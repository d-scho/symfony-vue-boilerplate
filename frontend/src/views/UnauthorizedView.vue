<script setup lang="ts">
import { useRoute } from "vue-router";

const route = useRoute();

const target = route.query.t;
</script>

<template>
  <div v-if="target">You're not authorized to see '{{ target }}'.</div>
  <div v-else>You're not authorized to see the requested page.</div>
</template>
