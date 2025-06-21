import { jwtDecode } from "jwt-decode";
import { useApi } from "@/composables/useApi.ts";
import { useUser, type DecodedToken } from "@/composables/useUser.ts";

const { token, user } = useUser();

async function login(username: string, password: string) {
  await useApi()
    .login(username, password)
    .then((res) => {
      if ("errors" in res) {
        console.log(res.errors);
        return;
      }

      token.value = res.data.token;
      user.value = jwtDecode<DecodedToken>(res.data.token);

      console.log(token.value);
      console.log(user.value);
    });
}

function logout() {
  token.value = null;
  user.value = null;
}

export function useAuthentication() {
  return { login, logout };
}
