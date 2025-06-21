import { jwtDecode } from "jwt-decode";
import { useApi } from "@/composables/useApi.ts";
import { useUser, type DecodedToken } from "@/composables/useUser.ts";

const { token, user } = useUser();

async function login(username: string, password: string) {
  await useApi()
    .login(username, password)
    .then(({ data, errors }) => {
      if (errors !== undefined) {
        console.log(errors);
        return;
      }

      token.value = data.token;
      user.value = jwtDecode<DecodedToken>(data.token);

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
