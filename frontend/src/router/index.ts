import { createRouter, createWebHistory, type RouteMeta } from "vue-router";
import { useUser, type Roles } from "@/composables/useUser.ts";

type ExtendedRouteMeta = RouteMeta & {
  requiresAuthentication: true | undefined;
  roles: Roles | undefined;
};

const { user } = useUser();

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
  const targetRouteMeta = to.meta as ExtendedRouteMeta;

  const requiresAuthentication = !!targetRouteMeta.requiresAuthentication;
  const requiresAuthorization = (targetRouteMeta.roles?.length ?? 0) > 0;

  if (requiresAuthentication) {
    if (user.value === null) {
      return next({ path: "/login", query: { t: to.path } });
    }

    if (
      requiresAuthorization &&
      (user.value.roles.length === 0 ||
        user.value.roles.every((role) => !targetRouteMeta.roles!.includes(role)))
    ) {
      return next({ path: "/unauthorized", query: { t: to.path } });
    }
  }

  return next();
});

export default router;
