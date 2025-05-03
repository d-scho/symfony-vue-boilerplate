import { ref } from "vue";

export type Roles = Array<"ROLE_ADMIN" | "ROLE_USER">;

export type DecodedToken = {
  exp: number;
  iat: number;
  roles: Roles;
  username: string;
  displayName: string;
};

const token = ref<string | null>(null);

const user = ref<DecodedToken | null>(null);

export function useUser() {
  return {
    token,
    user,
  };
}
