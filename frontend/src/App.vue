<script setup lang="ts">
import { RouterView, useRouter } from "vue-router";
import { useUser } from "@/composables/useUser.ts";
import { useAuthentication } from "@/composables/useAuthentication.ts";

const { user } = useUser();
const { logout: authLogout } = useAuthentication();

const router = useRouter();

function logout() {
  authLogout();

  router.push("/login");
}
</script>

<template>
  <header>
    <nav v-if="user">
      <router-link to="/user">User</router-link>
      <router-link to="/admin">Admin</router-link>
      <template v-if="user">
        <span
          >Logged in as <b>{{ user.displayName }}</b></span
        >
        <button @click="logout">Logout</button>
      </template>
    </nav>
    <nav v-else>
      <router-link to="/login">Login</router-link>
    </nav>
  </header>

  <main>
    <router-view />
  </main>
</template>

<style scoped>
main {
  margin: 2rem;
}

nav {
  display: flex;
  gap: 1rem;
}
</style>
