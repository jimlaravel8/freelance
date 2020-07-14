<template>
<div class="viewContainer" v-resize="onResize">
    <v-container class="pa-0">
        <v-img :src="tabImg" :key="tabImg" :min-height="tabImgHeight" :aspect-ratio="tabImgAspectRation" transition="fade-transition">
            <v-container class="inner-container">
                <v-card class="mt-md-12 mt-0 content-tabs-wrapper" tile elevation="0">
                    <v-tabs :color="($store.state.app.dark) ? 'white' : 'black'" class="px-5 content-tabs" icons-and-text height="100" slider-size="4" show-arrows>
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
                    <v-divider />
                </v-card>
                <v-tabs-items v-model="tab" style="background-color: transparent;">
                    <v-tab-item value="tab-0">
                        <v-container class="pa-0">
                            <v-row no-gutters>
                                <v-col cols="12" sm="9" md="6">
                                    <v-card flat tile class="content-tabs-card pa-5 mb-0 mb-md-12">
                                        <v-card-text v-if="$auth.user().role === 4 && !$auth.user().premium">
                                            <p class="fg--text subtitle-1 font-weight-medium">{{ $t('account_inactive') }}</p>
                                        </v-card-text>
                                        <v-card-text v-else>
                                            <v-alert :value="issuePointsForm.success" type="success" class="mb-5">
                                                <i18n path="issue_points_success">
                                                    <template #customerNumber>{{ msg.customerNumber }}</template>
                                                    <template #points>{{ msg.points }}</template>
                                                    <template #value>{{ msg.value }}</template>
                                                    <template #transactions>
                                                        <router-link class="white--text" :to="{ name: 'business.transactions' }">{{ $t('transactions').toLowerCase() }}</router-link>
                                                    </template>
                                                </i18n>
                                            </v-alert>
                                            <p class="fg--text subtitle-1 font-weight-medium" v-if="$auth.user().role === 3">
                                                <i18n path="issue_points_configure_rules">
                                                    <template #configure_the_rules>
                                                        <router-link :to="{ name: 'business.rules' }">{{ $t('configure_the_rules').toLowerCase() }}</router-link>
                                                    </template>
                                                </i18n>
                                            </p>
                                            <p class="fg--text subtitle-1">{{ $t('issue_points_head') }}</p>
                                            <ValidationObserver ref="verifyCustomerNumberForm" v-slot="{ invalid }">
                                                <v-form id="verifyCustomerNumberForm" lazy-validation @submit.prevent="verifyCustomerNumber()" autocomplete="off" method="post" accept-charset="UTF-8">
                                                    <v-row no-gutters>
                                                        <v-col cols="8">
                                                            <x-text-field inputmode="numeric" :name="$t('customer_number')" v-model="verifyCustomerNumberForm.customerNumber" id="verifyCustomerNumberForm.customerNumber" ref="verifyCustomerNumberForm.customerNumber" rules="required|min:14|max:14" class="title" outlined v-mask="'#-###-###-####'" placeholder="#-###-###-####" />
                                                        </v-col>
                                                        <v-col cols="4">
                                                            <v-btn type="submit" depressed tile block color="ctaBg ctaFg--text" class="title ma-0 no-caps" height="56px" :loading="verifyCustomerNumberForm.loading" :disabled="verifyCustomerNumberForm.loading || invalid">
                                                                {{ $t('verify') }}
                                                                <v-icon right>mdi-arrow-right</v-icon>
                                                            </v-btn>
                                                        </v-col>
                                                    </v-row>
                                                </v-form>
                                            </ValidationObserver>
                                            <p class="mt-0 mb-0 fg--text subtitle-1">{{ $t('issue_points_footer') }}</p>
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

    <v-dialog v-model="dialogIssuePoints" persistent max-width="320">
        <ValidationObserver ref="issuePointsForm" v-slot="{ invalid }">
            <v-form id="issuePointsForm" lazy-validation @submit.prevent="issuePoints()" autocomplete="off" method="post">
                <v-card>
                    <v-card-title class="headline">{{ $t('issue_points') }}</v-card-title>
                    <v-card-text>
                        <p class="body-1">{{ $t('enter_amount_of_purchase') }}</p>
                        <x-text-field type="number" autofocus v-model="issuePointsForm.amount" ref="issuePointsForm.amount" id="issuePointsForm.amount" :name="$t('amount')" :suffix="business.currency" :step="business.currency_step" :rules="'required|between:1,100000'" :hint="purchaseAmount + ' = ' + pointsIssued + ' ' + $t('points').toLowerCase()" persistent-hint />
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="secondary" class="no-caps" text @click="dialogIssuePoints = false">{{ $t('close') }}</v-btn>
                        <v-btn color="NavBg NavFg--text" class="no-caps" depressed :loading="issuePointsForm.loading" :disabled="issuePointsForm.loading || invalid" type="submit">{{ $t('issue_points') }}</v-btn>
                    </v-card-actions>
                </v-card>
            </v-form>
        </ValidationObserver>
    </v-dialog>
</div>
</template>

<script>
import {
    mask
} from "vue-the-mask";

export default {
    data: () => ({
        dialogIssuePoints: false,
        locale: "en",
        tab: "tab-0",
        tabImg: "about:blank",
        tabImgHeight: null,
        tabImgDefaultHeight: 780,
        tabImgAspectRation: 1.7778,
        tabImgs: [],
        msg: {
            points: 0,
            value: 0,
            customerNumber: ""
        },
        verifyCustomerNumberForm: {
            loading: false,
            customerNumber: ""
        },
        issuePointsForm: {
            loading: false,
            success: false,
            amount: ""
        },
        business: {
            currency: "USD",
            currency_step: 0.01,
            points_per_currency: null,
            point_value: null,
            points_expiration_months: null,
            min_points_per_purchase: null,
            max_points_per_purchase: null,
            min_points_per_redemption: null,
            max_points_per_redemption: null
        }
    }),
    directives: {
        mask
    },
    created() {
        // Set locale
        this.locale = this.$auth.check() ?
            this.$auth.user().locale :
            Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale;
        this.moment.locale(this.locale.substr(0, 2));

        // Tab images
        this.tabImgs = [
            this.$init.config.schemeAndHost + "/img/business-issue-tab.jpg"
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
        this.tabImgs.forEach(function (img, i) {
            images[i] = new Image();
            images[i].src = img;
        });

        // Query object
        this.verifyCustomerNumberForm.customerNumber = this.$route.query.c;

        this.axios
            .get("/business/settings", {
                params: {
                    locale: this.$i18n.locale
                }
            })
            .then(response => {
                let business = response.data.business;

                this.business.currency = business.currency;
                this.business.currency_step = business.currency_step;
                this.business.points_per_currency = business.points_per_currency;
                this.business.point_value = business.point_value;
                this.business.points_expiration_months =
                    business.points_expiration_months;
                this.business.min_points_per_purchase =
                    business.min_points_per_purchase;
                this.business.max_points_per_purchase =
                    business.max_points_per_purchase;
                this.business.min_points_per_redemption =
                    business.min_points_per_redemption;
                this.business.max_points_per_redemption =
                    business.max_points_per_redemption;
            });
    },
    methods: {
        formatNumber(number) {
            return new Intl.NumberFormat(this.locale.replace("_", "-")).format(
                number
            );
        },
        formatCurrency(number) {
            return new Intl.NumberFormat(this.locale.replace("_", "-"), {
                style: "currency",
                currency: this.business.currency
            }).format(number);
        },
        verifyCustomerNumber() {
            this.verifyCustomerNumberForm.loading = true;
            this.axios
                .post("/business/issue/verify-customer-number", {
                    locale: this.locale,
                    customerNumber: this.verifyCustomerNumberForm.customerNumber
                })
                .then(response => {
                    if (response.data.status === "success") {
                        this.$nextTick(() =>
                            this.$refs["verifyCustomerNumberForm"].reset()
                        );
                        this.dialogIssuePoints = true;
                    }
                })
                .catch(error => {
                    if (error.response.data.status === "error") {
                        this.errorMsg = error.response.data.error;

                        let errors = error.response.data.errors || [];

                        for (let field in errors) {
                            this.$refs["verifyCustomerNumberForm." + field].applyResult({
                                errors: errors[field],
                                valid: false,
                                failedRules: {}
                            });
                        }
                    }
                })
                .finally(() => {
                    this.verifyCustomerNumberForm.loading = false;
                });
        },
        issuePoints() {
            this.issuePointsForm.loading = true;
            this.axios
                .post("/business/issue/amount", {
                    locale: this.locale,
                    customerNumber: this.verifyCustomerNumberForm.customerNumber,
                    amount: this.issuePointsForm.amount
                })
                .then(response => {
                    if (response.data.status === "success") {
                        this.$nextTick(() => this.$refs["issuePointsForm"].reset());
                        this.dialogIssuePoints = false;
                        this.issuePointsForm.success = true;
                        this.verifyCustomerNumberForm.customerNumber = "";
                        this.issuePointsForm.amount = "";
                        this.msg.points = this.formatNumber(response.data.points);
                        this.msg.value = this.formatCurrency(response.data.value);
                        this.msg.customerNumber = response.data.customer_number;
                    }
                })
                .catch(error => {
                    if (error.response.data.status === "error") {
                        this.errorMsg = error.response.data.error;

                        let errors = error.response.data.errors || [];

                        for (let field in errors) {
                            this.$refs["issuePointsForm." + field].applyResult({
                                errors: errors[field],
                                valid: false,
                                failedRules: {}
                            });
                        }
                    }
                })
                .finally(() => {
                    this.issuePointsForm.loading = false;
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
    computed: {
        purchaseAmount: function () {
            return this.formatCurrency(this.issuePointsForm.amount);
        },
        pointsIssued: function () {
            let points =
                this.business.points_per_currency * this.issuePointsForm.amount;
            if (parseInt(points) < parseInt(this.business.min_points_per_purchase))
                points = this.business.min_points_per_purchase;
            if (parseInt(points) > parseInt(this.business.max_points_per_purchase))
                points = this.business.max_points_per_purchase;
            return this.formatNumber(points);
        }
    }
};
</script>
