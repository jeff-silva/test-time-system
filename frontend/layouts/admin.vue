<template>
  <v-app id="inspire">
    <v-defaults-provider
      :defaults="{
        VContainer: { maxWidth: 1200 },
      }"
    >
      <v-navigation-drawer width="244">
        <v-sheet
          color="grey-lighten-5"
          height="128"
          width="100%"
        ></v-sheet>

        <v-list>
          <v-list-item
            to="/admin"
            text="Dashboard"
          />
          <v-list-item
            to="/admin/todo_project"
            text="Projetos"
          />
        </v-list>
      </v-navigation-drawer>

      <v-app-bar
        class="px-3"
        color="grey-lighten-4"
        height="72"
        flat
      >
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
