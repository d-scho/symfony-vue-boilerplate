import { defineStore } from "pinia";
import { ref } from "vue";
import { jwtDecode } from "jwt-decode";

type SuccessData = {
  token: string;
};

type ErrorData = {
  code: number;
  message: string;
};

export type Roles = Array<"ROLE_ADMIN" | "ROLE_USER">;

type DecodedToken = {
  exp: number;
  iat: number;
  roles: Roles;
  username: string;
  displayName: string;
};

const user = ref<DecodedToken | null>(null);

export type User = DecodedToken | null;

function login(username: string, password: string): Promise<string> {
  return fetch("https://symfony-vue-boilerplate-backend.ddev.site/api/login", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ username, password }),
  })
    .then(async (res: Response) => {
      if (!res.ok) {
        const data: ErrorData = await res.json();
        throw new Error(data.message || "Login failed");
      }

      const data: SuccessData = await res.json();

      user.value = jwtDecode<DecodedToken>(data.token);

      console.log(user.value);

      return data.token;
    })
    .catch((error: Error) => {
      throw error;
    });
}

function logout() {
  user.value = null;
}

export const useAuthenticationStore = defineStore("authentication", () => {
  return { login, user, logout };
});
