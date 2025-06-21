<script setup lang="ts">
import {ref} from "vue";
import {useApi} from "@/composables/useApi.js";

const fetchedData = ref(null);
const fetchedErrors = ref(null);

useApi().getAll().then(({ data, errors }) => {
  if (data) {
    fetchedData.value = data;
    return
  }

  fetchedErrors.value = errors;
});
</script>

<template>
  <div>✅ only accessible to admins ✅</div>
  <div v-if="fetchedData">{{ fetchedData }}</div>
  <div v-if="fetchedErrors">{{ fetchedErrors }}</div>
</template>
