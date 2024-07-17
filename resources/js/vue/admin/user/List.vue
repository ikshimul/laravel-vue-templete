<template>
  <div class="col-xl-12">
    <loading v-if="loading" />
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Manage - Admin Users</h4>
        <small>Please manage admins here</small>
        <action-message v-if="message" />
        <div class="row pt-3">
          <div class="col-md-6">
            <div
              class="d-flex flex-wrap align-items-center justify-content-sm-start"
            >
              <div class="button-items">
                <button
                  type="button"
                  class="btn btn-primary waves-effect waves-light"
                  @click="openAddModal()"
                >
                  Add Admin
                  <i class="ri-add-line align-middle ms-2"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div
              class="d-flex flex-wrap align-items-center justify-content-sm-end"
            >
              <label for="status-select" class="me-2">per page</label>
              <div class="me-sm-2">
                <select
                  class="form-select shadow-none form-select-sm"
                  id="status-select"
                  v-model="pageSize"
                  @change="getUsers"
                >
                  <option
                    v-for="option in pageSizeOptions"
                    :key="option"
                    :value="option"
                  >
                    {{ option }}
                  </option>
                </select>
              </div>
              <div>
                <input
                  v-model="searchQuery"
                  type="search"
                  class="form-control form-control-sm"
                  placeholder="Search..."
                />
              </div>
            </div>
          </div>
        </div>

        <TableComponent>
          <template v-slot:header>
            <th>#</th>
            <th><i class="fas fa-image"></i></th>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Status</th>
            <th>Action</th>
          </template>

          <template v-slot:body>
            <tr v-for="(user, index) in users" :key="user.id">
              <td>{{ startEntry + index }}</td>
              <td>
                <img class="rounded-circle avatar-sm" :src="user.photo" />
              </td>
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.created_at }}</td>
              <td>
                <div class="font-size-13" v-if="user.status">
                  <i
                    class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"
                  ></i
                  >Active
                </div>
                <div class="font-size-13" v-else>
                  <i
                    class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"
                  ></i
                  >Deactive
                </div>
              </td>
              <td>
                <div class="button-items">
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-secondary edit"
                    @click="openEditModal(user)"
                  >
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-secondary edit"
                    @click="openPasswordModal(user)"
                  >
                    <i class="fas fa-key"></i>
                  </button>
                  <button
                    type="button"
                    class="btn btn-danger btn-sm waves-effect waves-light"
                    @click="deleteUser(user.id)"
                  >
                    <i class="far fa-trash-alt"></i>
                  </button>
                </div>
              </td>
            </tr>
          </template>
        </TableComponent>
        <div class="d-flex justify-content-between align-items-center">
          <div class="dataTables_info">
            Showing {{ startEntry }} to {{ endEntry }} of
            {{ totalEntries }} entries
          </div>
          <PaginationComponent
            :currentPage="currentPage"
            :totalPages="totalPages"
            @page-changed="gotoPage"
          />
        </div>
      </div>
    </div>
    <!-- Use the modal component for update password-->
    <modal
      v-model:visible="showPasswordModal"
      :title="modalTitle"
      @close="resetPasswordFields"
      @confirm="updatePassword"
      ref="passwordModal"
    >
      <div class="mb-3">
        <label for="new-password" class="form-label">New Password</label>
        <input
          type="text"
          class="form-control"
          id="new-password"
          v-model="new_password"
        />
        <small v-if="errors.new_password" class="text-danger">{{
          errors.new_password[0]
        }}</small>
      </div>
      <div class="mb-3">
        <label for="c-password" class="form-label">Confirm Password</label>
        <input
          type="text"
          class="form-control"
          id="c-password"
          v-model="confirm_password"
        />
        <small v-if="errors.confirm_password" class="text-danger">{{
          errors.confirm_password[0]
        }}</small>
      </div>
    </modal>

    <!-- Use the modal component for update information-->
    <modal
      v-model:visible="showEditModal"
      :title="editModalTitle"
      @close="resetEditFields"
      @confirm="updateInformation"
      ref="editModal"
    >
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input
          type="text"
          class="form-control"
          id="name"
          v-model="editedUser.name"
        />
        <small v-if="errors.name" class="text-danger">{{
          errors.name[0]
        }}</small>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          type="text"
          class="form-control"
          id="email"
          v-model="editedUser.email"
        />
        <small v-if="errors.email" class="text-danger">{{
          errors.email[0]
        }}</small>
      </div>
      <div class="mb-3">
        <div class="form-check form-switch mb-3" dir="ltr">
          <input
            type="checkbox"
            class="form-check-input"
            id="is_status"
            @change="toggleStatus"
            :checked="editedUser.status == 1"
          />
          <label class="form-check-label" for="is_status">
            {{ editedUser.status === 1 ? "Active" : "Inactive" }}
          </label>
        </div>
      </div>
    </modal>
    <!-- Use the modal component for add admin-->
    <modal
      v-model:visible="showAddModal"
      :title="addModalTitle"
      @close="resetAddFields"
      @confirm="addAdmin"
      ref="addModal"
    >
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input
          type="text"
          class="form-control"
          id="name"
          v-model="addUser.name"
          required
        />
        <small v-if="errors.name" class="text-danger">{{
          errors.name[0]
        }}</small>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input
          type="text"
          class="form-control"
          id="email"
          v-model="addUser.email"
        />
        <small v-if="errors.email" class="text-danger">{{
          errors.email[0]
        }}</small>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          class="form-control"
          id="password"
          v-model="addUser.password"
        />
        <small v-if="errors.password" class="text-danger">{{
          errors.password[0]
        }}</small>
      </div>
      <div class="mb-3">
        <div class="form-check form-switch mb-3" dir="ltr">
          <input
            type="checkbox"
            class="form-check-input"
            id="is_status"
            @change="toggleStatus"
            :checked="addUser.status == 1"
          />
          <label class="form-check-label" for="is_status">
            {{ editedUser.status === 1 ? "Active" : "Inactive" }}
          </label>
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
import Loading from "@/admin/components/Loading.vue";
import PaginationComponent from "@/admin/components/PaginationComponent.vue";
import {
  showError,
  showSuccess,
  showConfirmation,
} from "@/admin/plugins/sweet-alert";
export default {
  components: {
    Loading,
    PaginationComponent,
  },
  data() {
    return {
      errors: {},
      users: [],
      totalEntries: 0,
      totalPages: 0,
      currentPage: 1,
      pageSize: 10,
      pageSizeOptions: [10, 20, 50, 100],
      searchQuery: "",
      loading: false,
      message: false,
      showPasswordModal: false,
      selectedUser: null,
      selectedUserName: "",
      new_password: "",
      confirm_password: "",
      showEditModal: false,
      editedUserName: "",
      editedUser: {
        id: null,
        name: "",
        email: "",
        status: "",
      },
      addModalTitle: "Add New Admin",
      showAddModal: false,
      addUser: {
        name: "",
        email: "",
        password: "",
        status: "",
      },
    };
  },
  mounted() {
    this.getUsers();
  },
  methods: {
    getUsers() {
      this.loading = true;
      const params = {
        page: this.currentPage,
        perPage: this.pageSize,
        search: this.searchQuery,
      };
      axios
        .get("/admin/users/list", { params })
        .then((response) => {
          if (response.data.status) {
            this.totalPages = response.data.data.total_page;
            this.totalEntries = response.data.data.total;
            this.users = response.data.data.users;
          }
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    deleteUser(userId) {
      const self = this;
      showConfirmation("Are you sure?", {
        confirmButtonText: "Yes, Delete it",
        cancelButtonText: `Cancel`,
        text: "You won't be able to revert this!",
      }).then(function (result) {
        if (result.isConfirmed) {
          self.deleteUserConfirmation(userId);
        }
      });
    },
    deleteUserConfirmation(userId) {
      this.loading = true;
      axios
        .delete(`/admin/users/${userId}`)
        .then((response) => {
          showSuccess(response.data.message).then(() => {
            this.getUsers();
          });
        })
        .catch((error) => {
          this.loading = false;
          showError(error.response.data.message);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    gotoPage(pageNumber) {
      this.currentPage = pageNumber;
      this.getUsers();
    },
    searchUsers() {
      this.currentPage = 1;
      this.getUsers();
    },

    openPasswordModal(user) {
      this.resetPasswordFields();
      this.selectedUser = user;
      this.selectedUserName = user.name;
      this.showPasswordModal = true;
    },
    updatePassword() {
      const userId = this.selectedUser.id;
      const new_password = this.new_password;
      const confirm_password = this.confirm_password;
      axios
        .post(`/admin/users/${userId}/password`, {
          new_password: new_password,
          confirm_password: confirm_password,
        })
        .then((response) => {
          this.errors = {};
          this.message = true;
          this.$store.dispatch("showMessage", {
            text: response.data.message,
            type: "success",
          });
          this.showPasswordModal = false;
        })
        .catch((error) => {
          if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
          } else {
            this.message = true;
            this.$store.dispatch("showMessage", {
              text: error.response.data.message,
              type: "error",
            });
            this.showPasswordModal = false;
          }
        })
        .finally(() => {
          this.loading = false;
          this.$refs.passwordModal.resetLoading();
        });
    },
    openEditModal(user) {
      this.resetEditFields();
      this.editedUserName = user;
      this.editedUser = { ...user };
      this.showEditModal = true;
    },
    updateInformation() {
      const userId = this.editedUser.id;
      const name = this.editedUser.name;
      const email = this.editedUser.email;
      const is_active = this.editedUser.status;
      axios
        .post(`/admin/users/${userId}/info`, {
          name: name,
          email: email,
          is_active: is_active,
        })
        .then((response) => {
          this.errors = {};
          this.message = true;
          this.$store.dispatch("showMessage", {
            text: response.data.message,
            type: "success",
          });
          this.showEditModal = false;
          this.getUsers();
        })
        .catch((error) => {
          if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
          } else {
            this.message = true;
            this.$store.dispatch("showMessage", {
              text: error.response.data.message,
              type: "error",
            });
            this.showEditModal = false;
          }
        })
        .finally(() => {
          this.loading = false;
          this.$refs.editModal.resetLoading();
        });
    },
    openAddModal() {
      this.resetAddFields();
      this.showAddModal = true;
    },
    addAdmin() {
      const name = this.addUser.name;
      const email = this.addUser.email;
      const password = this.addUser.password;
      const is_active = this.addUser.status;
      axios
        .post(`/admin/users/create`, {
          name: name,
          email: email,
          password: password,
          is_active: is_active,
        })
        .then((response) => {
          this.errors = {};
          this.message = true;
          this.$store.dispatch("showMessage", {
            text: response.data.message,
            type: "success",
          });
          this.showAddModal = false;
          this.getUsers();
        })
        .catch((error) => {
          if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
          } else {
            this.message = true;
            this.$store.dispatch("showMessage", {
              text: error.response.data.message,
              type: "error",
            });
            this.showAddModal = false;
          }
        })
        .finally(() => {
          this.loading = false;
          this.$refs.addModal.resetLoading();
        });
    },
    resetAddFields() {
      this.errors = {};
      this.addUser.name = "";
      this.addUser.email = "";
      this.addUser.password = "";
      this.addUser.status = false;
    },
    resetEditFields() {
      this.errors = {};
    },
    resetPasswordFields() {
      this.new_password = "";
      this.confirm_password = "";
      this.errors = {};
    },
    toggleStatus(event) {
      this.editedUser.status = event.target.checked ? 1 : 0;
    },
  },
  computed: {
    modalTitle() {
      return this.selectedUser
        ? `Change Password for ${this.selectedUser.name}`
        : "Change Password";
    },
    editModalTitle() {
      return this.editedUserName
        ? `Update Information for ${this.editedUserName.name}`
        : "Update Information";
    },
    startEntry() {
      return (this.currentPage - 1) * this.pageSize + 1;
    },
    endEntry() {
      return Math.min(this.currentPage * this.pageSize, this.totalEntries);
    },
  },
  watch: {
    searchQuery() {
      clearTimeout(this.debounce);
      this.debounce = setTimeout(() => {
        this.searchUsers();
      }, 600);
    },
  },
};
</script>
