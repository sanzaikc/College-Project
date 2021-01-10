import axios from "axios";
import store from "../store"

const $axios = axios.create({
  baseURL: "http://127.0.0.1:8000/api/"
});

$axios.interceptors.request.use(config => {
  const token = store.getters.token;
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default $axios;
