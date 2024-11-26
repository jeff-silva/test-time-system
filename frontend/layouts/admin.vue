<template>
  <v-app id="inspire">
    <v-defaults-provider
      :defaults="{
        VContainer: { maxWidth: 1200 },
      }"
    >
      <v-navigation-drawer
        v-model="drawer.value"
        width="300"
      >
        <v-list>
          <template v-for="o in nav.items">
            <v-list-item v-bind="o">{{ o.text }}</v-list-item>
          </template>
        </v-list>
      </v-navigation-drawer>

      <v-app-bar
        class="px-3"
        color="grey-lighten-4"
        height="72"
        flat
      >
        <v-btn
          icon="flowbite:bars-outline"
          class="d-lg-none"
          @click="drawer.toggle()"
        />
        <v-spacer />

        <v-responsive max-width="156">
          <v-text-field
            bg-color="grey-lighten-1"
            density="compact"
            rounded="pill"
            variant="solo-filled"
            flat
            hide-details
          ></v-text-field>
        </v-responsive>
      </v-app-bar>

      <v-main>
        <v-container>
          <slot></slot>
        </v-container>
      </v-main>
    </v-defaults-provider>
  </v-app>
</template>

<script setup>
const app = useApp();
const router = useRouter();

const nav = reactive({
  items: [
    { text: "Dashboard", to: "/admin" },
    { text: "Tarefas", to: "/admin/todo_project" },
    {
      text: "Sair",
      onClick() {
        app.setToken("");
        router.push("/");
      },
    },
  ],
});

const drawer = reactive({
  value: null,
  toggle() {
    drawer.set(!drawer.value);
  },
  set(value) {
    drawer.value = value;
  },
});
</script>
