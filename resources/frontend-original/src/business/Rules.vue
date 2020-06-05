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
                <v-tab :to="{ name: 'business.staff' }" class="no-caps" :ripple="false">
                  {{ $t('staff') }}
                  <v-icon size="24">mdi-account-tie-outline</v-icon>
                </v-tab>
                <v-tab :to="{ name: 'business.rules' }" class="no-caps" :ripple="false">
                  {{ $t('rules') }}
                  <v-icon size="24">mdi-cards-playing-outline</v-icon>
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
                    md="12"
                  >
                    <v-card flat tile class="content-tabs-card pa-5 mb-0 mb-md-12">
                      <ValidationObserver ref="form1" v-slot="{ invalid }">
                        <v-form 
                          :model="form1" 
                          id="form1"
                          lazy-validation
                          @submit.prevent="updateRules()"
                          autocomplete="off"
                          method="post"
                          accept-charset="UTF-8"
                        >
                          <v-card-text>
                            <v-alert
                              :value="!$auth.user().premium"
                              type="info"
                              tile
                              class="mb-5"
                            >
                              <i18n path="premium_account_required">
                                <template #premium_account>
                                  <router-link :to="{name: 'settings.business.subscription'}" class="white--text">{{ $t('premium_account').toLowerCase() }}</router-link>
                                </template>
                              </i18n>
                            </v-alert>
                            <v-alert
                              :value="form1.success"
                              type="success"
                              class="mb-5"
                            >
                              {{ $t('update_success') }}
                            </v-alert>
                            <v-alert
                              :value="form1.error"
                              type="error"
                              class="mb-5"
                            >
                              {{ $t('correct_errors') }}
                            </v-alert>
                            <v-row>
                              <v-col cols="12" sm="6" class="py-0 order-2 order-sm-1">
                                <h3 class="title mb-3">{{ $t('expiry_period_and_value') }}</h3>
                                <p class="fg--text subtitle-1">{{ $t('expiry_period_and_value_info') }}</p>
                                <x-autocomplete
                                  :disabled="!$auth.user().premium"
                                  v-model="form1.points_expiration_months"
                                  ref="form1.points_expiration_months"
                                  id="form1.points_expiration_months"
                                  :items="pointsExpirationPeriods"
                                  item-value="0" 
                                  item-text="1"
                                  :label="$t('issued_points_expire_after')"
                                  :name="$t('expiration_period')"
                                  rules="required"
                                  dense
                                />
                                <x-text-field
                                  :disabled="!$auth.user().premium"
                                  type="number"
                                  v-model="form1.point_value"
                                  ref="form1.point_value"
                                  id="form1.point_value"
                                  :label="$t('one_point_worth')"
                                  :name="$t('point_value')"
                                  :suffix="form1.currency"
                                  :step="currency_point_value_step"
                                  :min="currency_point_value_step"
                                  :max="currency_point_value_max"
                                  :rules="'required|between:' + currency_point_value_step + ',' + currency_point_value_max"
                                  dense
                                />
                                <h3 class="title mb-3">{{ $t('reward') }}</h3>
                                <x-text-field
                                  :disabled="!$auth.user().premium"
                                  type="number"
                                  v-model="form1.points_per_currency"
                                  ref="form1.points_per_currency"
                                  id="form1.points_per_currency"
                                  :label="$t('points_per_currency', {currency: form1.currency})"
                                  :name="$t('points')"
                                  :suffix="$t('loyalty_points').toLowerCase()"
                                  rules="required|integer|between:1,10000"
                                  min="1"
                                  max="100000"
                                  dense
                                />
                                <h3 class="title mb-3">{{ $t('limit') }}</h3>
                                <x-text-field
                                  :disabled="!$auth.user().premium"
                                  type="number"
                                  v-model="form1.min_points_per_purchase"
                                  ref="form1.min_points_per_purchase"
                                  id="form1.min_points_per_purchase"
                                  :label="$t('min_points_per_purchase')"
                                  :name="$t('min_points_per_purchase')"
                                  :suffix="'(' + formatCurrency (form1.min_points_per_purchase * form1.point_value, 2) + ')'"
                                  rules="required|integer|between:1,1000"
                                  min="1"
                                  max="1000"
                                  dense
                                />
                                <x-text-field
                                  :disabled="!$auth.user().premium"
                                  type="number"
                                  v-model="form1.max_points_per_purchase"
                                  ref="form1.max_points_per_purchase"
                                  id="form1.max_points_per_purchase"
                                  :label="$t('max_points_per_purchase')"
                                  :name="$t('max_points_per_purchase')"
                                  :suffix="'(' + formatCurrency (form1.max_points_per_purchase * form1.point_value, 2) + ')'"
                                  rules="required|integer|between:1,10000000"
                                  min="1"
                                  max="10000000"
                                  dense
                                />
                                <h3 class="title mb-3">{{ $t('requirements') }}</h3>
                                <x-text-field
                                  :disabled="!$auth.user().premium"
                                  type="number"
                                  v-model="form1.min_points_per_redemption"
                                  ref="form1.min_points_per_redemption"
                                  id="form1.min_points_per_redemption"
                                  :label="$t('min_points_per_redemption')"
                                  :name="$t('min_points_per_redemption')"
                                  :suffix="'(' + formatCurrency (form1.min_points_per_redemption * form1.point_value, 2) + ')'"
                                  rules="required|integer|between:1,10000000"
                                  min="1"
                                  max="10000000"
                                  dense
                                />
                                <x-text-field
                                  :disabled="!$auth.user().premium"
                                  type="number"
                                  v-model="form1.max_points_per_redemption"
                                  ref="form1.max_points_per_redemption"
                                  id="form1.max_points_per_redemption"
                                  :label="$t('max_points_per_redemption')"
                                  :name="$t('max_points_per_redemption')"
                                  :suffix="'(' + formatCurrency (form1.max_points_per_redemption * form1.point_value, 2) + ')'"
                                  rules="required|integer|between:1,10000000"
                                  min="1"
                                  max="10000000"
                                  dense
                                />
                              </v-col>
                              <v-col cols="12" sm="6" class="py-0 order-1 order-sm-2">
                                <v-card outlined class="pa-5 pb-0 mb-5 outlined-strong">
                                <h3 class="title mb-3">{{ $t('test_calculator') }}</h3>
                                <p class="fg--text subtitle-1">{{ $t('test_calculator_info') }}</p>
                                <x-text-field
                                  type="number"
                                  v-model="testPurchase"
                                  ref="testPurchase"
                                  id="testPurchase"
                                  :label="$t('purchase_amount')"
                                  :name="$t('purchase_amount')"
                                  :suffix="form1.currency"
                                  min="0"
                                  max="10000"
                                  dense
                                />
                                <div class="mb-5 fg--text body-2">
                                  <p>
                                    <i18n path="test_scenario_1">
                                      <template #amount>{{ formatCurrency(testPurchase) }}</template>
                                      <template #points>{{ getTestPointsFormatted() }}</template>
                                      <template #pointsValue>{{ getTestAmountFormatted() }}</template>
                                    </i18n>
                                  </p>
                                  <p v-if="getRedeemableTestPoints() < 0">
                                    <i18n path="test_scenario_2">
                                      <template #points>{{ formatNumber(Math.abs(getRedeemableTestPoints())) }}</template>
                                      <template #pointsValue>{{ formatCurrency(Math.abs(getRedeemableTestPoints()) * form1.point_value) }}</template>
                                      <template #months>{{ form1.points_expiration_months }}</template>
                                      <template #redemptionPoints>{{ formatNumber(form1.min_points_per_redemption) }}</template>
                                      <template #redemptionPointsValue>{{ formatCurrency(form1.min_points_per_redemption * form1.point_value) }}</template>
                                    </i18n>
                                  </p>
                                  <p v-if="getRedeemableTestPoints() > 0">
                                    <i18n path="test_scenario_3">
                                      <template #points>{{ formatNumber(Math.abs(getRedeemableTestPoints())) }}</template>
                                      <template #pointsValue>{{ formatCurrency(Math.abs(getRedeemableTestPoints()) * form1.point_value) }}</template>
                                      <template #months>{{ form1.points_expiration_months }}</template>
                                    </i18n>
                                  </p>
                                </div>
                                </v-card>
                              </v-col>
                            </v-row>
                          </v-card-text>
                          <v-card-actions class="mx-2">
                            <v-btn color="ctaBg ctaFg--text" tile depressed x-large :loading="form1.loading" :disabled="form1.loading || invalid || !$auth.user().premium" type="submit" class="no-caps mb-2" style="min-width: 200px">{{ $t('update_rules') }} <v-icon right>mdi-arrow-right</v-icon></v-btn>
                          </v-card-actions>
                        </v-form>
                      </ValidationObserver>
                    </v-card>
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
import { copyStringToClipboard } from '@/utils/helpers';

export default {
  data: () => ({
    locale: 'en',
    tab: 'tab-0',
    tabImg: 'about:blank',
    tabImgHeight: null,
    tabImgDefaultHeight: 780,
    tabImgAspectRation: 1.7778,
    tabImgs: [],
    testPurchase: 24.95,
    currency_point_value_step: 0.001,
    currency_point_value_max: 100,
    pointsExpirationPeriods: [
      [1, '1 month'],
      [6, '6 months'],
      [12, '1 year'],
      [24, '2 years'],
      [36, '3 years'],
      [48, '4 years'],
      [60, '5 years']
    ],
    form1: {
      loading: false,
      success: false,
      error: false,
      currency: 'USD',
      points_per_currency: null,
      point_value: null,
      points_expiration_months: null,
      min_points_per_purchase: null,
      max_points_per_purchase: null,
      min_points_per_redemption: null,
      max_points_per_redemption: null
    }
  }),
  created () {
    this.axios
      .get('/business/settings', { params: { locale: this.$i18n.locale }})
      .then(response => {
        let business = response.data.business
        this.currency_point_value_step = business.currency_point_value_step
        this.currency_point_value_max = business.currency_point_value_max

        this.form1.currency = business.currency
        this.form1.points_per_currency = business.points_per_currency
        this.form1.point_value = business.point_value
        this.form1.points_expiration_months = business.points_expiration_months
        this.form1.min_points_per_purchase = business.min_points_per_purchase
        this.form1.max_points_per_purchase = business.max_points_per_purchase
        this.form1.min_points_per_redemption = business.min_points_per_redemption
        this.form1.max_points_per_redemption = business.max_points_per_redemption
      })

    // Set locale
    this.locale = (this.$auth.check()) ? this.$auth.user().locale: Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale
    this.moment.locale(this.locale.substr(0,2))

    // Tab images
    this.tabImgs = [
      this.$init.config.schemeAndHost + '/img/business-rules-tab.jpg'
    ]

    // Set image depending on the size of the browser window
    if (window.innerWidth > 960) {
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
  },
  methods: {
    copyStringToClipboard,
    getTestPoints () {
      let points = this.testPurchase * this.form1.points_per_currency
      if (parseInt(points) < parseInt(this.form1.min_points_per_purchase)) points = this.form1.min_points_per_purchase
      if (parseInt(points) > parseInt(this.form1.max_points_per_purchase)) points = this.form1.max_points_per_purchase
      return points
    },
    getRedeemableTestPoints () {
      let points = this.getTestPoints()
      if (parseInt(points) < parseInt(this.form1.min_points_per_redemption)) points -= this.form1.min_points_per_redemption
      if (parseInt(points) > parseInt(this.form1.max_points_per_redemption)) points = this.form1.max_points_per_redemption
      return points
    },
    getTestPointsFormatted () {
      return this.formatNumber(this.getTestPoints())
    },
    getTestAmountFormatted () {
      let points = this.getTestPoints()
      let amount = points * this.form1.point_value
      return this.formatCurrency(amount)
    },
    formatNumber (number) {
      return new Intl.NumberFormat(this.locale.replace('_', '-')).format(number)
    },
    formatCurrency (number) {
      return new Intl.NumberFormat(this.locale.replace('_', '-'), {style: 'currency', currency: this.form1.currency}).format(number)
    },
    onResize () {
      if (window.innerWidth <= 960) {
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
    updateRules () {
      this.form1.loading = true
      this.form1.success = false
      this.form1.error = false

      this.axios
        .post('/business/rules', {
          locale: this.locale,
          points_per_currency: this.form1.points_per_currency,
          point_value: this.form1.point_value,
          points_expiration_months: this.form1.points_expiration_months,
          min_points_per_purchase: this.form1.min_points_per_purchase,
          max_points_per_purchase: this.form1.max_points_per_purchase,
          min_points_per_redemption: this.form1.min_points_per_redemption,
          max_points_per_redemption: this.form1.max_points_per_redemption
        })
        .then(response => {
          if (response.data.status === 'success') {
            this.$nextTick(() => this.$refs['form1'].reset())
            this.form1.success = true
          }
        })
        .catch(error => {
          if (error.response.data.status === 'error') {
            this.form1.error = true
            this.errorMsg = error.response.data.error

            let errors = error.response.data.errors || []

            for (let field in errors) {
              this.$refs['form1.' + field].applyResult({
                errors: errors[field],
                valid: false,
                failedRules: {}
              })
            }
          }
        })
        .finally(() => { 
          window.scrollTo(0,0)
          this.form1.loading = false
        })
    },
  }
}
</script>