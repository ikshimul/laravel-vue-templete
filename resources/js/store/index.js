import { createStore } from "vuex";

const store = createStore({
    state: {
        message: {
            text: "",
            type: "", // 'success' or 'error'
        },
    },
    mutations: {
        setMessage(state, { text, type }) {
            state.message = { text, type };
        },
        clearMessage(state) {
            state.message = { text: "", type: "" };
        },
    },
    actions: {
        showMessage({ commit }, { text, type, duration = 5000 }) {
            commit("setMessage", { text, type });
            setTimeout(() => {
                commit("clearMessage");
            }, duration);
        },
    },
    getters: {
        message(state) {
            return state.message;
        },
    },
});

export default store;
