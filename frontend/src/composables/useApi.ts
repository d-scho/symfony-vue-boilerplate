import { useUser } from "@/composables/useUser.ts";

const { token } = useUser();

type Method = "GET" | "POST" | "PUT" | "PATCH" | "DELETE";
type URI = `/${string}`;

function apiFetch<T>(
  method: Method,
  uri: URI,
  token: string | null = null,
  body: object | null = null,
): Promise<T> {
  const init: RequestInit = {
    method,
    headers: {
      "Content-Type": "application/json",
    },
  };

  if (token !== null) {
    init.headers = {
      ...init.headers,
      Authorization: `Bearer ${token}`,
    };
  }

  if (body !== null) {
    init.body = JSON.stringify(body);
  }

  return fetch(import.meta.env.VITE_BACKEND_DOMAIN + uri, init).then((res) => res.json() as T);
}

const api = {
  login: (username: string, password: string) =>
    apiFetch<{ token: string }>("POST", "/api/login", null, { username, password }),
  users: {
    getAll: () =>
      apiFetch<Array<{ username: string; displayName: string }>>("GET", "/api/users", token.value),
  },
} as const;

export function useApi() {
  return api;
}
