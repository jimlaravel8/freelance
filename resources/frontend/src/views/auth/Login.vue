<template>
<div class="viewContainer" v-resize="onResize">
    <v-container class="pa-0">
        <v-img :src="topBgImg" :key="topBgImg" :min-height="topBgImgHeight" :aspect-ratio="topBgImgAspectRation" transition="fade-transition">
            <v-container class="inner-container">
                <v-card class="mt-md-12 mt-0 content-tabs-wrapper" tile elevation="0">
                    <v-tabs :color="($store.state.app.dark) ? 'white' : 'black'" class="px-5 content-tabs" icons-and-text height="100" slider-size="4" show-arrows>
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
                                                    <h1 class="fg--text display-2 font-weight-medium mb-4" style="line-height: 4rem">{{ $t('sign_in') }}</h1>
                                                    <v-alert :value="form1.hasError" type="error" tile class="mb-4">
                                                        <span v-html="$t('login_not_recognized')" />
                                                    </v-alert>
                                                    <v-alert :value="successRegistrationRedirect" type="success" tile class="mb-4">{{ $t('successfully_registered_info') }}</v-alert>
                                                    <v-alert :value="successResetUpdateRedirect" type="success" tile class="mb-4">{{ $t('password_reset_success') }}</v-alert>
                                                    <div v-if="$init.config.demo">
                                                        <div class="mb-3 font-weight-bold">{{ $t('login_demo_mode') }}</div>
                                                        <div class="mb-8">
                                                            <v-btn color="ctaBg ctaFg--text" width="105" small depressed tile class="mr-2 no-caps" @click="form1.email='admin@example.com';form1.password='welcome123';submit('form1')">
                                                                {{ $t('admin') }}
                                                                <v-icon right size="14">mdi-arrow-right</v-icon>
                                                            </v-btn>
                                                            <v-btn color="ctaBg ctaFg--text" width="105" small depressed tile class="mr-2 no-caps" @click="form1.email='customer@example.com';form1.password='welcome123';submit('form1')">
                                                                {{ $t('customer') }}
                                                                <v-icon right size="14">mdi-arrow-right</v-icon>
                                                            </v-btn>
                                                            <v-btn color="ctaBg ctaFg--text" width="105" small depressed tile class="no-caps" @click="form1.email='business@example.com';form1.password='welcome123';submit('form1')">
                                                                {{ $t('business') }}
                                                                <v-icon right size="14">mdi-arrow-right</v-icon>
                                                            </v-btn>
                                                        </div>
                                                    </div>
                                                    <x-text-field type="email" v-model="form1.email" ref="form1.email" id="form1.email" :label="$t('email')" rules="required|email" dense v-if="email_login" />
                                                    <x-text-field type="number" v-model="form1.phone" ref="form1.phone" id="form1.phone" :label="$t('phone')" rules="required|min:11|max:11" dense v-else />

                                                    <div style="text-decoration: underline;cursor: pointer;" v-if="email_login" @click="email_login = !email_login">Login with phone number</div>
                                                    <div style="text-decoration: underline;cursor: pointer;" v-else @click="email_login = !email_login">Login with Email</div>

                                                    <x-password v-model="form1.password" ref="form1.password" id="form1.password" :label="$t('password')" rules="required|min:8|max:24" dense />
                                                    <x-checkbox v-model="form1.rememberMe" ref="form1.rememberMe" id="form1.rememberMe" :label="$t('remember_me')" dense />
                                                    <v-btn color="ctaBg ctaFg--text" class="no-caps mb-3" block x-large depressed tile :loading="form1.loading" :disabled="form1.loading || invalid" type="submit">
                                                        {{ $t('log_in') }}
                                                        <v-icon right>mdi-arrow-right</v-icon>
                                                    </v-btn>
                                                    <div>
                                                        {{ $t('dont_have_account') }}
                                                        <router-link :to="{name: 'register'}">{{ $t('sign_up') }}</router-link>
                                                    </div>
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
        topBgImg: "about:blank",
        topBgImgHeight: null,
        topBgImgDefaultHeight: 780,
        topBgImgAspectRation: 1.7778,
        topBgImgs: [],
        successRegistrationRedirect: false,
        successResetUpdateRedirect: false,
        email_login: true,
        form1: {
            loading: false,
            email: "",
            phone: "",
            password: "",
            rememberMe: true,
            hasError: false
        }
    }),
    created() {
        this.successRegistrationRedirect =
            this.$route.params.successRegistrationRedirect || false;
        this.form1.email = this.$route.params.email || null;
        this.successResetUpdateRedirect =
            this.$route.params.successResetUpdateRedirect || false;

        // Tab images
        this.topBgImgs = [this.$init.config.schemeAndHost + "/img/login-tab.jpg"];

        // Set image depending on the size of the browser window
        if (window.innerWidth > 760) {
            this.topBgImg = this.topBgImgs[0];
            this.topBgImgHeight = this.topBgImgDefaultHeight;
            this.topBgImgAspectRation = 1.7778;
        } else {
            this.topBgImgHeight = null;
            this.topBgImgAspectRation = 3;
        }
    },
    methods: {
        onResize() {
            if (window.innerWidth <= 760) {
                this.topBgImg =
                    "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=";
                this.topBgImgHeight = null;
                this.topBgImgAspectRation = 3;
            } else if (
                this.topBgImg ==
                "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="
            ) {
                this.topBgImg = this.topBgImgs[0];
                this.topBgImgHeight = this.topBgImgDefaultHeight;
                this.topBgImgAspectRation = 1.7778;
            }
        },
        async submit(formName) {
            // Get the redirect object
            let redirect = this.$auth.redirect();

            // Reset form validation
            this.$refs[formName].reset();

            // Form defaults
            let form = this[formName];
            form.hasError = false;
            form.loading = true;

            var params = {}
            if (this.email_login) {
                params = {
                    locale: this.$i18n.locale,
                    email: form.email,
                    field: 'email',
                    password: form.password,
                    remember: form.rememberMe
                }

            } else {
                params = {
                    locale: this.$i18n.locale,
                    field: 'phone',
                    phone: form.phone,
                    password: form.password,
                    remember: form.rememberMe
                }

            }
            this.$auth.login({
                rememberMe: form.rememberMe,
                fetchUser: true,
                params: params,
                success() {
                    let customer_type;
                    if (parseInt(this.$auth.user().role) == 2) customer_type = "customer";
                    if (parseInt(this.$auth.user().role) == 3) customer_type = "business";
                    if (parseInt(this.$auth.user().role) == 4) customer_type = "staff";

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
                        (this.$auth.user().first_name + " " + this.$auth.user().last_name) +
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

                    if (parseInt(this.$auth.user().role) == 1)
                        redirectTo = "admin.dashboard";
                    if (parseInt(this.$auth.user().role) == 2)
                        redirectTo = "customer.earn";
                    if (parseInt(this.$auth.user().role) == 3)
                        redirectTo = "business.issue-points";
                    if (parseInt(this.$auth.user().role) == 4)
                        redirectTo = "business.issue-points";

                    // Redirect
                    this.$router.push({
                        name: redirect ? redirect.from.name : redirectTo,
                        query: redirect ? redirect.from.query : null
                    });
                },
                error: function (error) {
                    form.loading = false;
                    console.log(error);

                    // if (error.response.status === 500) {
                    //     eventBus.$emit('errorEvent', error.response.statusText)
                    //     return
                    // } else if (error.response.status === 401 || error.response.status === 409) {
                    //     eventBus.$emit('reloadRequest', error.response.statusText)
                    // } else if (error.response.status === 422) {
                    //     eventBus.$emit('errorEvent', error.response.data.message + ': ' + error.response.statusText)
                    //     context.commit('errors', error.response.data.errors)
                    //     return
                    // }

                    window.scrollTo(0, 0);
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
                this.topBgImg = this.topBgImgs[0];
                this.topBgImgHeight = this.topBgImgDefaultHeight;
                this.topBgImgAspectRation = 1.7778;
            }
        }
    }
};
</script>
