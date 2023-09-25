import { createApp } from "vue";
import { createPinia } from "pinia";
import router from "./router";
import {
  Menu,
  List,
  Drawer,
  Button,
  message,
  Card,
  Table,
  Input,
  Select,
  Image
} from "ant-design-vue";
import App from "./App.vue";
import axios from "axios";
window.axios = axios;

import vue3GoogleLogin from "vue3-google-login";
const CLIENT_ID =
  "180080061879-2nhkoqn4jcff8sgmfum7b6pqifcga919.apps.googleusercontent.com";

import "ant-design-vue/dist/reset.css";
import "./assets/styles/app.css";
import "./assets/styles/tailwind.css";
import "./assets/styles/responsive.css";

const app = createApp(App);
const pinia = createPinia();
app.use(router);
app.use(Button);
app.use(Drawer);
app.use(List);
app.use(Menu);
app.use(Card);
app.use(Table);
app.use(Input);
app.use(Select);
app.use(Image);
app.use(vue3GoogleLogin, { clientId: CLIENT_ID });
app.use(pinia);
app.mount("#app");

app.config.globalProperties.$message = message;
