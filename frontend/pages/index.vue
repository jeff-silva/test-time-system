<template>
  <div
    class="d-flex align-center justify-center"
    style="height: 100vh; background: #b55ea1"
  >
    <v-form @submit.prevent="login.submit()">
      <v-card style="min-width: 400px; max-width: 400px">
        <v-card-title>Login</v-card-title>
        <v-card-subtitle>Informe e-mail e senha.</v-card-subtitle>
        <v-card-text>
          <v-text-field
            v-model="login.data.email"
            label="E-mail"
          />
          <v-text-field
            v-model="login.data.password"
            label="Senha"
            type="password"
          />
        </v-card-text>
        <v-card-actions class="justify-end">
          <v-btn
            text="Login"
            class="bg-primary"
            type="submit"
            :loading="login.busy"
          />
        </v-card-actions>
      </v-card>
    </v-form>
  </div>
</template>

<script setup>
const app = useApp();
const route = useRoute();
const router = useRouter();

const login = useRequest({
  method: "post",
  url: "api://auth/login",
  data: {
    email: "main@grr.la",
    password: "main@grr.la",
  },
  onSuccess(resp) {
    app.setToken(resp.data.access_token);
    router.push(route.query.redirect ?? "/admin");
  },
});
</script>
