<template>
<div class="viewContainer" v-resize="onResize">
    <v-container class="pa-0">
        <v-img :src="tabImg" :key="tabImg" :min-height="tabImgHeight" :aspect-ratio="tabImgAspectRation" transition="fade-transition">
            <v-container class="inner-container">
                <v-card class="mt-md-12 mt-0 content-tabs-wrapper" tile elevation="0">
                    <v-tabs :color="($store.state.app.dark) ? 'white' : 'black'" class="px-5 content-tabs" icons-and-text height="100" slider-size="4" show-arrows>
                        <!-- <v-tabs-slider></v-tabs-slider>
                        <v-tab :to="{ name: 'register' }" class="no-caps" :ripple="false">
                            {{ $t('customer') }}
                            <v-icon size="24">mdi-account-circle</v-icon>
                        </v-tab>
                        <v-tab :to="{ name: 'register-business' }" class="no-caps" :ripple="false">
                            {{ $t('business') }}
                            <v-icon size="24">mdi-briefcase-account</v-icon>
                        </v-tab> -->
                    </v-tabs>
                    <v-divider />
                </v-card>
                <v-tabs-items v-model="tab" style="background-color: transparent;">
                    <v-tab-item value="tab-0">
                        <v-container class="pa-0">
                            <v-row no-gutters>
                                <v-col cols="12" sm="9" md="6">
                                    <ValidationObserver ref="form1" v-slot="{ invalid }">
                                        <v-form :model="form1" @submit.prevent="submit('form1')" autocomplete="off" method="post">
                                            <v-card flat tile class="content-tabs-card pa-5 mb-0 mb-md-12">
                                                <v-card-text>
                                                    <h1 class="fg--text display-2 font-weight-medium mb-4" style="line-height: 4rem; max-width: 340px">{{ $t('registration_business_title') }}</h1>
                                                    <p class="body-1">{{ $t('registration_business_head') }}</p>
                                                    <v-alert :value="form1.hasError && !form1.success" type="error" tile class="mb-4">
                                                        <span v-if="form1.error == 'registration_validation_error'">{{ $t('server_error') }}</span>
                                                        <span v-else>{{ $t('correct_errors') }}</span>
                                                    </v-alert>
                                                    <x-text-field v-model="form1.business_name" ref="form1.business_name" id="form1.business_name" :label="$t('business_name')" :name="$t('business_name')" rules="required|min:2|max:32" dense />
                                                    <x-autocomplete v-model="form1.location" :items="locations" item-value="0" item-text="1" label="Location" name="location" rules="required" :persistent-hint="false" dense @change="enter_code"/>
                                                    <!-- <x-autocomplete
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
                            />-->
                                                    <v-row>
                                                        <v-col cols="12" sm="6" class="py-0">
                                                            <x-text-field v-model="form1.first_name" ref="form1.first_name" id="form1.first_name" :label="$t('first_name')" :name="$t('name')" rules="required|min:1|max:32" dense />
                                                        </v-col>
                                                        <v-col cols="12" sm="6" class="py-0">
                                                            <x-text-field v-model="form1.last_name" ref="form1.last_name" id="form1.last_name" :label="$t('last_name')" :name="$t('last_name')" rules="required|min:1|max:32" dense />
                                                        </v-col>
                                                    </v-row>
                                                    <x-text-field type="email" v-model="form1.email" ref="form1.email" id="form1.email" :label="$t('email')" :name="$t('email')" rules="required|max:64|email" dense />
                                                    <x-text-field :prefix="form1.country_code" v-model="form1.phone" ref="form1.phone" id="form1.phone" :label="`Contact Number`" :name="`phone`" rules="required|min:7|max:7" dense />
                                                    <x-autocomplete v-model="form1.cid" ref="form1.cid" id="form1.cid" :loading="loading2" :items="categories" item-value="cid" item-text="name" :label="'Business Category'" :name="'Business Category'" rules="required" dense />
                                                    <x-password v-model="form1.password" ref="form1.password" id="form1.password" :label="$t('password')" :name="$t('password')" rules="required|min:8|max:24" dense />
                                                    <v-btn color="ctaBg ctaFg--text" class="no-caps mb-3" x-large tile depressed block :loading="form1.loading" :disabled="form1.loading || invalid" type="submit">
                                                        {{ $t('sign_up') }}
                                                        <v-icon right>mdi-arrow-right</v-icon>
                                                    </v-btn>
                                                    <i18n path="sign_up_consent">
                                                        <template #terms_and_conditions>
                                                            <router-link :to="{name: 'terms'}">{{ $t('terms_and_conditions') }}</router-link>
                                                        </template>
                                                        <template #privacy_policy>
                                                            <router-link :to="{name: 'privacy'}">{{ $t('privacy_policy') }}</router-link>
                                                        </template>
                                                    </i18n>
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
        tab: "tab-0",
        tabImg: "about:blank",
        tabImgHeight: null,
        tabImgDefaultHeight: 780,
        tabImgAspectRation: 1.7778,
        tabImgs: [],
        currencies: [],
        categories: [],
        locations: [],
        loading2: true,
        form1: {
            loading: false,
            cid: "",
            terms: "",
            business_name: "",
            currency: "XCD",
            first_name: "",
            last_name: "",
            location: "",
            email: "",
            phone: "",
            password: "",
            locale: "",
            timezone: "",
            hasError: false,
            country_code: '',
            error: "",
            success: false
        }
    }),
    created() {
        this.locations = this.$_.toPairs({
            Anguilla: "Anguilla",
            "Antigua and Barbuda": "Antigua and Barbuda",
            Barbados: "Barbados",
            Dominica: "Dominica",
            Grenada: "Grenada",
            Guyana: "Guyana",
            Jamaica: "Jamaica",
            Montserrat: "Montserrat",
            "St. Kitts and Nevis": "St. Kitts and Nevis",
            "St. Lucia": "St. Lucia",
            "St. Vincent": "St. Vincent",
            Trinidad: "Trinidad",
            "Virgin Islands": "Virgin Islands"
        });
        this.form1.locale = "en";
        // Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale;
        this.form1.timezone =
            Intl.DateTimeFormat().resolvedOptions().timeZone || null;

        this.axios
            .get("/localization/currencies", {
                params: {
                    locale: this.$i18n.locale
                }
            })
            .then(response => {
                this.currencies = this.$_.toPairs(response.data);
            });

        this.axios.get("/category/all").then(res => {
            this.categories = res.data;
            this.loading2 = false;
        });

        // Tab images
        this.tabImgs = [
            this.$init.config.schemeAndHost + "/img/sign-up-business-tab.jpg"
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
    },
    methods: {
        enter_code() {
            if (this.form1.location == "Anguilla") {
                this.form1.country_code = '1264'

            } else if (this.form1.location == "Antigua and Barbuda") {
                this.form1.country_code = '1268'

            } else if (this.form1.location == "Dominica") {
                this.form1.country_code = '1767'

            } else if (this.form1.location == "Grenada") {
                this.form1.country_code = '1473'

            } else if (this.form1.location == "Montserrat") {
                this.form1.country_code = '1664'

            } else if (this.form1.location == "St. Kitts and Nevis") {
                this.form1.country_code = '1869'

            } else if (this.form1.location == "St. Lucia") {
                this.form1.country_code = '1758'

            } else if (this.form1.location == "St. Vincent") {
                this.form1.country_code = '1784'
            } else if (this.form1.location == "Barbados") {
                this.form1.country_code = '1246'
            } else if (this.form1.location == "Jamaica") {
                this.form1.country_code = '1876'
            } else if (this.form1.location == "Trinidad") {
                this.form1.country_code = '1868'
            } else if (this.form1.location == "Guyana") {
                this.form1.country_code = '1592'
            } else if (this.form1.location == "Virgin Islands") {
                this.form1.country_code = '1284'
            }

            // console.log(this.country_code, this.form1.location);

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
        },
        async submit(formName) {
            // Reset form validation
            this.$refs[formName].reset();

            // Form defaults
            let form = this[formName];
            form.hasError = false;
            form.loading = true;

            this.$auth.register({
                data: {
                    language: this.$i18n.locale,
                    type: "business",
                    business_name: form.business_name,
                    currency: form.currency,
                    cid: form.cid,
                    first_name: form.first_name,
                    last_name: form.last_name,
                    email: form.email,
                    phone: form.phone,
                    // whatsapp: form.whatsapp,
                    password: form.password,
                    locale: form.locale,
                    timezone: form.timezone,
                    location: form.location,
                    country_code: form.country_code,
                    terms: form.terms
                },
                success: function () {
                    form.success = true;

                    this.$auth.login({
                        rememberMe: true,
                        fetchUser: true,
                        params: {
                            locale: this.$i18n.locale,
                            location: form.location,
                            email: form.email,
                            whatsapp: form.whatsapp,
                            password: form.password,
                            country_code: form.country_code,
                            remember: true
                        },
                        success() {
                            let customer_type;
                            if (parseInt(this.$auth.user().role) == 2)
                                customer_type = "customer";
                            if (parseInt(this.$auth.user().role) == 3)
                                customer_type = "business";
                            if (parseInt(this.$auth.user().role) == 4)
                                customer_type = "staff";

                            // Handle cookies for mobile app
                            var date = new Date();
                            date.setTime(date.getTime() + 1 * 24 * 60 * 60 * 1000);
                            document.cookie =
                                "customer_id" +
                                "=" +
                                this.$auth.user().customer_number +
                                "; expires=" +
                                date.toGMTString();
                            document.cookie =
                                "customer_name" +
                                "=" +
                                (this.$auth.user().first_name +
                                    " " +
                                    this.$auth.user().last_name) +
                                "; expires=" +
                                date.toGMTString();
                            document.cookie =
                                "customer_type" +
                                "=" +
                                customer_type +
                                "; expires=" +
                                date.toGMTString();

                            // Handle redirection
                            let redirectTo;
                            if (this.$auth.user().role === 1) redirectTo = "admin.dashboard";
                            if (this.$auth.user().role === 2) redirectTo = "customer.earn";
                            if (this.$auth.user().role === 3)
                                redirectTo = "business.issue-points";
                            if (this.$auth.user().role === 4) redirectTo = "staff.dashboard";
                            // Redirect
                            this.$router.push({
                                name: redirectTo
                            });
                        }
                    });
                    //this.$router.push({name: 'login', params: {successRegistrationRedirect: true, email: form.email}})
                },
                error: function (error) {
                    let errors = error.response.data.errors || [];

                    for (let field in errors) {
                        this.$refs[formName + "." + field].applyResult({
                            errors: errors[field],
                            valid: false,
                            failedRules: {}
                        });
                    }

                    if (errors.length === 0) {
                        form.hasError = true;
                    }

                    form.loading = false;
                }
            });
        }
    },
    watch: {
        tab: function (val) {
            if (window.innerWidth > 760) {
                let index = val.split("-");
                this.tabImg = this.tabImgs[index[1]];
                this.tabImgHeight = this.tabImgDefaultHeight;
                this.tabImgAspectRation = 1.7778;
            }
        }
    }
};
</script>
