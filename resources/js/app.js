import "./bootstrap";
import { createApp } from "vue";
import store from "./store";
import ActionMessage from "./vue/admin/components/ActionMessage.vue";
import TableComponent from "./vue/admin/components/TableComponent.vue";
import Modal from "./vue/admin/components/Modal.vue";
import Profile from "./vue/admin/profile/Profile.vue";
import UserList from "./vue/admin/user/List.vue";

const app = createApp({});
app.use(store);
app.component("action-message", ActionMessage);
app.component("TableComponent", TableComponent);
app.component("modal", Modal);
app.component("profile", Profile);
app.component("user-list", UserList);
app.mount("#app");
