// import { createApp } from 'vue'
// import App from './App.vue'

// import './assets/styles/app.css'
// import './assets/styles/tailwind.css'
// import './assets/styles/responsive.css'

// createApp(App).mount('#app')

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import {
    Menu,
    List,
    Drawer,
    Button,
    message,
    Card,
    Table,
    Input,
} from 'ant-design-vue';
import App from './App.vue';
import axios from 'axios';
window.axios = axios;

import 'ant-design-vue/dist/reset.css';
import './assets/styles/app.css'
import './assets/styles/tailwind.css'
import './assets/styles/responsive.css';

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
app.use(pinia);
app.mount('#app');

app.config.globalProperties.$message = message;
