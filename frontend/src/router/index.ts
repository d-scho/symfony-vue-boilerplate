import { createRouter, createWebHistory } from "vue-router";
import LoginView from "../views/LoginView.vue";
import { useAuthenticationStore } from "@/stores/authentication.ts";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/login",
      name: "login",
      component: LoginView,
    },
    {
      path: "/",
      name: "home",
      component: () => import("../views/HomeView.vue"),
      meta: { requiresAuth: true, roles: ["ROLE_ADMIN"] },
    },
    {
      path: "/foo",
      name: "foo",
      component: () => import("../views/HomeView.vue"),
      meta: { requiresAuth: true, roles: ["ROLE_SUPER_ADMIN"] },
    },
  ],
});

router.beforeEach((to, from, next) => {
  const { user } = useAuthenticationStore();

  if (to.meta.requiresAuth) {
    if (user === null) {
      return next("/login?noAuth");
    }

    if (user.roles.length === 0 || user.roles.every((role) => !to.meta.roles.includes(role))) {
      return next("/login?noRoles"); // TODO: What to do?
    }
  }

  return next();
});

export default router;
