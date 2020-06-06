<template>
  <div class="viewContainer">
    <v-container class="inner-container">
      <v-card class="mt-md-12 mt-0 content-tabs-wrapper" tile elevation="0">
        <v-tabs
          :color="($store.state.app.dark) ? 'white' : 'black'"
          class="px-5 content-tabs"
          icons-and-text
          height="100"
          slider-size="4"
          show-arrows
        >
          <v-tab class="no-caps" :to="{ name: 'settings.profile' }" :ripple="false">
            {{ $t('profile') }}
            <v-icon size="24">mdi-account-circle</v-icon>
          </v-tab>
          <v-tab
            class="no-caps"
            :to="{ name: 'settings.business' }"
            :ripple="false"
            v-if="$auth.user().role === 3"
          >
            {{ $t('business') }}
            <v-icon size="24">mdi-briefcase</v-icon>
          </v-tab>
          <v-tab
            class="no-caps"
            :to="{ name: 'settings.business.subscription' }"
            :ripple="false"
            v-if="$auth.user().role === 3"
          >
            Billing
            <v-icon size="24">mdi-domain</v-icon>
          </v-tab>
        </v-tabs>
        <v-divider />
        <v-tabs
          v-model="tab"
          :color="($store.state.app.dark) ? 'white' : 'black'"
          slider-size="2"
          class="mt-5 px-5 content-tabs"
          show-arrows
          active-class="font-weight-bold"
        >
          <v-tab class="no-caps" :href="'#general'" :ripple="false">{{ $t('general') }}</v-tab>
          <v-tab class="no-caps" :href="'#localization'" :ripple="false">{{ $t('localization') }}</v-tab>
          <v-tab class="no-caps" :href="'#password'" :ripple="false">{{ $t('password') }}</v-tab>
        </v-tabs>
        <v-divider />
        <v-card flat tile class="content-tabs-card pa-5 mb-0 mb-md-12">
          <ValidationObserver ref="form1" v-slot="{ invalid }">
            <v-form
              :model="form1"
              id="form1"
              lazy-validation
              @submit.prevent="submitForm('form1')"
              autocomplete="off"
              method="post"
              accept-charset="UTF-8"
              enctype="multipart/form-data"
            >
              <v-card-text>
                <v-alert :value="form1.has_error && !form1.success" type="error" class="mb-8">
                  <span
                    v-if="form1.error === 'registration_validation_error'"
                  >{{ $t('server_error') }}</span>
                  <span
                    v-else-if="form1.error === 'demo'"
                  >This is a demo user. You can't update or delete anything this account. If you want to test all user features, sign up with a new account.</span>
                  <span v-else>{{ $t('correct_errors') }}</span>
                </v-alert>
                <v-alert
                  :value="form1.success"
                  type="success"
                  class="mb-4"
                >{{ $t('update_success') }}</v-alert>
                <v-tabs-items v-model="tab" :touchless="false">
                  <v-tab-item :transition="false" :reverse-transition="false" :value="'general'">
                    <v-container class="pa-0">
                      <v-row no-gutters>
                        <v-col cols="12" sm="9" md="6">
                          <!-- <v-row>
                            <v-col cols="12" sm="6" class="pt-0 pb-8">
                              <input
                                type="file"
                                style="display: none"
                                id="avatar"
                                name="avatar"
                                accept="image/*"
                                @change="onFilePicked"
                              />
                            </v-col>
                          </v-row>-->
                          <v-row>
                            <v-col cols="12" sm="6" class="py-0">
                              <x-text-field
                                v-model="form1.first_name"
                                ref="form1.first_name"
                                id="form1.first_name"
                                :label="$t('first_name')"
                                :name="$t('first_name')"
                                rules="required|min:1|max:32"
                                dense
                              />
                            </v-col>
                            <v-col cols="12" sm="6" class="py-0">
                              <x-text-field
                                v-model="form1.last_name"
                                ref="form1.last_name"
                                id="form1.last_name"
                                :label="$t('last_name')"
                                :name="$t('last_name')"
                                rules="required|min:1|max:32"
                                dense
                              />
                            </v-col>
                          </v-row>
                          <x-text-field
                            v-model="form1.whatsapp"
                            ref="form1.whatsapp"
                            id="form1.whatsapp"
                            :label="$auth.user().role === 2 ? 'Whatsapp' : 'Contact Number'"
                            rules="required|max:64"
                            dense
                          />
                          <x-text-field
                            type="email"
                            v-model="form1.email"
                            ref="form1.email"
                            id="form1.email"
                            :label="$t('email')"
                            rules="required|max:64|email"
                            dense
                          />
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-tab-item>
                  <v-tab-item
                    :transition="false"
                    :reverse-transition="false"
                    :value="'localization'"
                  >
                    <v-container class="pa-0">
                      <v-row no-gutters>
                        <v-col cols="12" sm="9" md="6">
                          <x-autocomplete
                            v-model="form1.locale"
                            :items="locales"
                            item-value="0"
                            item-text="1"
                            label="Language"
                            :name="$t('locale')"
                            rules="required"
                            :hint="$t('locale_hint')"
                            :persistent-hint="true"
                            dense
                          />
                          <x-autocomplete
                            v-model="form1.location"
                            :items="locations"
                            item-value="0"
                            item-text="1"
                            label="Location"
                            name="location"
                            rules="required"
                            :persistent-hint="false"
                            dense
                          />
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-tab-item>
                  <v-tab-item :transition="false" :reverse-transition="false" :value="'password'">
                    <v-container class="pa-0">
                      <v-row no-gutters>
                        <v-col cols="12" sm="9" md="6">
                          <x-password
                            v-model="form1.new_password"
                            ref="form1.new_password"
                            id="form1.new_password"
                            :label="$t('change_password')"
                            :name="$t('password')"
                            rules="min:8|max:24"
                            :hint="$t('leave_empty_for_no_change')"
                            :persistent-hint="true"
                            dense
                          />
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-tab-item>
                </v-tabs-items>
                <v-container class="pa-0">
                  <v-row no-gutters>
                    <v-col cols="12" md="8">
                      <x-password
                        v-model="form1.current_password"
                        ref="form1.current_password"
                        id="form1.current_password"
                        :label="$t('current_password')"
                        :name="$t('current_password')"
                        rules="required|min:8|max:24"
                        :hint="$t('current_password_info')"
                        persistent-hint
                        dense
                      />
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>
              <v-card-actions class="mx-2">
                <v-btn
                  color="ctaBg ctaFg--text"
                  tile
                  depressed
                  x-large
                  :loading="form1.loading"
                  :disabled="form1.loading || invalid"
                  type="submit"
                  class="no-caps mb-2"
                  style="min-width: 200px"
                >
                  {{ $t('update') }}
                  <v-icon right>mdi-arrow-right</v-icon>
                </v-btn>
              </v-card-actions>
            </v-form>
          </ValidationObserver>
        </v-card>
      </v-card>
    </v-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tab: "general",
      activeFilePickerId: null,
      showDeleteAvatar: false,
      showDeleteCover: false,
      locales: [],
      locations: [],
      formDirty: false,
      form1: {
        loading: false,
        first_name: this.$auth.user().first_name,
        last_name: this.$auth.user().last_name,
        email: this.$auth.user().email,
        whatsapp: this.$auth.user().whatsapp,
        avatar_media_url: this.$auth.user().avatar,
        avatar_media_changed: false,
        locale: this.$auth.user().locale,
        // timezone: this.$auth.user().timezone,
        location: this.$auth.user().location,
        new_password: null,
        current_password: null,
        has_error: false,
        error: null,
        success: false
      }
    };
  },
  mounted() {
    this.axios
      .get("/localization/locales", { params: { locale: this.$i18n.locale } })
      .then(response => {
        // this.locales = this.$_.toPairs(response.data);
        this.locales = this.$_.toPairs({
          en: "English",
          en_US: "English (United States)",
          en_GB: "English (United Kingdom)"
        });
      });
    // this.axios
    //   .get("/localization/timezones", { params: { locale: this.$i18n.locale } })
    //   .then(response => {
    //     this.timezones = this.$_.toPairs(response.data);
    //   });
    this.locations = this.$_.toPairs({
      Anguilla: "Anguilla",
      "Antigua and Barbuda": "Antigua and Barbuda",
      Dominica: "Dominica",
      Grenada: "Grenada",
      Montserrat: "Montserrat",
      "St. Kitts and Nevis": "St. Kitts and Nevis",
      "St. Lucia": "St. Lucia",
      "St. Vincent": "St. Vincent"
    });
    console.log(this.$auth.user());
  },
  created() {
    this.showDeleteAvatar = this.$_.startsWith(
      this.form1.avatar_media_url,
      "data:image/png;base64"
    )
      ? false
      : true;
  },
  watch: {
    form1: {
      handler(newVal, oldVal) {
        if (!this.formDirty) {
          // Validate current password
          this.$refs["form1.current_password"].validate();
          this.formDirty = true;
        }
      },
      deep: true
    }
  },
  methods: {
    async submitForm(formName) {
      // Reset form validation
      this.$refs[formName].reset();

      this[formName].success = false;
      this[formName].has_error = false;
      this[formName].loading = true;

      this.updateProfile(formName);
    },
    updateProfile(formName) {
      var app = this[formName];

      let settings = { headers: { "content-type": "multipart/form-data" } };

      // Remove image urls
      let formModel = Object.assign({}, this.form1);
      formModel.avatar_media_url = null;
      formModel.language = this.$i18n.locale;

      let formData = new FormData(document.getElementById("form1"));

      for (let field in formModel) {
        let val = formModel[field] === null ? "" : formModel[field];
        formData.append(field, val);
      }

      this.axios
        .post("/auth/profile", formData, settings)
        .then(response => {
          if (response.data.status === "success") {
            app.success = true;
            app.new_password = null;
            app.current_password = null;
            this.$nextTick(() => this.$refs[formName].reset());

            // Update auth object
            this.$auth.user(response.data.user);
          }
        })
        .catch(error => {
          app.has_error = true;
          if (error.response.data.status === "error") {
            if (typeof error.response.data.error !== "undefined")
              app.error = error.response.data.error;
            this.errorMsg = error.response.data.error;

            let errors = error.response.data.errors || [];

            for (let field in errors) {
              this.$refs[formName + "." + field].applyResult({
                errors: errors[field],
                valid: false,
                failedRules: {}
              });
            }
          }
        })
        .finally(() => {
          window.scrollTo(0, 0);
          app.loading = false;
        });
    },
    pickFile(id) {
      this.activeFilePickerId = id;
      document.getElementById(id).click();
    },
    onFilePicked(e) {
      const files = e.target.files;
      if (files[0] !== undefined) {
        if (files[0].name.lastIndexOf(".") <= 0) {
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(files[0]);
        fr.addEventListener("load", () => {
          this.form1[this.activeFilePickerId + "_media_url"] = fr.result;
          this.form1[this.activeFilePickerId + "_media_file"] = files[0];
          this.form1[this.activeFilePickerId + "_media_changed"] = true;

          switch (this.activeFilePickerId) {
            case "avatar":
              this.showDeleteAvatar = true;
              break;
          }
        });
      } else {
        this.form1[this.activeFilePickerId + "_media_file"] = "";
        this.form1[this.activeFilePickerId + "_media_url"] = "";
        this.form1[this.activeFilePickerId + "_media_changed"] = true;
      }
    }
  },
  computed: {
    app() {
      return this.$store.getters.app;
    }
  }
};
</script>