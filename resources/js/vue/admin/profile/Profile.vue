<template>
  <div class="row">
    <div class="col-xl-6">
      <profile-form></profile-form>
    </div>
    <div class="col-xl-6">
      <password-form></password-form>
    </div>
  </div>
</template>
<script>
import PasswordForm from "@/admin/profile/PasswordForm.vue";
import ProfileForm from "@/admin/profile/ProfileForm.vue";

export default {
  components: {
    PasswordForm,
    ProfileForm,
  },
};
</script>