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
                {{ $t('forgot_password') }}
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
                            <h1 class="fg--text display-2 font-weight-medium mb-4" style="line-height: 4rem">{{ $t('reset_password_title') }}</h1>
                            <v-alert
                              :value="successReset"
                              type="success"
                              tile
                              class="mb-4"
                            >
                              {{ $t('reset_email_sent') }}
                            </v-alert>
                            <v-alert
                              :value="form1.hasError"
                              type="error"
                              tile
                              class="mb-3"
                            >
                              {{ errorMsg }}
                            </v-alert>
                            <p class="body-1">{{ $t('reset_password_info') }}</p>
                            <x-text-field 
                              type="email"
                              v-model="form1.email"
                              ref="form1.email"
                              id="form1.email"
                              :label="$t('email')"
                              :name="$t('email')"
                              rules="required|email"
                              dense
                            />
                            <v-btn color="ctaBg ctaFg--text" class="no-caps" block x-large depressed tile :loading="form1.loading" :disabled="form1.loading || invalid" type="submit">{{ $t('send_reset_link') }} <v-icon right>mdi-arrow-right</v-icon></v-btn>
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
    successReset: false,
    topBgImgs: [],
    errorMsg: '',
    form1: {
      loading: false,
      email: null,
      hasError: false,
    }
  }),
  created () {
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
        .post('/auth/password/reset', {
          locale: this.$i18n.locale,
          email: form.email
        })
        .then(response => {
          if (response.data.status === 'success') {
            this.successReset = true
            this.form1.email = ''
            this.$refs[formName].reset()
          } else if (typeof response.data.error !== 'undefined') {
            form.hasError = true
            this.errorMsg = response.data.error
          }
        })
        .catch(() => {
          form.hasError = true
        })
        .finally(() => form.loading = false)

    }
  },
  watch: {
    tab: function (val) {
      if (window.innerWidth > 760) {
        this.topBgImg = this.topBgImgs[0]
        this.topBgImgHeight = this.topBgImgDefaultHeight
        this.topBgImgAspectRation = 1.7778
      }
    }
  }
};
</script>
