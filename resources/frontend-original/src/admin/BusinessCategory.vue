<template>
  <div class="viewContainer">
    <v-container class="content-container py-1">
      <v-row>
        <v-col cols="12" sm="12" xl="6">
          <v-card>
            <v-card-text>
              <h2 class="title">
                Edit an existing
                <strong>category</strong>
              </h2>
            </v-card-text>
            <v-form ref="updateForm" v-model="formUpdate.valid" class="px-2">
              <v-autocomplete
                :items="categories"
                item-value="id"
                item-text="name"
                label="Select a Category"
                :loading="loading"
                clearable
                solo
                v-model="idOfItemToUpdate"
                @change="handleChange()"
              ></v-autocomplete>
              <v-text-field
                v-model="formUpdate.name"
                :counter="250"
                :rules="rules"
                label="Name"
                required
                outlined
                :disabled="disabled"
              ></v-text-field>
              <v-btn outlined large color="success" :disabled="disabled" @click="updateCategory()">
                <v-icon left>mdi-checkbox-marked-outline</v-icon>Save
              </v-btn>
              <v-btn
                class="ml-2"
                outlined
                large
                color="red"
                :disabled="disabled"
                @click="deleteCategory()"
              >
                <v-icon left>mdi-delete</v-icon>Delete
              </v-btn>
            </v-form>
            <v-card-subtitle>
              <small>Select the category within `Select Category` to update/delete it.</small>
            </v-card-subtitle>
          </v-card>
        </v-col>
        <v-col cols="12" sm="12" xl="6">
          <v-card>
            <v-card-text>
              <h2 class="title">
                Create a new
                <strong>category</strong>
              </h2>
            </v-card-text>
            <v-form ref="createForm" v-model="formCreate.valid" class="px-2">
              <v-text-field
                v-model="formCreate.name"
                :counter="250"
                :rules="rules"
                label="Name"
                required
                outlined
              ></v-text-field>
              <v-btn outlined large color="purple" @click="createCategory()">
                <v-icon left>mdi-checkbox-marked-outline</v-icon>Save
              </v-btn>
            </v-form>
            <v-card-subtitle>
              <small>Here you can create a new Category for businesses.</small>
            </v-card-subtitle>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
export default {
  data: () => ({
    idOfItemToUpdate: "",
    disabled: true,
    loading: true,
    formUpdate: {
      valid: true,
      name: "",
      short_description: ""
    },
    formCreate: {
      valid: true,
      name: "",
      short_description: ""
    },
    rules: {
      name: [
        v => !!v || "Name is required",
        v => (v && v.length <= 250) || "Name must be less than 250 characters"
      ],
      email: [
        v => !!v || "E-mail is required",
        v => /.+@.+\..+/.test(v) || "E-mail must be valid"
      ]
    },
    categories: [],
    loadedCategory: []
  }),
  created() {
    this.loadCategories();
    this.loading = false;
  },
  methods: {
    async loadCategories() {
      const body = await this.axios(`/admin/category/get`);
      this.categories = body.data;
    },
    async handleChange() {
      const body = await this.axios(
        `/admin/category/get/${this.idOfItemToUpdate.toString()}`
      );
      const data = body.data;
      const { name, short_description } = data[0];
      this.formUpdate.name = name;
      this.formUpdate.short_description = short_description;

      this.disabled = false;
    },
    async updateCategory() {
      const body = await this.axios.post(
        `/admin/category/update/${this.idOfItemToUpdate}`,
        {
          name: this.formUpdate.name
        }
      );
      const response = body.data;
      this.$root.$snackbar(response.message);

      this.loading = true;
      this.loadCategories();
      this.loading = false;
    },
    async deleteCategory() {
      const body = await this.axios.post(
        `/admin/category/delete/${this.idOfItemToUpdate}`
      );
      const response = body.data;
      this.$root.$snackbar(response.message);

      this.loading = true;
      this.loadCategories();
      this.loading = false;

      this.idOfItemToUpdate = "";
      this.disabled = true;
    },
    async createCategory() {
      const { name, short_description } = this.formCreate;
      const body = await this.axios.post(`/admin/category/create`, {
        name: name
      });
      // Show response
      const response = body.data;
      this.$root.$snackbar(response.message);
      // Update the list.
      this.loading = true;
      this.loadCategories();
      this.loading = false;
      // Clear form.
      this.formCreate.name = "";
      this.formCreate.short_description = "";
      this.idOfItemToUpdate = "";
    }
  }
};
</script>
