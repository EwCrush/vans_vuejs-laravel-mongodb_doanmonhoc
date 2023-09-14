// import { createApp } from 'vue'
// import App from './App.vue'

// import './assets/styles/app.css'
// import './assets/styles/tailwind.css'
// import './assets/styles/responsive.css'

// createApp(App).mount('#app')
// import Vue from 'vue';
import { VueElement, createApp } from 'vue';
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

//import { createAuth0 } from '@auth0/auth0-vue';
import vue3GoogleLogin from 'vue3-google-login';
const CLIENT_ID = '180080061879-5pp5218hnpos9trjmbo31rvuhj0gtmvr.apps.googleusercontent.com';

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
// app.use(
//     createAuth0({
//       domain: "dev-hhyq4gpbzr30dmar.us.auth0.com",
//       clientId: "r7mNfw804s3Q7WmKEPoK7dVFh9hQWOcI",
//       authorizationParams: {
//         redirect_uri: window.location.origin
//       }
//     })
//   );
app.use(vue3GoogleLogin, {clientId: CLIENT_ID})

app.use(pinia);
//app.use(vue3GoogleLogin, {clientId: CLIENT_ID})
app.mount('#app');

app.config.globalProperties.$message = message;
