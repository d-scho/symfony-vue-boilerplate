import { createRouter, createWebHistory } from "vue-router";
import { useAuthenticationStore } from "@/stores/authentication.ts";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/login",
      name: "LoginView",
      component: () => import("../views/LoginView.vue"),
    },
    {
      path: "/unauthorized",
      name: "UnauthorizedView",
      component: () => import("../views/UnauthorizedView.vue"),
      meta: { requiresAuthentication: true },
    },
    {
      path: "/user",
      name: "UserView",
      component: () => import("../views/UserView.vue"),
      meta: { requiresAuthentication: true, roles: ["ROLE_USER"] },
    },
    {
      path: "/admin",
      name: "AdminView",
      component: () => import("../views/AdminView.vue"),
      meta: { requiresAuthentication: true, roles: ["ROLE_ADMIN"] },
    },
    {
      path: "/:pathMatch(.*)*",
      name: "NotFound",
      component: () => import("../views/NotFoundView.vue"),
    },
  ],
});

router.beforeEach((to, from, next) => {
  const { user } = useAuthenticationStore();

  const requiresAuthentication = !!to.meta.requiresAuthentication;
  const requiresAuthorization = to.meta.roles !== undefined;

  console.log(requiresAuthentication);
  console.log(requiresAuthorization);

  if (requiresAuthentication) {
    if (user === null) {
      return next("/login");
    }

    if (
      requiresAuthorization &&
      (user.roles.length === 0 || user.roles.every((role) => !to.meta.roles?.includes(role)))
    ) {
      return next({ path: "/unauthorized", query: { t: to.path } });
    }
  }

  return next();
});

export default router;
