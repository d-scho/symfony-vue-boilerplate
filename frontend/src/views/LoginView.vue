<script setup lang="ts">
import { ref } from "vue";
import { useAuthenticationStore } from "@/stores/authentication.ts";
import { useRouter, useRoute } from "vue-router";

const { login } = useAuthenticationStore();

const router = useRouter();

const route = useRoute();
const target = route.query.t;

const username = ref("admin");
const password = ref("admin-pw");

const usernameError = ref("");
const passwordError = ref("");

const loginError = ref("");

const isLoading = ref(false);

async function submit(event: Event) {
  event.preventDefault();

  isLoading.value = true;

  usernameError.value = "";
  passwordError.value = "";
  loginError.value = "";

  let abort = false;

  if (username.value.length < 2) {
    usernameError.value = "Please enter at least two characters.";
    abort = true;
  }

  if (password.value.length < 6) {
    passwordError.value = "Please enter at least 6 characters.";
    abort = true;
  }

  if (abort) {
    isLoading.value = false;
    return;
  }

  login(username.value, password.value)
    .then(() => {
      router.push(typeof target === "string" ? target : "/");
    })
    .catch((error: Error) => {
      loginError.value = error.message;
    })
    .finally(() => {
      isLoading.value = false;
    });
}
</script>

<template>
  <form>
    <fieldset class="input-fields">
      <div>
        <label for="username">Username</label>
        <input id="username" required v-model="username" />
        <span v-if="usernameError.length > 0">{{ usernameError }}</span>
      </div>
      <div>
        <label for="username">Password</label>
        <input id="password" required v-model="password" />
        <span v-if="passwordError.length > 0">{{ passwordError }}</span>
      </div>
    </fieldset>

    <fieldset>
      <button @click="submit">{{ isLoading ? "-" : "Submit" }}</button>
      <span v-if="loginError.length > 0">{{ loginError }}</span>
    </fieldset>
  </form>
</template>

<style scoped>
form {
  max-width: 400px;
  margin: 0 auto;
  padding: 2rem;
  border: 1px solid black;
  border-radius: 10px;
}

fieldset {
  border: none;

  &.input-fields {
    margin-bottom: 1.6rem;
  }
}

label,
input,
button {
  display: block;
  width: 100%;
}

label {
  margin-bottom: 0.4rem;
  font-weight: bold;
}

input,
button {
  padding: 0.4rem 0.8rem;
  margin-bottom: 0.8rem;
}

button {
  cursor: pointer;
}
</style>
