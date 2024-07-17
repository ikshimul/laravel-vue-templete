<template>
  <form
    v-if="!showForgotPassword"
    @submit.prevent="login"
    class="form-horizontal mt-3"
  >
    <loading v-if="redirectLoading" />
    <div class="form-group mb-3 row">
      <div class="col-12">
        <input
          v-model="form.email"
          ref="email"
          :class="['form-control', { 'parsley-error': errors.email }]"
          type="text"
          placeholder="Email"
        />
        <small v-if="errors.email" class="text-danger">
          {{ errors.email[0] }}
        </small>
      </div>
    </div>

    <div class="form-group mb-3 row">
      <div class="col-12">
        <input
          v-model="form.password"
          ref="password"
          :class="['form-control', { 'parsley-error': errors.password }]"
          type="password"
          placeholder="Password"
        />
        <small v-if="errors.password" class="text-danger">
          {{ errors.password[0] }}
        </small>
      </div>
    </div>

    <div class="form-group mb-3 row">
      <div class="col-12">
        <div class="custom-control custom-checkbox">
          <input
            v-model="form.remember"
            type="checkbox"
            class="custom-control-input"
            id="customCheck1"
          />
          <label class="form-label ms-1" for="customCheck1">Remember me</label>
        </div>
      </div>
    </div>

    <div class="form-group mb-3 text-center row mt-3 pt-1">
      <div class="col-12">
        <button
          class="btn btn-info w-100 waves-effect waves-light"
          type="submit"
          :disabled="loading"
        >
          <span v-if="loading" class="spinner-border spinner-border-sm"></span>
          <span v-if="loading"> process...</span>
          <span v-else>Log In</span>
        </button>
      </div>
    </div>
    <div class="form-group mb-0 row mt-2">
      <div class="col-sm-7 mt-3">
        <a
          href="javascript: void(0);"
          class="text-muted"
          @click.prevent="showForgotPasswordForm"
          ><i class="mdi mdi-lock"></i> Forgot your password?</a
        >
      </div>
    </div>
  </form>
  <form v-if="showForgotPassword" @submit.prevent="sendPasswordResetEmail">
    <div v-if="message" class="alert alert-success" role="alert">
      {{ successMessage }}
    </div>
    <div class="form-group mb-3 row">
      <div class="col-12">
        <input
          v-model="forgotPasswordEmail"
          :class="['form-control', { 'parsley-error': errors.email }]"
          type="email"
          placeholder="Enter your email"
        />
        <small v-if="errors.email" class="text-danger">
          {{ errors.email[0] }}
        </small>
      </div>
    </div>
    <div class="form-group mb-3 text-center row mt-3 pt-1">
      <div class="col-12">
        <button
          class="btn btn-info w-100 waves-effect waves-light"
          type="submit"
        >
          <span
            v-if="loading"
            class="spinner-border spinner-border-sm"
          ></span>
          <span v-if="loading"> process...</span>
          <span v-else>Email Password Reset Link</span>
        </button>
      </div>
    </div>
    <div class="form-group mb-0 row mt-2">
      <div class="col-sm-7 mt-3">
        <a
          href="javascript: void(0);"
          class="text-muted"
          @click.prevent="showLoginForm"
        >
          <i class="mdi mdi-chevron-left"></i> Back to Login
        </a>
      </div>
    </div>
  </form>
</template>

<script>
import axios from "axios";
import Loading from "@/admin/components/Loading.vue";

export default {
  components: {
    Loading,
  },
  data() {
    return {
      form: {
        email: "",
        password: "",
        remember: false,
      },
      errors: {},
      redirectLoading: false,
      loading: false,
      message: false,
      successMessage: "",
      forgotPasswordEmail: "",
      showForgotPassword: false,
    };
  },
  methods: {
    showLoginForm() {
      this.errors = {};
      this.showForgotPassword = false;
    },
    async login() {
      this.loading = true;
      this.errors = {};
      await axios
        .post("/login", {
          email: this.form.email,
          password: this.form.password,
          remember: this.form.remember,
        })
        .then((response) => {
          this.redirectLoading = true;
          if (response.status == 204) {
            setTimeout(() => {
              window.location.href = "/admin/dashboard";
            }, 100);
          }
          window.location.reload();
        })
        .catch((error) => {
          this.loading = false;
          if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
            if (this.errors.email) {
              this.$refs.email.focus();
            } else if (this.errors.password) {
              this.$refs.password.focus();
            }
            this.form.password = null;
          } else {
            console.error("Login failed:", error);
            alert("Login failed. Please check your credentials and try again.");
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    showForgotPasswordForm() {
      this.errors = {};
      this.showForgotPassword = true;
      this.forgotPasswordEmail = "";
    },
    sendPasswordResetEmail() {
      this.loading = true;
      axios
        .post("/forgot-password", {
          email: this.forgotPasswordEmail,
        })
        .then((response) => {
          this.errors = {};
          this.message = true;
          this.successMessage = response.data.message;
          this.forgotPasswordEmail = "";
        })
        .catch((error) => {
          if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
          } else {
            this.message = true;
            this.successMessage = error.response.data.message;
            console.log(error.response.data.message);
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>
