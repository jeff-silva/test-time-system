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
      max-width="800"
      scrollable
      @update:modelValue="
        () => {
          todoProjectTaskUpdate.data = {};
          todoProjectTaskList.params.project_id = null;
          todoProjectTaskList.response.data = [];
        }
      "
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

          <strong>Tasks</strong>
          <br />

          <v-text-field
            v-model="todoProjectTaskCreate.data.name"
            :append-inner-icon="
              todoProjectTaskCreate.busy
                ? 'line-md:loading-twotone-loop'
                : 'mdi-plus'
            "
            @keyup.enter="todoProjectTaskCreate.create()"
            @click:append-inner="todoProjectTaskCreate.create()"
          />

          <v-expansion-panels variant="accordion">
            <template v-for="o in todoProjectTaskList.response.data">
              <v-expansion-panel
                :title="o.name"
                :value="o.id"
              >
                <v-expansion-panel-text>
                  <v-text-field
                    v-model="o.name"
                    label="Nome"
                  />
                  <v-textarea
                    v-model="o.description"
                    label="Descrição"
                  />
                  <v-switch
                    hide-details="auto"
                    v-model="o.finished"
                    :true-value="1"
                    false-value=""
                    :color="o.finished ? 'success' : null"
                    :label="o.finished ? 'Concluído' : 'Em andamento'"
                  />

                  <div class="d-flex justify-end">
                    <v-btn
                      text="Deletar"
                      color="error"
                      @click="todoProjectTaskDelete.delete(o)"
                    />
                    <v-spacer />
                    <v-btn
                      text="Salvar"
                      color="success"
                      :loading="todoProjectTaskUpdate.busy"
                      @click="todoProjectTaskUpdate.update(o)"
                    />
                  </div>
                </v-expansion-panel-text>
              </v-expansion-panel>
            </template>
          </v-expansion-panels>

          <!-- <pre>{{ todoProjectTaskList }}</pre> -->
          <!-- <pre>{{ todoProjectTaskUpdate }}</pre> -->
          <!-- <pre>todoProjectTaskDelete: {{ todoProjectTaskDelete }}</pre> -->
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
      v-model="dialog.value"
      max-width="400"
    >
      <v-card>
        <v-card-title class="bg-error">{{ dialog.title }}</v-card-title>
        <v-card-text>{{ dialog.text }}</v-card-text>
        <v-card-actions>
          <v-btn
            text="Confirmar"
            class="bg-error"
            @click="dialog.actionConfirm()"
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
    todoProjectTaskUpdate.data.project_id = data.id;
    todoProjectTaskList.params.project_id = data.id;
    todoProjectTaskList.submit();
  },
  async onSuccess(resp) {
    if (resp.method == "POST") {
      todoProjectSave.update(resp.data.entity);
    } else {
      todoProjectDialog.set(false);
    }
    await todoProjectList.submit();
  },
});

const todoProjectDelete = useRequest({
  method: "delete",
  url: "api://todo_project",
  item: false,
  dialog: false,
  delete(data) {
    dialog.confirm({
      title: `Deletar ${data.name}?`,
      async onSuccess() {
        todoProjectDelete.url = `api://todo_project/${data.id}`;
        todoProjectDelete.submit();
      },
    });
  },
  async onSuccess() {
    todoProjectDelete.dialog = false;
    await todoProjectList.submit();
  },
});

const todoProjectTaskList = useRequest({
  method: "get",
  url: "api://todo_project_task",
  params: { project_id: null },
  response: { data: [] },
});

const todoProjectTaskCreate = useRequest({
  method: "post",
  url: "api://todo_project_task",
  onSuccess() {
    todoProjectTaskCreate.data = {};
    todoProjectTaskList.submit();
  },
  async create() {
    todoProjectTaskCreate.data.project_id = todoProjectSave.data.id;
    await todoProjectTaskCreate.submit();
    todoProjectTaskCreate.data = {};
  },
});

const todoProjectTaskUpdate = useRequest({
  method: "post",
  url: "api://todo_project_task",
  update(data) {
    todoProjectTaskUpdate.method = "put";
    todoProjectTaskUpdate.url = `api://todo_project_task/${data.id}`;
    todoProjectTaskUpdate.data = data;
    todoProjectTaskUpdate.submit();
  },
  onSuccess() {
    todoProjectTaskUpdate.data = {};
    todoProjectTaskList.submit();
  },
});

const todoProjectTaskDelete = useRequest({
  method: "delete",
  url: "api://todo_project_task",
  delete(data) {
    dialog.confirm({
      title: `Deletar ${data.name}?`,
      async onSuccess() {
        todoProjectTaskDelete.url = `api://todo_project_task/${data.id}`;
        await todoProjectTaskDelete.submit();
      },
    });
  },
  onSuccess() {
    todoProjectTaskList.submit();
  },
});

const todoProjectDialog = reactive({
  value: false,
  set(value) {
    todoProjectDialog.value = value;
  },
});

const dialog = reactive({
  value: false,
  title: "",
  text: "",
  onSuccess: () => {},
  onError: () => {},
  set(value) {
    dialog.value = value;
  },
  confirm(data) {
    dialog.set(true);
    dialog.title = data.title ?? "Deletar item?";
    dialog.text = data.text ?? "Deseja prosseguir com a ação?";
    dialog.onSuccess = data.onSuccess ?? function () {};
    dialog.onError = data.onError ?? function () {};
  },
  actionConfirm() {
    dialog.set(false);
    dialog.onSuccess();
  },
  actionError() {
    dialog.set(false);
    dialog.onError();
  },
});

onMounted(() => {
  todoProjectList.submit();
});
</script>
