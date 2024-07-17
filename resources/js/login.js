import "./bootstrap";
import { createApp } from "vue";
import Login from "./vue/auth/Login.vue";
import ResetPassword from "./vue/auth/ResetPassword.vue";

const app = createApp({});
app.component("login", Login);
app.component("reset-password", ResetPassword);
app.mount("#app");
