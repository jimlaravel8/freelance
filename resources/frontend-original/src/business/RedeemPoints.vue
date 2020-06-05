<template>
  <div class="viewContainer" v-resize="onResize">
    <v-container class="pa-0">
      <v-img
        :src="tabImg"
        :key="tabImg"
        :min-height="tabImgHeight"
        :aspect-ratio="tabImgAspectRation"
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
              <v-tab :to="{ name: 'business.issue-points' }" class="no-caps" :ripple="false">
                {{ $t('issue') }}
                <v-icon size="24">mdi-coins</v-icon>
              </v-tab>
              <v-tab :to="{ name: 'business.redeem-points' }" class="no-caps" :ripple="false">
                {{ $t('redeem') }}
                <v-icon size="24">mdi-wallet-giftcard</v-icon>
              </v-tab>
              <v-tab :to="{ name: 'business.transactions' }" class="no-caps" :ripple="false">
                {{ $t('transactions') }}
                <v-icon size="24">mdi-format-list-bulleted</v-icon>
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
                    <v-card flat tile class="content-tabs-card pa-5 mb-0 mb-md-12">
                      <v-card-text v-if="$auth.user().role === 4 && !$auth.user().premium">
                        <p class="fg--text subtitle-1 font-weight-medium">
                          {{ $t('account_inactive') }}
                        </p>
                      </v-card-text>
                      <v-card-text v-else>
                        <v-alert
                          :value="verifyCodeForm.success"
                          type="success"
                          class="mb-5"
                        >
                          <i18n path="redeem_points_success">
                            <template #points>
                              {{ msg.points }}
                            </template>
                            <template #value>
                              {{ msg.value }}
                            </template>
                            <template #customerNumber>
                              {{ msg.customerNumber }}
                            </template>
                            <template #transactions>
                              <router-link class="white--text" :to="{ name: 'business.transactions' }">{{ $t('transactions').toLowerCase() }}</router-link>
                            </template>
                          </i18n>
                        </v-alert>
                        <p class="fg--text subtitle-1">{{ $t('redeem_points_head') }}</p>
                        <ValidationObserver ref="verifyCodeForm" v-slot="{ invalid }">
                          <v-form 
                            id="verifyCodeForm"
                            lazy-validation
                            @submit.prevent="verifyCode()"
                            autocomplete="off"
                            method="post"
                            accept-charset="UTF-8"
                          >
                            <v-row no-gutters>
                              <v-col cols="8">
                                <x-text-field
                                  inputmode="numeric"
                                  :name="$t('code')"
                                  v-model="verifyCodeForm.code"
                                  id="verifyCodeForm.code"
                                  ref="verifyCodeForm.code"
                                  rules="required|min:4|max:4"
                                  class="title"
                                  outlined
                                  v-mask="'####'"
                                  placeholder="####"
                                />
                              </v-col>
                              <v-col cols="4">
                                <v-btn
                                  type="submit"
                                  depressed 
                                  tile
                                  block
                                  color="ctaBg ctaFg--text"
                                  class="title ma-0 no-caps"
                                  height="56px"
                                  :loading="verifyCodeForm.loading" :disabled="verifyCodeForm.loading || invalid"
                                >
                                  {{ $t('verify') }} <v-icon right>mdi-arrow-right</v-icon>
                                </v-btn>
                              </v-col>
                            </v-row>
                          </v-form>
                        </ValidationObserver>
  
                        <p class="mt-0 mb-0 fg--text subtitle-1">{{ $t('redeem_points_footer') }}</p>
                      </v-card-text>
                    </v-card>
                  </v-col>
                </v-row>
              </v-container>
            </v-tab-item>
          </v-tabs-items>
        </v-container>
      </v-img>
    </v-container>

    <v-dialog v-model="verifyCodeDialog" persistent max-width="360">
      <v-card>
        <v-card-title class="headline">{{ $t('redeem_points') }}</v-card-title>
        <v-card-text>
          <p class="body-1">
            <i18n path="redeem_points_verify">
              <template #points>
                {{ msg.points }}
              </template>
              <template #value>
                {{ msg.value }}
              </template>
              <template #customerNumber>
                {{ msg.customerNumber }}
              </template>
            </i18n>
          </p>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="secondary" class="no-caps" text @click="verifyCodeDialog = false">{{ $t('cancel') }}</v-btn>
          <v-btn color="NavBg NavFg--text" class="no-caps" depressed :loading="verifyCodeForm.loading" :disabled="verifyCodeForm.loading" @click="verifyCodeDialog = false;processCode()">{{ $t('redeem_points') }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

  </div>
</template>
<script>
import { copyStringToClipboard } from '@/utils/helpers'
import { mask } from 'vue-the-mask'

export default {
  data: () => ({
    locale: 'en',
    verifyCodeDialog: false,
    tab: 'tab-0',
    tabImg: 'about:blank',
    tabImgHeight: null,
    tabImgDefaultHeight: 780,
    tabImgAspectRation: 1.7778,
    tabImgs: [],
    verifyCodeForm: {
      success: false,
      loading: false,
      code: ''
    },
    msg: {
      points: 0,
      value: 0,
      customerNumber: ''
    }
  }),
  directives: {
    mask
  },
  created () {
    // Set locale
    this.locale = (this.$auth.check()) ? this.$auth.user().locale: Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale
    this.moment.locale(this.locale.substr(0,2))

    // Tab images
    this.tabImgs = [
      this.$init.config.schemeAndHost + '/img/business-redeem-tab.jpg'
    ]

    // Set image depending on the size of the browser window
    if (window.innerWidth > 760) {
      this.tabImg = this.tabImgs[0]
      this.tabImgHeight = this.tabImgDefaultHeight
      this.tabImgAspectRation = 1.7778
    } else {
      this.tabImgHeight = null
      this.tabImgAspectRation = 3
    }

    // Preload
    let images = new Array()
    this.tabImgs.forEach(function(img, i) {
      images[i] = new Image()
      images[i].src = img
    })

    // Query object
    this.verifyCodeForm.code = this.$route.query.c
  },
  methods: {
    copyStringToClipboard,
    formatNumber (number) {
      return new Intl.NumberFormat(this.locale.replace('_', '-')).format(number)
    },
    formatCurrency (number, currency) {
      return new Intl.NumberFormat(this.locale.replace('_', '-'), {style: 'currency', currency: currency}).format(number)
    },
    onResize () {
      if (window.innerWidth <= 760) {
        this.tabImg = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='
        this.tabImgHeight = null
        this.tabImgAspectRation = 3
      } else if (this.tabImg == 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=') {
        let index = this.tab.split('-')
        this.tabImg = this.tabImgs[index[1]]
        this.tabImgHeight = this.tabImgDefaultHeight
        this.tabImgAspectRation = 1.7778
      }
    },
    verifyCode () {
      this.verifyCodeForm.loading = true
      this.axios
        .post('/business/redeem/verify-code', {
          locale: this.locale,
          code: this.verifyCodeForm.code
        })
        .then(response => {
          if (response.data.status === 'success') {
            this.verifyCodeDialog = true
            this.msg.points = this.formatNumber(response.data.points)
            this.msg.value = this.formatCurrency(response.data.value, response.data.currency)
            this.msg.customerNumber = response.data.customer_number
          }
        })
        .catch(error => {
          if (error.response.data.status === 'error') {
            this.errorMsg = error.response.data.error

            let errors = error.response.data.errors || []

            for (let field in errors) {
              this.$refs['verifyCodeForm.' + field].applyResult({
                errors: errors[field],
                valid: false,
                failedRules: {}
              })
            }
          }
        })
        .finally(() => { 
          window.scrollTo(0,0)
          this.verifyCodeForm.loading = false
        })
    },
    processCode () {
      this.verifyCodeForm.loading = true
      this.axios
        .post('/business/redeem/process-code', {
          locale: this.locale,
          code: this.verifyCodeForm.code
        })
        .then(response => {
          if (response.data.status === 'success') {
            this.verifyCodeForm.code = ''
            this.$nextTick(() => this.$refs['verifyCodeForm'].reset())
            this.verifyCodeForm.success = true
            this.msg.points = this.formatNumber(response.data.redeem.points)
            this.msg.value = this.formatCurrency(response.data.redeem.value, response.data.redeem.currency)
            this.msg.customerNumber = response.data.redeem.customer_number
          }
        })
        .catch(error => {
          if (error.response.data.status === 'error') {
            this.errorMsg = error.response.data.error

            let errors = error.response.data.errors || []

            for (let field in errors) {
              this.$refs['verifyCodeForm.' + field].applyResult({
                errors: errors[field],
                valid: false,
                failedRules: {}
              })
            }
          }
        })
        .finally(() => { 
          window.scrollTo(0,0)
          this.verifyCodeForm.loading = false
        })
    },
  }
}
</script>