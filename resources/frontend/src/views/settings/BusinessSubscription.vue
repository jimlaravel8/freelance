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
              Billing
              <v-icon size="24">mdi-domain</v-icon>
            </v-tab>
          </v-tabs>
          <v-divider />
        </v-card>
        <v-tabs-items v-model="tab" style="background-color: transparent;">
          <v-tab-item :transition="false" :reverse-transition="false" value="tab-0">
            <v-container class="pa-0">
              <v-row no-gutters>
                <v-col cols="12" sm="12" md="12">
                  <v-card flat tile class="pa-5 mb-0">
                    <v-card-text>
                      <v-card outlined>
                        <v-card-title>Monthly Billing Report</v-card-title>
                        <v-select
                          class="ma-5"
                          label="Select a year"
                          v-model="billings.transactionYear"
                          :items="billings.transactionYears"
                          item-text="0"
                          item-value="0"
                        ></v-select>
                        <v-select
                          class="ma-5"
                          label="Select a month"
                          v-model="billings.transactionMonth"
                          :items="getMonths"
                          item-text="text"
                          item-value="value"
                        ></v-select>
                        <div
                          v-if="billings.loaded && billings.status_code == 200 && transactionMonth != ''"
                          class="ma-5"
                        >
                          <v-input
                            prepend-icon="mdi-trending-up"
                            color="black"
                            :messages="[`Points Issuance Sessions: ${this.billings.transaction_details.earnings.count || 0}`]"
                          >Total Points Issued: {{this.billings.transaction_details.earnings.amount}}</v-input>
                          <v-input
                            prepend-icon="mdi-trending-down"
                            color="black"
                            :messages="[`Points Redemption Sessions: ${this.billings.transaction_details.spendings.count || 0}`]"
                          >Total Points Redeemed: {{this.billings.transaction_details.spendings.amount}}</v-input>
                          <v-input
                            prepend-icon="mdi-clock"
                            color="black"
                            :messages="['Due Date (Net 7)']"
                          >{{this.billings.due_details.date}}</v-input>
                          <v-input
                            prepend-icon="mdi-cash"
                            color="black"
                            :messages="[`Amount Due`]"
                          >{{this.billings.due_details.amount}}</v-input>

                          <v-input
                            prepend-icon="mdi-file-document"
                            color="black"
                            :messages="['Invoice NO']"
                          >{{this.billings.invoice_no}}</v-input>
                          <v-input :messages="['Payment Status']">
                            <v-icon
                              slot="prepend"
                              :color="billings.due_details.status == 1 ? 'green' : 'red'"
                            >{{this.billings.due_details.status == 1 ? 'mdi-check' : 'mdi-close'}}</v-icon>
                            {{this.billings.due_details.status == 1 ? 'Paid' : 'Not Paid'}}
                          </v-input>
                          <div></div>
                          <v-list>
                            <h4 class="ma-3">Payment Options</h4>
                            <v-list-item three-line>
                              <v-list-item-content>
                                <v-list-item-title>Pay by Check</v-list-item-title>
                                <p>Make check payable to: Piggy Bank Loyalty</p>
                                <p>Mail check to: Kingstown, St Vincent & the Grenadines</p>
                                <p>Alternatively, we can arrange pickup.</p>
                              </v-list-item-content>
                            </v-list-item>
                            <v-list-item two-line>
                              <v-list-item-content>
                                <v-list-item-title>Squeeze Cash</v-list-item-title>
                                <p>Send XCD invoice amount to: SQ03017</p>
                                <p>Please include your invoice number(s)</p>
                              </v-list-item-content>
                            </v-list-item>
                            <v-list-item three-line>
                              <v-list-item-content>
                                <v-list-item-title>Pay by Paypal:</v-list-item-title>
                                <p>Go to <a href="https://www.paypal.me/caribbeanadventures" target="_blank">Paypal</a> </p>
                                <p>Please send USD amount on invoice</p>
                                <p>Please include your invoice number(s)</p>
                              </v-list-item-content>
                            </v-list-item>
                            <v-list-item three-line>
                              <v-list-item-content>
                                <v-list-item-title>Payswif:</v-list-item-title>
                                <p>Send XCD invoice amount to: 17845303260</p>
                                <p>Please include your invoice number(s)</p>
                                <!-- <v-list-item-subtitle>Alternatively, we can arrange pickup.</v-list-item-subtitle> -->
                              </v-list-item-content>
                            </v-list-item>
                          </v-list>
                        </div>
                        <h3 class="ma-5" v-if="billings.status_code == 404">No Transaction Found</h3>
                      </v-card>
                    </v-card-text>
                  </v-card>
                </v-col>
                <v-col cols="12" sm="12" md="12">
                  <v-card flat tile class="pa-5 mb-0 mb-md-12">
                    <v-card-text>
                      <v-card outlined>
                        <v-data-table
                          :headers="billings.headers"
                          :items="billings.transactions"
                          :items-per-page="5"
                          class="elevation-1"
                        ></v-data-table>
                      </v-card>
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
import moment from "moment";
export default {
  data: () => ({
    billings: {
      loaded: false,
      showdetails: "",
      headers: [
        {
          text: "Customer NO",
          align: "start",
          value: "customer_number"
        },
        {
          text: "Event",
          value: "event"
        },
        {
          text: "Points",
          value: "points"
        },
        {
          text: "Staff Name",
          value: "staff_name"
        },
        {
          text: "Date",
          value: "created_at"
        }
      ],
      transactionMonth: "",
      transactionYear: new Date().getFullYear(),
      transactionYears: [],
      transactions: [],
      transaction_details: {
        earnings: "",
        spendings: ""
      },
      due_details: {
        date: "select a month",
        amount: "0",
        status: "0"
      },
      invoice_no: "",
      transaction_fee: ""
    },
    locale: "en",
    tab: "tab-0",
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
  created() {
    this.axios(`business/transaction-years`).then(body => {
      this.billings.transactionYears = body.data.years;
    });
    // Set locale
    this.locale = this.$auth.check()
      ? this.$auth.user().locale
      : Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale;
    this.moment.locale(this.locale.substr(0, 2));

    this.axios
      .get("/business/subscription/settings", {
        params: { locale: this.$i18n.locale }
      })
      .then(response => {
        this.subscriptionSettings = response.data;
        this.loading = false;

        if (this.subscriptionSettings.gateway == "stripe") {
          let JavaScript = {
            load: function(src, callback) {
              let script = document.createElement("script"),
                loaded;
              script.setAttribute("src", src);
              if (callback) {
                script.onreadystatechange = script.onload = function() {
                  if (!loaded) {
                    callback();
                  }
                  loaded = true;
                };
              }
              document.getElementsByTagName("head")[0].appendChild(script);
            }
          };

          let that = this;

          JavaScript.load("//checkout.stripe.com/checkout.js", function() {
            that.stripeHandler = window.StripeCheckout.configure({
              key: that.subscriptionSettings.key,
              image: null,
              locale: "auto",
              token: function(token) {
                that.subscriptionForm.loading = true;
                // You can access the token ID with `token.id`.
                // Get the token ID to your server-side code for use.
                that.axios
                  .post("/business/stripe/token", {
                    token: token.id,
                    email: token.email,
                    type: token.type,
                    stripe_plan_id: that.selectedRemotePlanId,
                    months: that.selectedMonths
                  })
                  .then(function(response) {
                    that.paymentProcessed = true;
                    that.paymentProcessedGateway = "stripe";
                    that.subscriptionForm.loading = false;
                  })
                  .catch(function(error) {
                    that.$root.$snackbar(
                      "An unknow error has occured. Please refresh this page and try again. Contact us if the error persists."
                    );
                    that.subscriptionForm.loading = false;
                    console.log(error);
                  });
              }
            });
          });
        }
      });
  },
  methods: {
    async getTransactions() {
      const body = await this.axios.get(
        `/business/transaction/${this.billings.transactionMonth}-${this.billings.transactionYear}`
      );
      const data = body.data;
      this.billings.status_code = body.data.status_code ?? 200;

      const {
        transactions,
        transaction_details,
        due_details,
        invoice_no,
        transaction_fee
      } = data;
      this.billings.transactions = transactions;
      this.transaction_fee = transaction_fee;
      this.billings.transaction_details.earnings = transaction_details.earnings;
      this.billings.transaction_details.spendings =
        transaction_details.spendings;
      this.billings.due_details.date = moment(
        new Date(due_details.date)
      ).format("DD MMM YYYY");
      this.billings.due_details.amount = due_details.amount;
      this.billings.due_details.status = due_details.status;
      this.billings.invoice_no = invoice_no;
      this.billings.loaded = true;
    },
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
    goToCheckout() {
      if (
        this.$init.config.demo === true &&
        this.subscriptionSettings.gateway == "manual"
      ) {
        this.subscriptionForm.loading = true;
        this.axios
          .post("/business/subscription/demo", {
            locale: this.locale,
            months: this.subscriptionForm.months
          })
          .then(response => {
            if (response.data.status === "success") {
              this.$auth.user(response.data.user);
              this.paymentProcessed = true;
            }
          })
          .catch(error => {
            if (error.response.data.status === "error") {
              this.errorMsg = error.response.data.error;

              let errors = error.response.data.errors || [];

              for (let field in errors) {
                this.$refs["subscriptionForm." + field].applyResult({
                  errors: errors[field],
                  valid: false,
                  failedRules: {}
                });
              }
            }
          })
          .finally(() => {
            window.scrollTo(0, 0);
            this.subscriptionForm.loading = false;
          });
      } else {
        if (this.subscriptionSettings.gateway == "manual") {
          this.subscriptionForm.loading = true;
          // To do
        } else if (this.subscriptionSettings.gateway == "stripe") {
          let stripe_plan_id, name, amount;
          if (this.subscriptionForm.months == 1) {
            name = this.$t("monthly_subscription");
            stripe_plan_id = this.subscriptionSettings.plan_id_monthly;
            amount = this.subscriptionSettings.premium_price_monthly * 100;
          }

          if (this.subscriptionForm.months == 12) {
            name = this.$t("annual_subscription");
            stripe_plan_id = this.subscriptionSettings.plan_id_yearly;
            amount = this.subscriptionSettings.premium_price_yearly * 100;
          }

          // Set global vars
          this.selectedMonths = this.subscriptionForm.months;
          this.selectedRemotePlanId = stripe_plan_id;

          // Open Checkout with further options:
          this.stripeHandler.open({
            name: name,
            description: null,
            currency: this.subscriptionSettings.premium_price_currency,
            amount: amount
          });
        }
      }
    },
    cancelSubscription() {
      let that = this;

      if (this.subscriptionSettings.gateway == "stripe") {
        this.$root
          .$confirm(
            this.$t("cancel_subscription"),
            this.$t("cancel_subscription_confirm")
          )
          .then(confirm => {
            if (confirm) {
              that.loading = true;

              this.axios
                .post("/business/stripe/cancel")
                .then(function(response) {
                  that.subscriptionCancelled = true;
                  that.subscriptionSettings.remote_customer_id = null;
                  that.loading = false;
                })
                .catch(function(error) {
                  console.log(error);
                  that.loading = false;
                });
            }
          });
      }
    }
  },
  computed: {
    getMonths: function() {
      let months = [];
      let monthsNames = moment.months();
      for (let i in monthsNames) {
        months.push({
          value: `1-${parseInt(i) + 1}`,
          text: monthsNames[i]
        });
      }
      return months;
    }
  },
  watch: {
    "billings.transactionMonth": function(newOne, oldOne) {
      this.billings.transactionMonth = newOne;
      if (newOne !== "") {
        this.getTransactions();
      }
    },
    "billings.transactionYear": function(newOne, oldOne) {
      this.billings.transactionYear = newOne;
      this.billings.transactionMonth = "";
    }
  }
};
</script>
<style>
.input-group--disabled.checkbox .input-group__input {
  color: #000 !important;
}

.input-group--disabled.input-group--select label {
  color: #000 !important;
}
.v-list-item__subtitle,
.v-list-item__title {
    white-space: break-spaces !important;
}
</style>
