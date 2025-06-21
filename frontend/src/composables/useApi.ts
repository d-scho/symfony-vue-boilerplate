import { useUser } from "@/composables/useUser.ts";

type SymfonyApiError = {
  // Problem Details https://datatracker.ietf.org/doc/html/rfc7807 (Symfony default)
  type?: string;
  title?: string;
  detail?: string;
  status?: number;
  violations?: Array<{
    propertyPath: string;
    title: string;
  }>

  // when 401 by JWT bundle
  code?: string;
  message?: string;
};

type Errors = {
  genericErrors: Array<{
    status: number;
    message: string;
  }>;
  validationErrors: {
    [key: string]: string;
  };
}

type Method = "GET" | "POST" | "PUT" | "DELETE";
type URI = `/${string}`;

async function request<T>(
  method: Method,
  uri: URI,
  token: string | null = null,
  body: object | null = null,
): Promise<{data?: T, errors?: Errors}> {
  const init: RequestInit = {
    method,
    headers: {
      Accept: "application/problem+json, application/json",

    },
  };

  if (token !== null) {
    (init.headers as Record<string, string>)["Authorization"] = `Bearer ${token}`;
  }

  if (body !== null) {
    const stringified = JSON.stringify(body);

    (init.headers as Record<string, string>)["Content-Type"] = "application/json";
    init.body = stringified;
  }

  try {
    const res = await fetch(import.meta.env.VITE_BACKEND_DOMAIN + uri, init);

    const contentType = res.headers.get("Content-Type") || "";
    if (!contentType.includes("application/json")) {
      throw new Error(`Unexpected content type: ${contentType}`);
    }

    const json = await res.json();

    if (res.ok) {
      return { data: json as T };
    }

    const errorData = json as SymfonyApiError;

    const errors: Errors = {
      genericErrors: [],
      validationErrors: {},
    };

    if (errorData.violations && Array.isArray(errorData.violations)) {
      errorData.violations.forEach((violation) => {
        errors.validationErrors[violation.propertyPath] = violation.title;
      });
    } else {
      errors.genericErrors.push({
        status: errorData.status ?? errorData.code ?? res.status,
        message: getErrorMessage(errorData)
      });
    }

    return { errors };

  } catch (err: unknown) {
    return {
      errors: {
        genericErrors: [
          {
            status: 0,
            message:
              err instanceof Error
                ? err.message
                : "Something went wrong.",
          },
        ],
        validationErrors: {},
      },
    };
  }
}

function getErrorMessage(error: SymfonyApiError): string {
  if (error.title && error.detail) return `${error.title} - ${error.detail}`;
  if (error.title) return error.title;
  if (error.detail) return error.detail;
  if (error.message) return error.message;
  return "An unknown error occurred.";
}

const { token } = useUser();

const login = (username: string, password: string) =>
    request<{ token: string }>("POST", "/api/login", null, { username, password });

const getAll = () =>
  request<Array<{ uuid: string, title: string }>>("GET", "/api/examples", token.value, null);

export function useApi() {
  return {
    login,
    getAll
  };
}
