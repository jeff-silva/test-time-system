export default () => {
  const r = reactive({
    ready: false,
    token: localStorage.getItem("test-time-system-token"),

    setToken(token) {
      r.token = token;
      localStorage.setItem("test-time-system-token", token);
    },

    async load() {
      r.ready = true;
    },
  });

  r.load();
  return r;
};
