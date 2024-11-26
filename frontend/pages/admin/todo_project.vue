<template>
  <nuxt-layout name="admin">
    <div class="d-flex justify-end">
      <v-btn
        text="Novo"
        color="primary"
        prepend-icon="mdi-plus"
        @click="todoProjectSave.create()"
      />
    </div>
    <br />

    <v-card :loading="todoProjectList.busy">
      <v-table>
        <colgroup>
          <col width="*" />
          <col width="200px" />
          <col width="0" />
          <col width="0" />
        </colgroup>
        <thead>
          <tr>
            <th>Projeto</th>
            <th>Tasks</th>
            <th></th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          <template v-for="o in todoProjectList.response.data">
            <tr>
              <td>{{ o.name }}</td>
              <td>
                <div
                  class="text-center"
                  :class="{
                    'text-success':
                      o.computed.tasksFinished == o.computed.tasksTotal,
                  }"
                  style="white-space: nowrap"
                >
                  {{ o.computed.tasksFinished }} /
                  {{ o.computed.tasksTotal }}
                </div>
                <v-progress-linear
                  :color="
                    o.computed.tasksFinished == o.computed.tasksTotal
                      ? 'success'
                      : null
                  "
                  :model-value="
                    (o.computed.tasksFinished * 100) / o.computed.tasksTotal
                  "
                />
              </td>
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
      max-height="95vh"
      scrollable
      @update:modelValue="
        () => {
          todoProjectTaskUpdate.data = {};
          todoProjectTaskList.params.project_id = null;
          todoProjectTaskList.response.data = [];
          todoProjectList.submit();
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

          <template v-if="todoProjectSave.data.id">
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

            <table
              cellspacing="10"
              class="w-100"
            >
              <colgroup>
                <col width="45" />
                <col width="*" />
                <col width="0" />
              </colgroup>
              <tbody>
                <template v-for="o in todoProjectTaskList.response.data">
                  <tr>
                    <td class="pa-0">
                      <v-checkbox-btn
                        v-model="o.finished"
                        :true-value="1"
                        :false-value="null"
                        true-icon="material-symbols:check-box-outline"
                        false-icon="material-symbols:check-box-outline-blank"
                        density="comfortable"
                        class="d-flex justify-center"
                        style="zoom: 1.4"
                        :color="o.finished == 1 ? 'success' : null"
                        @update:model-value="todoProjectTaskUpdate.update(o)"
                      />
                    </td>
                    <td>
                      <v-text-field
                        v-model="o.name"
                        hide-details="auto"
                        density="compact"
                        variant="plain"
                        :style="{
                          textDecoration:
                            o.finished == 1 ? 'line-through' : null,
                        }"
                        @focus="todoProjectTaskView.id = o.id"
                        @change="todoProjectTaskUpdate.update(o)"
                      />

                      <v-expand-transition>
                        <v-textarea
                          v-if="todoProjectTaskView.id == o.id"
                          v-model="o.description"
                          label="Descrição"
                          class="mt-2"
                          @change="todoProjectTaskUpdate.update(o)"
                        />
                      </v-expand-transition>
                    </td>
                    <td class="pa-0">
                      <v-btn
                        icon="mdi-delete"
                        color="error"
                        flat
                        @click="todoProjectTaskDelete.delete(o)"
                      />
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </template>
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
  </nuxt-layout>
</template>

<script setup>
definePageMeta({ middleware: ["admin"] });

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
  async update(data) {
    todoProjectTaskUpdate.method = "put";
    todoProjectTaskUpdate.url = `api://todo_project_task/${data.id}`;
    todoProjectTaskUpdate.data = data;
    await todoProjectTaskUpdate.submit();
  },
  onSuccess() {
    todoProjectTaskUpdate.data = {};
    // todoProjectTaskList.submit();
  },
});

const todoProjectTaskView = reactive({
  id: null,
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
