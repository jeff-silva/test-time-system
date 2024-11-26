const app = useApp();

export default defineNuxtRouteMiddleware((to, from) => {
  if (!app.token) {
    return navigateTo(`/?redirect=${to.path}`);
  }
});
