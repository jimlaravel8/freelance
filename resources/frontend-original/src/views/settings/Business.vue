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
          <v-tab class="no-caps" :to="{ name: 'settings.business' }" :ripple="false">
            {{ $t('business') }}
            <v-icon size="24">mdi-briefcase</v-icon>
          </v-tab>
          <v-tab class="no-caps" :to="{ name: 'settings.business.subscription' }" :ripple="false">
            {{ $t('subscription') }}
            <v-icon size="24">mdi-domain</v-icon>
          </v-tab>
        </v-tabs>
        <v-divider/>
        <v-tabs 
          v-model="tab"
          :color="($store.state.app.dark) ? 'white' : 'black'"
          slider-size="2"
          class="mt-5 px-5 content-tabs"
          show-arrows
          active-class="font-weight-bold"
        >
          <v-tab class="no-caps" :href="'#general'" :ripple="false">
            {{ $t('general') }}
          </v-tab>
          <v-tab class="no-caps" :href="'#localization'" :ripple="false">
            {{ $t('localization') }}
          </v-tab>
        </v-tabs>
        <v-divider/>
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
                <v-alert
                  :value="form1.hasError && !form1.success"
                  type="error"
                  class="mb-8"
                >
                  {{ form1.error }}
                </v-alert>
                <v-alert
                  :value="form1.success"
                  type="success"
                  class="mb-4"
                >
                  {{ $t('update_success') }}
                </v-alert>
                <v-tabs-items v-model="tab" :touchless="false">
                  <v-tab-item :transition="false" :reverse-transition="false"  value="general">
                    <v-container class="pa-0">
                      <v-row no-gutters>
                        <v-col
                          cols="12"
                          sm="9"
                          md="6"
                        >
                          <v-row>
                            <v-col cols="12" sm="6" class="pt-0 pb-4">
                              <div class="ml-3 mb-2 caption" v-html="$t('cover') + ' <small class=\'ml-2\'>(1280 x 800px)</small>'"/>
                              <v-hover>
                                <template v-slot:default="{ hover }">
                                  <v-img
                                    max-height="160"
                                    max-width="240"
                                    class="grey lighten-3 elevation-1 mb-5"
                                    :src="(form1.cover_media_url == null) ? 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=' : form1.cover_media_url"
                                  >
                                    <v-fade-transition>
                                      <v-overlay
                                        v-if="hover"
                                        absolute
                                      >
                                        <v-btn-toggle rounded>
                                          <v-btn @click="pickFile('cover')" x-small color="primary" rounded><v-icon class="mr-1" size="14">mdi-upload</v-icon> {{ $t('upload') }}</v-btn>
                                          <v-btn v-if="showDeleteCover" @click="form1.cover_media_url = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAIAAACQd1PeAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAA9JREFUeNpiuHbtGkCAAQAFCAKDZcGh3gAAAABJRU5ErkJggg=='; form1.cover_media_changed = true; showDeleteCover = false" x-small color="red" rounded><v-icon size="14">mdi-close</v-icon></v-btn>
                                        </v-btn-toggle>
                                      </v-overlay>
                                    </v-fade-transition>
                                  </v-img>
                                </template>
                              </v-hover>
                              <input
                                type="file"
                                style="display: none"
                                id="cover"
                                name="cover"
                                accept="image/*"
                                @change="onFilePicked"
                              >
                            </v-col>
                          </v-row>
                          <x-text-field 
                            v-model="form1.name"
                            ref="form1.name"
                            id="form1.name"
                            :label="$t('name')"
                            :name="$t('name')"
                            rules="required|min:1|max:96"
                            dense
                          />
                          <x-text-field 
                            v-model="form1.short_description"
                            ref="form1.short_description"
                            id="form1.short_description"
                            :label="$t('short_description')"
                            :name="$t('description')"
                            rules="nullable|max:100"
                            dense
                          />
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-tab-item>
                  <v-tab-item :transition="false" :reverse-transition="false"  value="localization">
                    <v-container class="pa-0">
                      <v-row no-gutters>
                        <v-col
                          cols="12"
                          sm="9"
                          md="6"
                        >
                          <x-autocomplete
                            v-model="form1.currency"
                            ref="form1.currency"
                            id="form1.currency"
                            :items="currencies"
                            item-value="0" 
                            item-text="1"
                            :label="$t('currency')"
                            :name="$t('currency')"
                            rules="required"
                            dense
                            :hint="$t('business_currency_hint')"
                            persistent-hint
                          />
                        </v-col>
                      </v-row>
                    </v-container>
                  </v-tab-item>
                </v-tabs-items>
              </v-card-text>
              <v-card-actions class="mx-2">
                <v-btn color="ctaBg ctaFg--text" tile depressed x-large :loading="form1.loading" :disabled="form1.loading || invalid" type="submit" class="no-caps mb-2" style="min-width: 200px">{{ $t('update') }} <v-icon right>mdi-arrow-right</v-icon></v-btn>
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
        tab: 'general',
        activeFilePickerId: null,
        showDeleteCover: false,
        currencies: [],
        form1: {
          loading: false,
          currency: '',
          name: '',
          short_description: '',
          cover_media_url: null,
          cover_media_changed: false,
          hasError: false,
          error: null,
          success: false
        }
      }
    },
    created () {
      this.axios
        .get('/localization/currencies', { params: { locale: this.$i18n.locale }})
        .then(response => {
          this.currencies = this.$_.toPairs(response.data)

          this.axios
            .get('/business/settings', { params: { locale: this.$i18n.locale }})
            .then(response => {
              let business = response.data.business
              this.form1.cover_media_url = business.cover
              this.showDeleteCover = (this.form1.cover_media_url == null) ? false : true
              this.form1.currency = business.currency
              this.form1.name = business.name
              this.form1.short_description = business.short_description
            })
        })
    },
    methods: {
      async submitForm(formName) {
        // Reset form validation
        this.$refs[formName].reset()

        this[formName].success = false
        this[formName].hasError = false
        this[formName].loading = true

        this.updateProfile(formName)
      },
      updateProfile(formName) {
        var app = this[formName]

        let settings = { headers: { 'content-type': 'multipart/form-data' } }

        // Remove image urls
        let formModel = Object.assign({}, this.form1)
        formModel.cover_media_url = null
        formModel.language = this.$i18n.locale

        let formData = new FormData(document.getElementById('form1'))

        for (let field in formModel) {
          let val = (formModel[field] === null) ? '' : formModel[field]
          formData.append(field, val)
        }

        this.axios
          .post('/business/settings', formData, settings)
          .then(response => {
            if (response.data.status === 'success') {
              app.success = true
              this.$nextTick(() => this.$refs[formName].reset())
            }
          })
          .catch(error => {
            app.hasError = true
            if (error.response.data.status === 'error') {
              if (typeof error.response.data.error !== 'undefined') app.error = error.response.data.error
              this.errorMsg = error.response.data.error

              let errors = error.response.data.errors || []

              for (let field in errors) {
                this.$refs[formName + '.' + field].applyResult({
                  errors: errors[field],
                  valid: false,
                  failedRules: {}
                })
              }
            }
          })
          .finally(() => { 
            window.scrollTo(0,0)
            app.loading = false
          })
      },
      pickFile (id) {
        this.activeFilePickerId = id
        document.getElementById(id).click();
      },
      onFilePicked (e) {
        const files = e.target.files
        if(files[0] !== undefined) {
          if(files[0].name.lastIndexOf('.') <= 0) {
            return
          }
          const fr = new FileReader ()
          fr.readAsDataURL(files[0])
          fr.addEventListener('load', () => {
            this.form1[this.activeFilePickerId + '_media_url'] = fr.result
            this.form1[this.activeFilePickerId + '_media_file'] = files[0]
            this.form1[this.activeFilePickerId + '_media_changed'] = true

            switch (this.activeFilePickerId) {
              case 'cover':
                this.showDeleteCover = true
                break;
            }
          })
        } else {
          this.form1[this.activeFilePickerId + '_media_file'] = ''
          this.form1[this.activeFilePickerId + '_media_url'] = ''
          this.form1[this.activeFilePickerId + '_media_changed'] = true
        }
      }
    },
    computed: {
      app () {
        return this.$store.getters.app
      }
    }
  }
</script>