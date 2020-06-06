<template>
  <div class="viewContainer" v-resize="onResize">
    <v-container class="pa-0">
      <v-img
        :src="topBgImg"
        :key="topBgImg"
        :min-height="topBgImgHeight"
        :aspect-ratio="topBgImgAspectRation"
        transition="fade-transition"
      >
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
              <v-tabs-slider></v-tabs-slider>

              <v-tab :to="{ name: 'login' }" class="no-caps" :ripple="false">
                {{ $t('log_in') }}
                <v-icon size="24">mdi-account</v-icon>
              </v-tab>

              <v-tab :to="{ name: 'password.email' }" class="no-caps" :ripple="false">
                {{ $t('new_password') }}
                <v-icon size="24">mdi-key-variant</v-icon>
              </v-tab>

            </v-tabs>
            <v-divider/>
          </v-card>
          <v-tabs-items v-model="tab" style="background-color: transparent;">
            <v-tab-item value="tab-0">
              <v-container class="pa-0">
                <v-row no-gutters>
                  <v-col
                    cols="12"
                    sm="9"
                    md="6"
                  >
                    <ValidationObserver ref="form1" v-slot="{ invalid }">
                      <v-form 
                        :model="form1" 
                        @submit.prevent="submit('form1')"
                        autocomplete="off"
                        method="post"
                      >
                        <v-card flat tile class="content-tabs-card pa-5 mb-0 mb-md-12">
                          <v-card-text>
                            <h1 class="fg--text display-2 font-weight-medium mb-4" style="line-height: 4rem">{{ $t('set_a_new_password') }}</h1>
                            <v-alert
                              :value="invalidToken"
                              type="error"
                              tile
                              class="mt-8 mb-1"
                            >
                              {{ $t('invalid_token') }}
                            </v-alert>
                            <el-alert
                              type="error"
                              v-if="form1.hasError && !form1.success"
                              class="mb-3"
                              tile
                              show-icon
                            >
                              {{ $t('correct_errors') }}
                            </el-alert>
                            <x-password
                              v-if="!invalidToken"
                              v-model="form1.password"
                              ref="form1.password"
                              id="form1.password"
                              :label="$t('enter_new_password')"
                              :name="$t('password')"
                              rules="required|min:8|max:24"
                              dense
                            />
                            <v-btn v-if="!invalidToken" color="ctaBg ctaFg--text" class="no-caps" block x-large depressed tile :loading="form1.loading" :disabled="form1.loading || invalid" type="submit">{{ $t('update_password') }} <v-icon right>mdi-arrow-right</v-icon></v-btn>
                          </v-card-text>
                        </v-card>
                      </v-form>
                    </ValidationObserver>
                  </v-col>
                </v-row>
              </v-container>
            </v-tab-item>
          </v-tabs-items>
        </v-container>
      </v-img>
    </v-container>
  </div>
</template>
<script>
export default {
  data: () => ({
    tab: 'tab-0',
    topBgImg: 'about:blank',
    topBgImgHeight: null,
    topBgImgDefaultHeight: 780,
    topBgImgAspectRation: 1.7778,
    topBgImgs: [],
    invalidToken: false,
    form1: {
      loading: false,
      password: '',
      hasError: false,
      errors: {},
      success: false
    }
  }),
  created() {
    // Tab images
    this.topBgImgs = [
      this.$init.config.schemeAndHost + '/img/reset-password-tab.jpg'
    ]

    // Set image depending on the size of the browser window
    if (window.innerWidth > 760) {
      this.topBgImg = this.topBgImgs[0]
      this.topBgImgHeight = this.topBgImgDefaultHeight
      this.topBgImgAspectRation = 1.7778
    } else {
      this.topBgImgHeight = null
      this.topBgImgAspectRation = 3
    }

    // Verify token
    let token = this.$route.params.token
    this.axios
      .post('/auth/password/reset/validate-token', {
        locale: this.$i18n.locale,
        token: token
      })
      .then(response => {
        if (response.data.status === 'success') {
          this.invalidToken = false
        } else {
          this.invalidToken = true
        }
      })
      .catch(() => {
        this.invalidToken = true
      })
  },
  methods: {
    onResize () {
      if (window.innerWidth <= 760) {
        this.topBgImg = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='
        this.topBgImgHeight = null
        this.topBgImgAspectRation = 3
      } else if (this.topBgImg == 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=') {
        this.topBgImg = this.topBgImgs[0]
        this.topBgImgHeight = this.topBgImgDefaultHeight
        this.topBgImgAspectRation = 1.7778
      }
    },
    async submit (formName) {
      // Reset form validation
      this.$refs[formName].reset()

      // Form defaults
      let form = this[formName]
      form.hasError = false
      form.loading = true

      this.axios
        .post('/auth/password/update', {
          locale: this.$i18n.locale,
          password: form.password,
          token: this.$route.params.token
        })
        .then(response => {
          if (response.data.status === 'success') {
            this.$router.push({name: 'login', params: {successResetUpdateRedirect: true}})
          }
        })
        .catch(error => {
          form.hasError = true
          form.errors = error.response.data.errors || {}
        })
        .finally(() => form.loading = false)
    }
  },
  watch: {
    tab: function (val) {
      if (window.innerWidth > 960) {
        this.topBgImg = this.topBgImgs[0]
        this.topBgImgHeight = this.topBgImgDefaultHeight
        this.topBgImgAspectRation = 1.7778
      }
    }
  }
};
</script>
