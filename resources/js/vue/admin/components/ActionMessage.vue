<template>
  <div
    v-if="message.text"
    :class="['alert', alertClass, 'alert-dismissible', 'fade', 'show']"
    role="alert"
  >
    <i :class="iconClass" class="me-2"></i>
    {{ message.text }}
    <button
      type="button"
      class="btn-close"
      @click="dismissMessage"
      aria-label="Close"
    ></button>
  </div>
</template>
  
  <script>
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters(["message"]),
    alertClass() {
      return this.message.type === "success" ? "alert-success" : "alert-danger";
    },
    iconClass() {
      return this.message.type === "success"
        ? "mdi mdi-check-all"
        : "mdi mdi-block-helper";
    },
  },
  methods: {
    dismissMessage() {
      this.$store.commit("clearMessage");
    },
  },
};
</script>
  
  <style scoped>
/* Add any scoped styles here */
</style>
  