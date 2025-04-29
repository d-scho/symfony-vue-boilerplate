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

type DecodedToken = {
  exp: number;
  iat: number;
  roles: array<"ROLE_ADMIN" | "ROLE_USER">;
  username: string;
};

const user = ref<DecodedToken | null>(null);

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
