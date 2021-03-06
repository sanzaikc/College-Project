import axios from "axios";
import store from "../store";

const $axios = axios.create({
  baseURL: process.env.VUE_APP_API_BASE_URL,
});

$axios.interceptors.request.use((config) => {
  const token = store.getters.token;
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default $axios;
