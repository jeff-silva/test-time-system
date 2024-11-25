<template>
  <nuxt-layout name="admin">
    <div class="d-flex justify-end">
      <v-btn
        text="Novo"
        color="primary"
        @click="todoProjectSave.create()"
      />
    </div>
    <br />

    <v-card :loading="todoProjectList.busy">
      <v-table>
        <colgroup>
          <col width="*" />
          <col width="0" />
          <col width="0" />
        </colgroup>
        <thead>
          <tr>
            <th>Projeto</th>
            <th></th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <template v-for="o in todoProjectList.response.data">
            <tr>
              <td>{{ o.name }}</td>
              <td class="pa-1">
                <v-btn
                  v-tooltip="'Editar'"
                  icon="mdi-pen"
                  color="success"
                  rounded="0"
                  size="40"
                  block
                  @click="todoProjectSave.update(o)"
                />
              </td>
              <td class="pa-1">
                <v-btn
                  v-tooltip="'Deletar'"
                  icon="mdi-delete"
                  color="red"
                  rounded="0"
                  size="40"
                  block
                  @click="todoProjectDelete.delete(o)"
                />
              </td>
            </tr>
          </template>
        </tbody>
      </v-table>
    </v-card>

    <v-dialog
      v-model="todoProjectDialog.value"
      max-width="600"
    >
      <v-card>
        <v-card-title>{{
          todoProjectSave.data.id ? "Editar" : "Criar"
        }}</v-card-title>
        <v-card-text>
          <v-text-field
            label="Nome"
            v-model="todoProjectSave.data.name"
          />
        </v-card-text>
        <v-card-actions>
          <v-btn
            text="Cancelar"
            @click="todoProjectDialog.set(false)"
          />
          <v-spacer />
          <v-btn
            text="Salvar"
            class="bg-primary"
            :loading="todoProjectSave.busy"
            @click="todoProjectSave.submit()"
          />
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog
      v-model="todoProjectDelete.dialog"
      max-width="400"
    >
      <v-card>
        <v-card-title class="bg-error"
          >Deletar {{ todoProjectDelete.item.name }}?</v-card-title
        >
        <v-card-text>A ação é irreversível</v-card-text>
        <v-card-actions>
          <v-btn
            text="Confirmar"
            class="bg-error"
            :loading="todoProjectDelete.busy"
            @click="todoProjectDelete.submit()"
          />
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- <pre>todoProjectSave: {{ todoProjectSave }}</pre> -->
    <!-- <pre>todoProjectList: {{ todoProjectList }}</pre> -->
  </nuxt-layout>
</template>

<script setup>
const todoProjectList = useRequest({
  method: "get",
  url: "api://todo_project",
  response: { data: [] },
});

const todoProjectSave = useRequest({
  method: "post",
  url: "api://todo_project",
  create() {
    todoProjectSave.method = "post";
    todoProjectSave.url = "api://todo_project";
    todoProjectSave.data = {};
    todoProjectDialog.set(true);
  },
  update(data) {
    todoProjectSave.method = "put";
    todoProjectSave.url = `api://todo_project/${data.id}`;
    todoProjectSave.data = { ...data };
    todoProjectDialog.set(true);
  },
  async onSuccess() {
    await todoProjectList.submit();
    todoProjectDialog.set(false);
  },
});

const todoProjectDelete = useRequest({
  method: "delete",
  url: "api://todo_project",
  item: false,
  dialog: false,
  delete(data) {
    todoProjectDelete.dialog = true;
    todoProjectDelete.url = `api://todo_project/${data.id}`;
    todoProjectDelete.item = data;
  },
  async onSuccess() {
    todoProjectDelete.dialog = false;
    await todoProjectList.submit();
  },
});

const todoProjectDialog = reactive({
  value: false,
  set(value) {
    todoProjectDialog.value = value;
  },
});

onMounted(() => {
  todoProjectList.submit();
});
</script>
