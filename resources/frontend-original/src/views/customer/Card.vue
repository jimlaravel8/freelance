<template>
  <div class="viewContainer" v-resize="onResize">
    <v-container class="pa-0">
      <v-img
        style="position: absolute;top:0; width: 1280px"
        class="mx-auto"
        :src="tabImg"
        :key="tabImg"
        :min-height="tabImgHeight"
        :aspect-ratio="tabImgAspectRation"
        transition="fade-transition"
      ></v-img>
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
            <v-tab :to="{ name: 'customer.earn' }" class="no-caps" :ripple="false">
              {{ $t('earn') }}
              <v-icon size="24">mdi-coins</v-icon>
            </v-tab>
            <v-tab :to="{ name: 'customer.wallet' }" class="no-caps" :ripple="false">
              {{ $t('wallet') }}
              <v-icon size="24">mdi-wallet-outline</v-icon>
            </v-tab>
            <v-tab :to="{ name: 'customer.card' }" class="no-caps" :ripple="false">
              {{ $t('card') }}
              <v-icon size="24">mdi-wallet-giftcard</v-icon>
            </v-tab>
          </v-tabs>
          <v-divider />
        </v-card>
        <v-tabs-items v-model="tab" style="background-color: transparent;">
          <v-tab-item value="tab-0">
            <v-container class="pa-0">
              <v-row no-gutters>
                <v-col cols="12" sm="9" md="6">
                  <v-card flat tile class="content-tabs-card pa-5 mb-0 mb-md-12" elevation="0">
                    <v-card-text>
                      <v-progress-linear :active="loading" indeterminate color="ctaBg"></v-progress-linear>
                      <div v-if="card !== null && !loading">
                        <v-card flat tile elevation="1" outlined>
                          <v-img v-if="card.cover" :src="card.cover" />
                          <v-card-title>
                            <v-icon left color="ctaBg">mdi-wallet-giftcard</v-icon>
                            <span class="title font-weight-medium">{{ card.name }}</span>
                          </v-card-title>
                          <v-row align="center" class="mx-4">
                            <span
                              class="body-1 font-weight-medium"
                              v-html="formatNumber(card.balance.points)"
                            />
                            <v-icon right size="18">mdi-coins</v-icon>
                            <div class="grey--text ml-5">
                              <span
                                v-html="formatCurrency(card.balance.value, card.balance.currency)"
                              />
                            </div>
                          </v-row>
                          <v-card-text>
                            <div
                              class="subtitle-1 mb-3"
                              v-if="card.short_description"
                              v-html="card.short_description"
                            />
                            <div class="body-2">
                              <div
                                v-if="!card.balance.redeemable"
                              >{{ $t('points_required_for_discount', { points: formatNumber(card.balance.pointsRequired) }) }}</div>
                              <div v-if="card.balance.redeemable">
                                <v-btn
                                  @click="dialogGenerateDiscountCode = true"
                                  color="NavBg NavFg--text"
                                  large
                                  depressed
                                  class="no-caps"
                                >
                                  {{ $t('discount') }}
                                  <v-icon right size="14">mdi-arrow-right</v-icon>
                                </v-btn>
                              </div>
                            </div>
                          </v-card-text>
                          <v-divider />
                          <v-expansion-panels flat elevation="0">
                            <v-expansion-panel>
                              <v-expansion-panel-header>{{ $t('rules_and_conditions') }}</v-expansion-panel-header>
                              <v-expansion-panel-content>
                                <ul>
                                  <li>{{ $t('rules_1', { months: card.balance.rules.points_expiration_months }) }}</li>
                                  <li>{{ $t('rules_2', { currency: card.balance.currency, points: formatNumber(card.balance.rules.points_per_currency), value: formatCurrency(card.balance.rules.points_per_currency * card.balance.rules.point_value, card.balance.currency) }) }}</li>
                                  <li>{{ $t('rules_3', { minPoints: formatNumber(card.balance.rules.min_points_per_purchase), maxPoints: formatNumber(card.balance.rules.max_points_per_purchase) }) }}</li>
                                  <li>{{ $t('rules_4', { minPoints: formatNumber(card.balance.rules.min_points_per_redemption), maxPoints: formatNumber(card.balance.rules.max_points_per_redemption) }) }}</li>
                                </ul>
                              </v-expansion-panel-content>
                            </v-expansion-panel>
                          </v-expansion-panels>
                        </v-card>
                        <v-card flat tile elevation="1" outlined class="pr-5">
                          <v-timeline dense clipped>
                            <v-timeline-item
                              v-for="(item, index) in card.history"
                              :key="index"
                              fill-dot
                              :color="(item.expired) ? ($store.state.app.dark) ? 'grey darken-2' : 'grey lighten-1' : (item.event == 'purchase') ? 'green' : 'blue'"
                              :class="(item.expired) ? ($store.state.app.dark) ? 'grey--text text--darken-2' : 'grey--text text--lighten-1' : ''"
                              :icon="(item.event == 'purchase') ? 'mdi-coins' : 'mdi-content-cut'"
                            >
                              <v-row justify="space-between" align="center">
                                <v-col>
                                  <strong>
                                    {{ $t(item.event) }}
                                    <span
                                      class="ml-2"
                                      :class="($store.state.app.dark) ? 'grey--text text--darken-2' : 'grey--text text--lighten-1'"
                                    >{{ formatCurrency(item.purchase_amount, card.balance.currency) }}</span>
                                  </strong>
                                  <div class="body-2">
                                    <span v-if="item.points > 0" v-html="'+'" />
                                    {{ formatNumber(item.points) }} {{ $t('points').toLowerCase() }}
                                  </div>
                                  <div
                                    class="mt-1 caption"
                                    v-if="item.event == 'purchase'"
                                    :class="($store.state.app.dark) ? 'grey--text text--darken-2' : 'grey--text text--lighten-1'"
                                  >
                                    <span
                                      v-if="item.points_used > 0"
                                    >{{ $t('points_used', { points: formatNumber(item.points_used) }) }}</span>
                                    <span
                                      v-if="!item.expired && item.points_available > 0"
                                    >, {{ $t('points_expire', { points: formatNumber(item.points_available), expire: formatDate(item.expires_at, 'ago') }) }}</span>
                                    <span
                                      v-if="item.expired && item.points_available > 0"
                                    >{{ $t('points_expired', { points: formatNumber(item.points_available) }) }}</span>
                                  </div>
                                </v-col>
                                <v-col
                                  class="text-right caption"
                                  cols="4"
                                >{{ formatDate(item.created_at, 'ago') }}</v-col>
                              </v-row>
                            </v-timeline-item>
                          </v-timeline>
                        </v-card>
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

    <v-dialog v-model="dialogGenerateDiscountCode" persistent max-width="360">
      <ValidationObserver ref="generateDiscountCodeForm" v-slot="{ invalid }">
        <v-form
          id="generateDiscountCodeForm"
          lazy-validation
          @submit.prevent="generateDiscountCode()"
          autocomplete="off"
          method="post"
        >
          <v-card>
            <v-card-title class="headline">
              {{ $t('discount_code') }}
              <v-tooltip top max-width="400" color="NavBg NagFg--text">
                <template v-slot:activator="{ on }">
                  <v-icon
                    style="margin-top:-2px;position: absolute; right: 15px"
                    color="ctaBg"
                    v-on="on"
                  >mdi-help-circle-outline</v-icon>
                </template>
                <span
                  v-html="$t('discount_code_help', { expiration_hours: card.discount_code_expiration_time_hours })"
                ></span>
              </v-tooltip>
            </v-card-title>
            <div v-if="step == 1">
              <v-card-text>
                <p
                  class="body-1"
                >{{ $t('enter_discount_amount', { minAmount: formatCurrency(card.balance.rules.min_amount_redeemable, card.balance.currency), maxAmount: formatCurrency(card.balance.rules.max_amount_redeemable, card.balance.currency) }) }}</p>
                <v-row no-gutters>
                  <v-col cols="3">
                    <v-btn
                      depressed
                      tile
                      block
                      small
                      color="ctaBg ctaFg--text"
                      class="ma-0 no-caps"
                      height="57px"
                      @click="generateDiscountCodeForm.amount = card.balance.rules.max_amount_redeemable"
                    >{{ $t('max') }}</v-btn>
                  </v-col>
                  <v-col cols="9">
                    <x-text-field
                      type="number"
                      autofocus
                      v-model="generateDiscountCodeForm.amount"
                      ref="generateDiscountCodeForm.amount"
                      id="generateDiscountCodeForm.amount"
                      :name="$t('amount')"
                      :suffix="card.balance.currency"
                      :step="card.balance.rules.currency_step"
                      :rules="'required|between:' + card.balance.rules.min_amount_redeemable + ',' + card.balance.rules.max_amount_redeemable"
                      :hint="generateDiscountCodeForm.points + ' ' + $t('points').toLowerCase()"
                      persistent-hint
                    />
                  </v-col>
                </v-row>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="secondary"
                  class="no-caps"
                  text
                  @click="dialogGenerateDiscountCode = false"
                >{{ $t('close') }}</v-btn>
                <v-btn
                  color="NavBg NavFg--text"
                  class="no-caps"
                  depressed
                  :loading="generateDiscountCodeForm.loading"
                  :disabled="generateDiscountCodeForm.loading || invalid"
                  type="submit"
                >
                  {{ $t('get_code') }}
                  <v-icon right>mdi-arrow-right</v-icon>
                </v-btn>
              </v-card-actions>
            </div>
            <div v-if="step == 2">
              <v-card-text>
                <p
                  class="body-1"
                >{{ $t('discount_code_details', { expires: generateDiscountCodeForm.expiresAt, points: generateDiscountCodeForm.calculatedPoints, amount: generateDiscountCodeForm.calculatedAmount, businessName: generateDiscountCodeForm.businessName }) }}</p>
                <div class="mt-5">
                  <x-text-field
                    type="number"
                    v-model="generateDiscountCodeForm.code"
                    ref="generateDiscountCodeForm.code"
                    id="generateDiscountCodeForm.code"
                    class="title"
                    outlined
                    readonly
                    append-icon="mdi-content-copy"
                    @click:append="copyStringToClipboard(generateDiscountCodeForm.code)"
                  />
                </div>
                <qrcode
                  class="mt-0"
                  :value="$init.config.schemeAndHost + '/business/redeem-points?c=' + generateDiscountCodeForm.code"
                  :options="{ margin:0, width: 311, errorCorrectionLevel: 'H', color: { light: '#ffffff00', dark: ($vuetify.theme.dark) ? '#ffffff' : '#000000' } }"
                ></qrcode>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="NavBg NavFg--text"
                  class="no-caps"
                  depressed
                  @click="step = 1; dialogGenerateDiscountCode = false"
                >{{ $t('close') }}</v-btn>
              </v-card-actions>
            </div>
          </v-card>
        </v-form>
      </ValidationObserver>
    </v-dialog>
  </div>
</template>
<script>
import { copyStringToClipboard } from "@/utils/helpers";

export default {
  data: () => ({
    locale: "en",
    tab: "tab-0",
    tabImg: "about:blank",
    tabImgHeight: null,
    tabImgDefaultHeight: 780,
    tabImgAspectRation: 1.7778,
    tabImgs: [],
    loading: true,
    step: 1,
    dialogGenerateDiscountCode: false,
    generateDiscountCodeForm: {
      loading: false,
      code: "",
      amount: 0,
      points: 0,
      expiresAt: null,
      calculatedPoints: 0,
      calculatedAmount: 0,
      businessName: ""
    },
    card: null
  }),
  created() {
    // Set locale
    this.locale = this.$auth.check()
      ? this.$auth.user().locale
      : Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale;
    this.moment.locale(this.locale.substr(0, 2));

    // Query object
    let uuid = this.$route.params.uuid;

    this.axios
      .get("/customer/card", {
        params: { locale: this.$i18n.locale, uuid: uuid }
      })
      .then(response => {
        this.card = response.data.card;
        this.generateDiscountCodeForm.amount = this.card.balance.rules.max_amount_redeemable;
        this.generateDiscountCodeForm.points = this.formatNumber(
          this.card.balance.rules.max_points_redeemable /
            this.card.balance.rules.point_value
        );
        this.loading = false;
      });

    // Tab images
    this.tabImgs = [
      this.$init.config.schemeAndHost + "/img/customer-wallet-tab.jpg"
    ];

    // Set image depending on the size of the browser window
    if (window.innerWidth > 760) {
      this.tabImg = this.tabImgs[0];
      this.tabImgHeight = this.tabImgDefaultHeight;
      this.tabImgAspectRation = 1.7778;
    } else {
      this.tabImgHeight = null;
      this.tabImgAspectRation = 3;
    }

    // Preload
    let images = new Array();
    this.tabImgs.forEach(function(img, i) {
      images[i] = new Image();
      images[i].src = img;
    });
  },
  methods: {
    copyStringToClipboard,
    formatNumber(number) {
      return new Intl.NumberFormat(this.locale.replace("_", "-")).format(
        number
      );
    },
    formatCurrency(number, currency) {
      return new Intl.NumberFormat(this.locale.replace("_", "-"), {
        style: "currency",
        currency: currency
      }).format(number);
    },
    formatDate(dt, format) {
      if (dt === null) {
        dt = "-";
      } else {
        dt =
          format == "ago"
            ? this.moment(dt).fromNow()
            : this.moment(dt).format(format);
      }
      return dt;
    },
    generateDiscountCode() {
      this.generateDiscountCodeForm.loading = true;
      this.axios
        .post("/customer/generate-discount-code", {
          locale: this.locale,
          uuid: this.card.uuid,
          amount: this.generateDiscountCodeForm.amount
        })
        .then(response => {
          if (response.data.status === "success") {
            this.generateDiscountCodeForm.code = response.data.code;
            this.generateDiscountCodeForm.calculatedPoints = this.formatNumber(
              response.data.points
            );
            this.generateDiscountCodeForm.calculatedAmount = this.formatCurrency(
              response.data.amount,
              this.card.balance.currency
            );
            this.generateDiscountCodeForm.expiresAt = this.formatDate(
              response.data.expires_at,
              "lll"
            );
            this.generateDiscountCodeForm.businessName = response.data.name;
            this.step = 2;
          }
        })
        .catch(error => {
          if (error.response.data.status === "error") {
            this.errorMsg = error.response.data.error;
            let errors = error.response.data.errors || [];

            for (let field in errors) {
              this.$refs["generateDiscountCodeForm." + field].applyResult({
                errors: errors[field],
                valid: false,
                failedRules: {}
              });
            }
          }
        })
        .finally(() => {
          this.generateDiscountCodeForm.loading = false;
        });
    },
    onResize() {
      if (window.innerWidth <= 760) {
        this.tabImg =
          "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=";
        this.tabImgHeight = null;
        this.tabImgAspectRation = 3;
      } else if (
        this.tabImg ==
        "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
      ) {
        let index = this.tab.split("-");
        this.tabImg = this.tabImgs[index[1]];
        this.tabImgHeight = this.tabImgDefaultHeight;
        this.tabImgAspectRation = 1.7778;
      }
    }
  },
  watch: {
    "generateDiscountCodeForm.amount": function() {
      this.generateDiscountCodeForm.points = this.formatNumber(
        this.generateDiscountCodeForm.amount /
          this.card.balance.rules.point_value
      );
    }
  }
};
</script>