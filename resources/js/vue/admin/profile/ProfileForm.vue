<template>
  <div class="card">
    <loading v-if="dataLoading"/>
    <div class="card-body">
      <h4 class="card-title">Manage - Profile Information</h4>
      <p class="card-title-desc">Please update your profile information here</p>
      <action-message v-if="message" />
      <!-- Success message -->
      <form @submit.prevent="updateProfile">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input
                type="text"
                ref="name"
                :class="['form-control', { 'parsley-error': errors.name }]"
                v-model="profile.name"
                id="name"
                placeholder="Full name"
                required
              />
              <small v-if="errors.name" class="text-danger">{{
                errors.name[0]
              }}</small>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                ref="email"
                :class="['form-control', { 'parsley-error': errors.email }]"
                v-model="profile.email"
                id="email"
                placeholder="Email"
                required
              />
              <small v-if="errors.email" class="text-danger">{{
                errors.email[0]
              }}</small>
            </div>
          </div>
        </div>
        <button
          class="btn btn-primary waves-effect waves-light"
          type="submit"
          :disabled="loading"
        >
          <span v-if="loading" class="spinner-border spinner-border-sm"></span>
          <span v-if="loading"> process...</span>
          <span v-else>Update</span>
        </button>
      </form>
    </div>
  </div>
</template>
  
<script>
import Loading from "@/admin/components/Loading.vue";
export default {
  components: {
    Loading,
  },
  data() {
    return {
      profile: {
        name: "",
        email: "",
      },
      errors: {},
      loading: false,
      dataLoading: false,
      message: false,
    };
  },
  created() {
    this.fetchProfileData();
  },
  methods: {
    fetchProfileData() {
      this.dataLoading = true;
      axios
        .get("/admin/profile/information")
        .then((response) => {
          this.profile = response.data;
          this.dataLoading = false;
        })
        .catch((error) => {
          this.$store.dispatch("showMessage", {
            text: "Error fetching profile data:",
            error,
            type: "error",
          });
        });
    },
    updateProfile() {
      this.loading = true;
      this.$store.dispatch("showMessage", { text: "", type: "" });
      axios
        .post("/admin/profile/information/update", this.profile)
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
            if (this.errors.email) {
              this.$refs.email.focus();
            }
            if (this.errors.name) {
              this.$refs.name.focus();
            }
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
      this.message = false;
    },
  },
};
</script>
<style scoped>
.alert {
  margin-top: 10px;
}
</style>