import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.min.js'

createApp(App).use(router).mount('#app')

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

import axios from 'axios';
export const axios_inst = axios.create({
    withCredentials: true,
    baseURL: "/api",
    headers: {'X-CSRF-TOKEN': getCookie('csrf_access_token')}
 })
