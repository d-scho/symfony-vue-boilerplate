import { useUser } from "@/composables/useUser.ts";

const { token } = useUser();

type UserApiResponse = {
  id: string;
  username: string;
  displayName: string;
};

type Method = "GET" | "POST" | "PUT" | "DELETE";
type URI = `/${string}`;

function req<T>(
  method: Method,
  uri: URI,
  token: string | null = null,
  body: object | null = null,
): Promise<T> {
  const init = {
    method,
  };

  if (token !== null) {
    init.headers["Authorization"] = `Bearer ${token}`;
  }

  if (body !== null) {
    const stringified = JSON.stringify(body);

    init.headers["Content-Type"] = "application/json";
    init.headers["Content-Length"] = stringified.length;
    init.body = stringified;
  }

  return fetch(import.meta.env.VITE_BACKEND_DOMAIN + uri, init).then((res) => res.json() as T);
}

const api = {
  login: (username: string, password: string) =>
    req<{ token: string }>("POST", "/api/login", null, { username, password }),
  users: {
    showAll: () => req<Array<UserApiResponse>>("GET", "/api/users", token.value),
    showSingle: (id: string) => req<UserApiResponse>("GET", `/api/users/${id}`, token.value),
    create: (username: string, password: string, displayName: string) =>
      req<UserApiResponse>("POST", "/api/users", token.value, { username, password, displayName }),
    update: (id: string, username: string, password: string, displayName: string) =>
      req<UserApiResponse>("PUT", `/api/users/${id}`, token.value, {
        username,
        password,
        displayName,
      }),
    delete: (id: string) => req<void>("DELETE", `/api/users/${id}`),
  },
} as const;

export function useApi() {
  return api;
}
