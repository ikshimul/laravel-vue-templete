<template>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title">Manage - Password</h4>
      <p class="card-title-desc">Please update your password here</p>
      <action-message v-if="message" />
      <form
        @submit.prevent="updatePassword"
        class="needs-validation"
        novalidate
      >
        <div class="row">
          <div class="col-md-4">
            <div class="mb-3">
              <label for="currentPassword" class="form-label"
                >Current Password</label
              >
              <input
                v-model="formData.current_password"
                type="password"
                :class="[
                  'form-control',
                  { 'parsley-error': errors.current_password },
                ]"
                id="currentPassword"
                required
              />
              <small v-if="errors.current_password" class="text-danger">{{
                errors.current_password[0]
              }}</small>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label for="newPassword" class="form-label">New Password</label>
              <input
                v-model="formData.new_password"
                type="password"
                :class="[
                  'form-control',
                  { 'parsley-error': errors.new_password },
                ]"
                id="newPassword"
                required
              />
              <small v-if="errors.new_password" class="text-danger">{{
                errors.new_password[0]
              }}</small>
            </div>
          </div>
          <div class="col-md-4">
            <div class="mb-3">
              <label for="confirmPassword" class="form-label"
                >Confirm Password</label
              >
              <input
                v-model="formData.confirm_password"
                type="password"
                :class="[
                  'form-control',
                  { 'parsley-error': errors.confirm_password },
                ]"
                id="confirmPassword"
                required
              />
              <small v-if="errors.confirm_password" class="text-danger">{{
                errors.confirm_password[0]
              }}</small>
            </div>
          </div>
        </div>
        <div>
          <button :disabled="loading" class="btn btn-primary waves-effect waves-light" type="submit">
            <span
              v-if="loading"
              class="spinner-border spinner-border-sm"
            ></span>
            <span v-if="loading"> process...</span>
            <span v-else>Update Password</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      formData: {
        current_password: "",
        new_password: "",
        confirm_password: "",
      },
      errors: {},
      loading: false,
      message: false,
    };
  },
  methods: {
    updatePassword() {
      this.loading = true;
      axios
        .post("/admin/password/update", this.formData)
        .then((response) => {
          this.errors = {};
          this.message = true;
          this.$store.dispatch("showMessage", {
            text: response.data.message,
            type: "success",
          });
          setTimeout(() => {
            this.resetForm();
          }, 8000);
        })
        .catch((error) => {
          if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
          } else {
            this.message = true;
            this.$store.dispatch("showMessage", { text: error, type: "error" });
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    resetForm() {
      this.formData.current_password = "";
      this.formData.new_password = "";
      this.formData.confirm_password = "";
      this.errors = {};
      this.message = false;
    },
  },
};
</script>

<style scoped>
/* Add scoped styles here */
</style>
