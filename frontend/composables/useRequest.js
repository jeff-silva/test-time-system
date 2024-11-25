export default (options = {}) => {
  options = {
    url: "",
    method: "get",
    params: {},
    data: {},
    headers: {
      "Content-Type": "application/json",
    },
    onSuccess(resp) {},
    onError(resp) {},
    response: false,
    ...options,
  };

  const urlSerialize = (obj) => {
    let str =
      (Object.keys(obj).length > 0 ? "?" : "") +
      Object.keys(obj)
        .reduce(function (a, k) {
          a.push(k + "=" + encodeURIComponent(obj[k]));
          return a;
        }, [])
        .join("&");
    return str;
  };

  const r = reactive({
    ...options,
    busy: false,

    async submit() {
      r.busy = true;

      try {
        let fetchOptions = {
          method: r.method.toUpperCase(),
          headers: { ...r.headers },
        };

        let fetchUrl = r.url;

        if (fetchUrl.startsWith("api://")) {
          fetchUrl = fetchUrl.replace("api://", "http://localhost/api/");
          const token = localStorage.getItem("test-time-system-token");
          if (token) {
            fetchOptions.headers["Authorization"] = `Bearer ${token}`;
          }
        }

        if (Object.keys(r.params).length > 0) {
          fetchUrl += urlSerialize(r.params);
        }

        if (["put", "post"].includes(r.method)) {
          fetchOptions.body = JSON.stringify(r.data);
        }

        const resp = await fetch(fetchUrl, fetchOptions);
        const data = await resp.json();
        options.onSuccess({ ...resp, data });

        r.response = data;
      } catch (err) {
        options.onError(err);
      }

      r.busy = false;
    },
  });

  return r;
};
