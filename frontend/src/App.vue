<script setup lang="ts">
import { RouterView, useRouter } from "vue-router";
import { useAuthenticationStore } from "@/stores/authentication.ts";
import { storeToRefs } from "pinia";

const authentication = useAuthenticationStore();
const { user } = storeToRefs(authentication);

const router = useRouter();

function logout() {
  authentication.logout();

  router.push("/login");
}
</script>

<template>
  <header>
    <nav>
      <router-link to="/">Home</router-link>
      <router-link to="/foo">Foo</router-link>
      <template v-if="user">
        <span>Logged in as {{ user.username }}</span>
        <button @click="logout">Logout</button>
      </template>
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
</style>
