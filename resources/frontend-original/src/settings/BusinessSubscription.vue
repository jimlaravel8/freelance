<template>
  <div class="viewContainer">
    <v-container class="pa-0">
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
        </v-card>
        <v-tabs-items v-model="tab" style="background-color: transparent;">
          <v-tab-item :transition="false" :reverse-transition="false" value="tab-0">
            <v-container class="pa-0">
              <v-row no-gutters>
                <v-col
                  cols="12"
                  sm="9"
                  md="6"
                >
                  <v-card flat tile class="content-tabs-card pa-5 mb-0 mb-md-12">
                    <v-card-text>
                      <div v-if="!paymentProcessed">
                        <div v-if="!$auth.user().premium">
                          <p class="title font-weight-bold">{{ $t('currently_free_account') }}</p>
                          <p class="body-1">
                            <i18n path="premium_account_desc1">
                              <template #change_the_rules><router-link :to="{ name: 'business.rules' }">{{ $t('change_the_rules').toLowerCase() }}</router-link></template>
                              <template #add_staff_members><router-link :to="{ name: 'business.staff' }">{{ $t('add_staff_members').toLowerCase() }}</router-link></template>
                            </i18n>
                          </p>
                          <p class="body-1">{{ $t('premium_account_desc2') }}</p>
                          <ValidationObserver ref="subscriptionForm" v-slot="{ invalid }" v-if="!loading">
                            <v-form 
                              id="subscriptionForm"
                              lazy-validation
                              @submit.prevent="goToCheckout()"
                              autocomplete="off"
                              method="post"
                              accept-charset="UTF-8"
                            >
                              <ValidationProvider rules="required" v-slot="{ }">
                                <v-radio-group
                                  v-model="subscriptionForm.months"
                                  class="title"
                                  :disabled="$init.config.demo !== true && subscriptionSettings.gateway == 'manual'"
                                >
                                  <v-radio
                                    :label="$t('get_monthly_subscription', { price: formatCurrency(subscriptionSettings.premium_price_monthly, subscriptionSettings.premium_price_currency) })"
                                    class="ma-2"
                                    value="1"
                                  ></v-radio>
                                  <v-radio
                                    :label="$t('get_yearly_subscription', { price: formatCurrency(subscriptionSettings.premium_price_yearly, subscriptionSettings.premium_price_currency) }) + ' - ' + $t('save_percentage', { percentage: Math.round(((subscriptionSettings.premium_price_monthly * 12) - subscriptionSettings.premium_price_yearly) / (subscriptionSettings.premium_price_yearly / 100)) })"
                                    class="ma-2"
                                    value="12"
                                  ></v-radio>
                                </v-radio-group>
                              </ValidationProvider>

                              <v-alert
                                :value="($init.config.demo === true) ? true : false"
                                type="info"
                                tile
                                class="mb-5"
                              >
                                <p>{{ $t('demo_payments') }}</p>
                                <p v-if="subscriptionSettings.gateway == 'stripe'" v-html="$t('stripe_test')"/>
                              </v-alert>

                              <p class="body-1" v-if="$init.config.demo !== true && subscriptionSettings.gateway == 'manual'">{{ $t('premium_account_manual', { email: subscriptionSettings.email }) }}</p>

                              <v-btn
                                v-if="($init.config.demo === true && subscriptionSettings.gateway == 'manual') || subscriptionSettings.gateway != 'manual'"
                                type="submit"
                                depressed 
                                tile
                                block
                                x-large
                                color="ctaBg ctaFg--text"
                                class="no-caps"
                                :loading="subscriptionForm.loading" :disabled="subscriptionForm.loading || invalid"
                              >
                                {{ $t('go_to_checkout') }} <v-icon right>mdi-arrow-right</v-icon>
                              </v-btn>
                            </v-form>
                          </ValidationObserver>
                        </div>
                        <div v-if="$auth.user().premium && subscriptionSettings !== null">
                          <p class="title font-weight-bold">{{ $t('currently_premium_account') }}</p>
                          <p class="body-1">{{ $t('premium_subscription_end_date', { expires_date: formatDate(subscriptionSettings.expires_at, 'LL'), expires_friendly: formatDate(subscriptionSettings.expires_at, 'ago')}) }}</p>
                          <p class="body-1">{{ $t('premium_account_info1') }}</p>
                          <p class="body-1">{{ $t('premium_account_info2') }}</p>
                          
                          <div v-if="subscriptionSettings.gateway == 'stripe'">
                            <v-btn color="red darken-2 white--text mt-5" class="no-caps" x-large depressed :loading="loading" :disabled="loading" @click="cancelSubscription" v-if="subscriptionSettings.remote_customer_id">{{ $t('cancel_subscription') }}</v-btn>
                            <p class="body-1 font-weight-bold red--text text--darken-2" v-if="!subscriptionSettings.remote_customer_id">{{ $t('subscription_cancelled_info') }}</p>
                          </div>
                        </div>
                      </div>
                      <div v-if="paymentProcessed">
                        <p class="title font-weight-bold">{{ $t('thank_you_for_purchase') }}</p>
                        <div v-if="paymentProcessedGateway == 'stripe'">
                          <p class="body-1">{{ $t('payment_processed1') }}</p>
                          <p class="body-1">{{ $t('payment_processed2') }}</p>
                        </div>
                        <div v-else-if="$init.config.demo === true && subscriptionSettings.gateway == 'manual'">
                          <p class="body-1">{{ $t('you_now_have_premium') }}</p>
                        </div>
                      </div>
                    </v-card-text>
                  </v-card>
                </v-col>
              </v-row>
            </v-container>
          </v-tab-item>
        </v-tabs-items>
      </v-container>
    </v-container>
  </div>
</template>
<script>
export default {
  data: () => ({
    locale: 'en',
    tab: 'tab-0',
    loading: true,
    subscriptionSettings: null,
    paymentProcessed: false,
    paymentProcessedGateway: null,
    subscriptionCancelled: false,
    subscriptionForm: {
      loading: false,
      months: null
    },
    stripeHandler: null,
    selectedMonths: null,
    selectedRemotePlanId: null
  }),
  created () {
    // Set locale
    this.locale = (this.$auth.check()) ? this.$auth.user().locale: Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale
    this.moment.locale(this.locale.substr(0,2))

    this.axios
      .get('/business/subscription/settings', { params: { locale: this.$i18n.locale }})
      .then(response => {
        this.subscriptionSettings = response.data
        this.loading = false

        if (this.subscriptionSettings.gateway == 'stripe') {
          let JavaScript = {
            load: function(src, callback) {
              let script = document.createElement('script'),
                  loaded
              script.setAttribute('src', src)
              if (callback) {
                script.onreadystatechange = script.onload = function() {
                  if (!loaded) {
                    callback()
                  }
                  loaded = true
                }
              }
              document.getElementsByTagName('head')[0].appendChild(script)
            }
          }

          let that = this

          JavaScript.load("//checkout.stripe.com/checkout.js", function() {
            that.stripeHandler = window.StripeCheckout.configure({
              key: that.subscriptionSettings.key,
              image: null,
              locale: 'auto',
              token: function(token) {
                that.subscriptionForm.loading = true
                // You can access the token ID with `token.id`.
                // Get the token ID to your server-side code for use.
                that.axios.post('/business/stripe/token', {
                  token: token.id, 
                  email: token.email, 
                  type: token.type, 
                  stripe_plan_id: that.selectedRemotePlanId,
                  months: that.selectedMonths
                })
                .then(function (response) {
                  that.paymentProcessed = true
                  that.paymentProcessedGateway = 'stripe'
                  that.subscriptionForm.loading = false
                })
                .catch(function (error) {
                  that.$root.$snackbar("An unknow error has occured. Please refresh this page and try again. Contact us if the error persists.")
                  that.subscriptionForm.loading = false
                  console.log(error)
                })
              }
            })
          })
        }
      })
  },
  methods: {
    formatNumber (number) {
      return new Intl.NumberFormat(this.locale.replace('_', '-')).format(number)
    },
    formatCurrency (number, currency) {
      return new Intl.NumberFormat(this.locale.replace('_', '-'), {style: 'currency', currency: currency}).format(number)
    },
    formatDate (dt, format) {
      if (dt === null) {
        dt = '-'
      } else {
        dt = (format == 'ago') ? this.moment(dt).fromNow() : this.moment(dt).format(format)
      }
      return dt
    },
    goToCheckout () {
      if (this.$init.config.demo === true && this.subscriptionSettings.gateway == 'manual') {
        this.subscriptionForm.loading = true
        this.axios
          .post('/business/subscription/demo', {
            locale: this.locale,
            months: this.subscriptionForm.months
          })
          .then(response => {
            if (response.data.status === 'success') {
              this.$auth.user(response.data.user)
              this.paymentProcessed = true
            }
          })
          .catch(error => {
            if (error.response.data.status === 'error') {
              this.errorMsg = error.response.data.error

              let errors = error.response.data.errors || []

              for (let field in errors) {
                this.$refs['subscriptionForm.' + field].applyResult({
                  errors: errors[field],
                  valid: false,
                  failedRules: {}
                })
              }
            }
          })
          .finally(() => { 
            window.scrollTo(0,0)
            this.subscriptionForm.loading = false
          })
      } else {
        if (this.subscriptionSettings.gateway == 'manual') {
          this.subscriptionForm.loading = true
          // To do
        } else if (this.subscriptionSettings.gateway == 'stripe') {

          let stripe_plan_id, name, amount
          if (this.subscriptionForm.months == 1) {
            name = this.$t('monthly_subscription')
            stripe_plan_id = this.subscriptionSettings.plan_id_monthly
            amount = this.subscriptionSettings.premium_price_monthly * 100
          }

          if (this.subscriptionForm.months == 12) {
            name = this.$t('annual_subscription')
            stripe_plan_id = this.subscriptionSettings.plan_id_yearly
            amount = this.subscriptionSettings.premium_price_yearly * 100
          }

          // Set global vars
          this.selectedMonths = this.subscriptionForm.months
          this.selectedRemotePlanId = stripe_plan_id

          // Open Checkout with further options:
          this.stripeHandler.open({
            name: name,
            description: null,
            currency: this.subscriptionSettings.premium_price_currency,
            amount: amount
          })
        }
      }
    },
    cancelSubscription () {
      let that = this

      if (this.subscriptionSettings.gateway == 'stripe') {
        this.$root.$confirm(this.$t('cancel_subscription'), this.$t('cancel_subscription_confirm'))
        .then((confirm) => {
          if (confirm) {
            that.loading = true

            this.axios.post('/business/stripe/cancel')
              .then(function (response) {
                that.subscriptionCancelled = true
                that.subscriptionSettings.remote_customer_id = null
                that.loading = false
              })
              .catch(function (error) {
                console.log(error)
                that.loading = false
              })
          }
        })
      }
    },
  }
}
</script>