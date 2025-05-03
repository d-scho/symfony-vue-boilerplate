<script setup lang="ts">
import { ref } from "vue";
import { useApi } from "@/composables/useApi.ts";

const users = ref<Array<{ username: string; displayName: string }>>([]);

useApi()
  .users.getAll()
  .then((fetchedUsers) => (users.value = fetchedUsers))
  .catch(console.log);
</script>

<template>
  <div>✅ only accessible to admins ✅</div>
  <div>{{ users }}</div>
</template>
