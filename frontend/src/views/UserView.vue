<script setup lang="ts">
import { ref } from "vue";
import { useApi } from "@/composables/useApi.js";

const fetchedData = ref<any>(null);
const fetchedErrors = ref<any>(null);

useApi()
  .getAll()
  .then((res) => {
    if ("data" in res) {
      fetchedData.value = res.data;
      return;
    }

    fetchedErrors.value = res.errors;
  });
</script>

<template>
  <div>✅ only accessible to users ✅</div>
  <div v-if="fetchedData">{{ fetchedData }}</div>
  <div v-if="fetchedErrors">{{ fetchedErrors }}</div>
</template>
