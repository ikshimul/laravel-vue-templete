<template>
  <form @submit.prevent="resetPassword" class="form-horizontal mt-3">
    <div v-if="message" class="alert alert-success" role="alert">
      {{ successMessage }}
    </div>
    <input type="hidden" v-model="form.token" />
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
        <input
          v-model="form.password_confirmation"
          ref="password_confirmation"
          :class="[
            'form-control',
            { 'parsley-error': errors.password_confirmation },
          ]"
          type="password"
          placeholder="Password Confirmation"
        />
        <small v-if="errors.password_confirmation" class="text-danger">
          {{ errors.password_confirmation[0] }}
        </small>
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
          <span v-else>Reset Password</span>
        </button>
      </div>
    </div>
    <div class="form-group mb-0 row mt-2">
      <div class="col-sm-7 mt-3">
        <a
          href="javascript: void(0);"
          class="text-muted"
          @click.prevent="goToLogin"
          ><i class="mdi mdi-lock"></i> Login</a
        >
      </div>
    </div>
  </form>
</template>
<script>
import axios from "axios";

export default {
  props: {
    token: {
      default: "",
    },
    email: {
      default: "",
    },
  },
  mounted() {
    this.form.email = this.email;
    this.form.token = this.token;
  },
  data() {
    return {
      form: {
        token: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
      errors: {},
      redirectLoading: false,
      loading: false,
      message: false,
      successMessage: "",
    };
  },
  methods: {
    resetPassword() {
      this.loading = true;
      axios
        .post(`/reset-password`, {
          token: this.form.token,
          email: this.form.email,
          password: this.form.password,
          password_confirmation: this.form.password_confirmation,
        })
        .then((response) => {
          this.message = true;
          this.successMessage = response.data.message;
          this.form.password = "";
          this.form.password_confirmation = "";
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
    goToLogin() {
      setTimeout(() => {
        window.location.href = "/login";
      }, 100);
    },
  },
};
</script>