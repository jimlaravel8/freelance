<template>
<v-app>
    <!-- <v-navigation-drawer v-model="drawer" fixed left floating temporary>
      <template v-slot:prepend>
        <div class="pa-4 d-flex" style="right: 0; position: absolute">
          <v-divider vertical class="mx-2" />
          <v-app-bar-nav-icon small @click.stop="drawer = !drawer">
            <v-icon small v-if="!drawer">mdi-minus</v-icon>
            <v-icon small v-if="drawer">mdi-close</v-icon>
          </v-app-bar-nav-icon>
        </div>
      </template>
      <h3 class="title ma-4" v-html="$t('navigate')"></h3>
      <v-list class="ma-0 pa-0">
        <v-list-item link exact :to="{name: 'home'}">
          <v-list-item-content>
            <v-list-item-title>{{ $t('home') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link exact :to="{name: 'about'}">
          <v-list-item-content>
            <v-list-item-title>{{ $t('about') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <v-list-item link exact :to="{name: 'login'}" v-if="!$auth.check()">
          <v-list-item-content>
            <v-list-item-title>{{ $t('log_in') }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>-->
    <v-app-bar app clipped-right elevate-on-scroll style="z-index:6" color="NavBg NavFg--text">
        <v-container class="inner-container">
            <v-row no-gutters>
                <!-- <div class="d-sm-none d-md-none d-lg-none d-xl-none d-flex align-center mr-3">
            <v-app-bar-nav-icon @click.stop="drawer = !drawer" color="NavFg">
              <v-icon>mdi-menu</v-icon>
            </v-app-bar-nav-icon>
          </div>-->
                <div class="d-flex align-center">
                    <v-img width="45" :src="$init.app.logo" />
                    <!-- <router-link v-if="$init.app.logo != '' && $init.app.logo != null" :to="{name: 'home'}">
              <v-img width="144" :src="$init.app.logo" />
            </router-link>-->
                    <router-link v-if="$init.app.logo == '' || $init.app.logo == null" :to="{name: 'home'}" style="max-width: 156px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" class="subtitle-1 font-weight-medium white--text text-decoration-none" v-html="$init.app.name"></router-link>
                </div>
                <v-toolbar-items class="d-none d-sm-flex d-md-flex d-lg-flex d-xl-flex navigation ml-3">
                    <v-btn v-for="(item, i) in topMenuLeft" :key="'nav_' + i" text exact color="NavFg--text" :dark="true" :ripple="false" v-show="(item.showIfLoggedIn === true && $auth.check()) || (item.showIfNotLoggedIn === true && !$auth.check())" :to="(item.to) ? item.to : null" @click="(item.isLogout) ? $auth.logout() : null" class="no-caps subtitle-2">{{ item.label }}</v-btn>
                </v-toolbar-items>
                <v-spacer></v-spacer>
                <div class="align-center d-none d-sm-flex d-md-flex d-lg-flex d-xl-flex">
                    <!-- <v-btn icon small color="NavFg--text" :dark="true" @click="switchDarkTheme">
              <v-icon v-if="$vuetify.theme.dark" small>mdi-lightbulb</v-icon>
              <v-icon v-if="!$vuetify.theme.dark" small>mdi-lightbulb-on-outline</v-icon>
            </v-btn>-->
                </div>
                <v-toolbar-items class="d-flex align-center navigation" v-if="!$auth.check()">
                    <v-btn text exact color="NavFg--text" :dark="true" :ripple="false" :to="{ name: 'login' }" class="no-caps subtitle-2 d-none d-sm-flex d-md-flex d-lg-flex d-xl-flex">{{ $t('log_in') }}</v-btn>
                </v-toolbar-items>
                <v-menu v-if="$auth.check()" offset-y bottom left origin="top right" :close-on-content-click="true" tile>
                    <template v-slot:activator="{ on }">
                        <div class="d-flex align-center">
                            <!-- <v-btn
                  v-on="on"
                  tile
                  depressed
                  color="NavFg NavBg--text"
                  :dark="true"
                  :ripple="false"
                  class="no-caps subtitle-2 ml-3"
                >{{ $t('menu') }}</v-btn>-->
                            <v-app-bar-nav-icon v-on="on" color="NavFg">
                                <v-icon>mdi-menu</v-icon>
                            </v-app-bar-nav-icon>
                        </div>
                    </template>
                    <v-card tile max-width="460">
                        <v-list dense tile class="pt-0">
                            <v-list-item class="px-3 py-2">
                                <v-list-item-avatar>
                                    <img :src="$auth.user().avatar" />
                                </v-list-item-avatar>

                                <v-list-item-content>
                                    <v-list-item-title v-html="$auth.user().first_name + ' ' + $auth.user().last_name" />
                                    <v-list-item-subtitle v-html="$auth.user().email" />
                                </v-list-item-content>
                            </v-list-item>
                            <v-divider />
                            <v-list-item-group>
                                <template v-for="(item, index) in dashboardMenu">
                                    <v-layout v-if="item.heading" :key="item.heading">
                                        <v-subheader class="text-uppercase">{{ item.heading }}</v-subheader>
                                    </v-layout>

                                    <v-list-group v-else-if="item.children" :key="'nav_parent_' + index" :value="item.opened" no-action :sub-group="false" :prepend-icon="item.icon">
                                        <template #activator>
                                            <v-list-item-content>
                                                <v-list-item-title>{{ item.label }}</v-list-item-title>
                                            </v-list-item-content>
                                        </template>

                                        <v-list-item v-for="(child, i) in item.children" :key="'nav_child_' + i" :to="child.to" exact>
                                            <v-list-item-icon v-if="child.icon">
                                                <v-icon>{{ child.icon }}</v-icon>
                                            </v-list-item-icon>
                                            <v-list-item-content>
                                                <v-list-item-title>{{ child.label }}</v-list-item-title>
                                            </v-list-item-content>
                                        </v-list-item>
                                    </v-list-group>

                                    <v-list-item v-else :key="'nav_sub_' + index" :to="item.to" exact>
                                        <v-list-item-icon v-if="item.icon">
                                            <v-icon>{{ item.icon }}</v-icon>
                                        </v-list-item-icon>
                                        <v-list-item-content v-if="item.label">
                                            <v-list-item-title>{{ item.label }}</v-list-item-title>
                                        </v-list-item-content>
                                        <v-list-item-content v-if="item.name">
                                            <v-list-item-title>{{ item.name }}</v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </template>
                                <v-divider class="my-2" />
                                <v-list-item v-if="!$auth.impersonating()" @click="$auth.logout()">
                                    <v-list-item-icon>
                                        <v-icon>mdi-power</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>{{ $t('logout') }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item v-if="$auth.impersonating() && parseInt($auth.user().role) != 4" @click="$auth.unimpersonate({redirect: {name: 'admin.users'}})">
                                    <v-list-item-icon>
                                        <v-icon>mdi-power</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>{{ $t('logout') }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                                <v-list-item v-if="$auth.impersonating() && parseInt($auth.user().role) == 4" @click="$auth.unimpersonate({redirect: {name: 'business.staff'}})">
                                    <v-list-item-icon>
                                        <v-icon>mdi-power</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content>
                                        <v-list-item-title>{{ $t('logout') }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>
                    </v-card>
                </v-menu>
                <!-- <myNotifications :notifications="notifications" /> -->
                <!-- <v-spacer></v-spacer> -->
                <v-menu v-if="$auth.check() && $auth.user().role == 2" offset-y bottom left origin="top right" :close-on-content-click="true" tile>
                    <template v-slot:activator="{ on }">
                        <div class="d-flex align-center">
                            <v-app-bar-nav-icon v-on="on" color="NavFg" @click="markAsRead">
                                <!-- <v-icon>mdi-bell</v-icon> -->
                                <v-badge :value="hover" color="black" :content="notification_count" right transition="slide-x-transition">
                                    <v-icon>mdi-bell</v-icon>
                                </v-badge>
                            </v-app-bar-nav-icon>
                        </div>
                    </template>
                    <v-card tile max-width="460">
                        <v-list dense tile class="pt-0">
                            <v-list-item-group>
                                <v-layout>
                                    <v-subheader class="text-uppercase">Notifications</v-subheader>
                                </v-layout>
                                <v-divider></v-divider>
                                <template v-for="(item, index) in notifications">
                                    <v-list-item exact :key="item.id">
                                        <v-list-item-content>
                                            <v-list-item-subtitle>
                                                {{ item.data.notification }} | <b> {{ item.date }}</b>
                                            </v-list-item-subtitle>

                                            <!-- <v-list-item-title>
                                                {{ item.data.notification }} | <b> {{ item.date }}</b>
                                            </v-list-item-title> -->
                                        </v-list-item-content>

                                    </v-list-item>
                                    <v-divider v-if="index + 1 < notifications.length" :key="index"></v-divider>
                                </template>
                            </v-list-item-group>
                        </v-list>
                    </v-card>
                </v-menu>
                <!-- <v-menu v-if="$auth.check()" offset-y bottom left origin="top right" :close-on-content-click="false" tile v-model="menu">
                    <template v-slot:activator="{ on, attrs }">
                        <v-app-bar-nav-icon v-on="on" color="NavFg" v-bind="attrs" style="margin-top: 6px;">
                            <v-badge :value="hover" color="black" :content="notifications.length" right transition="slide-x-transition">
                                <v-icon>mdi-bell</v-icon>
                            </v-badge>
                        </v-app-bar-nav-icon>
                    </template>

                    <v-card>
                        <v-list dense>
                            <v-subheader>Notifications</v-subheader>
                            <v-list-item-group color="primary">
                                <v-list-item v-for="notification in notifications" :key="notification.id">
                                    <v-list-item-content>
                                        <v-list-item-title>
                                            {{ notification.data.notification }} | <b> {{ notification.date }}</b>

                                        </v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </v-list-item-group>
                        </v-list>

                    </v-card>
                </v-menu> -->

                <!-- <v-btn text icon color="primary" @click="getNotifications">
                    <v-icon>mdi-refresh</v-icon>
                </v-btn> -->

                <!-- computer -->
                <div class="d-none d-sm-flex d-md-flex d-lg-flex d-xl-flex align-center ml-2" v-if="!$auth.check()">
                    <v-btn color="NavFg NavBg--text" depressed tile class="no-caps" :to="{name: 'register'}">
                        <span>{{ 'Register' }}</span>
                    </v-btn>
                </div>
                <!-- phone -->
                <div class="d-flex d-sm-none d-md-none d-lg-none d-xl-none align-center ml-2" v-if="!$auth.check()">
                    <v-btn color="NavFg NavBg--text" depressed tile class="no-caps" :to="{name: pathname == 'register' ? 'login' : 'register'}">
                        <v-icon class="d-flex">{{(this.pathname == 'register' || this.pathname == 'register-business') ? 'mdi-account' : 'mdi-pencil-outline'}}</v-icon>
                    </v-btn>
                </div>
            </v-row>
        </v-container>
    </v-app-bar>
    <v-content>
        <router-view name="primary"></router-view>
    </v-content>
    <v-footer color="NavBg" v-if="showFooter">
        <v-container class="inner-container">
            <v-row no-gutters>
                <v-col cols="6" xs="6" lg="3">
                    <h3 class="title NavFg--text my-5" v-html="$t('navigate')"></h3>
                    <ul class="nav-list">
                        <li>
                            <router-link :to="{name: 'home'}" class="NavFg--text text-decoration-none">{{ $t('home') }}</router-link>
                        </li>
                        <li>
                            <router-link :to="{name: 'about'}" class="NavFg--text text-decoration-none">{{ $t('about') }}</router-link>
                        </li>
                        <li v-if="!$auth.check()">
                            <router-link :to="{name: 'login'}" class="NavFg--text text-decoration-none">{{ $t('log_in') }}</router-link>
                        </li>
                        <li v-if="!$auth.check()">
                            <router-link :to="{name: 'register'}" class="NavFg--text text-decoration-none">{{ $t('sign_up') }}</router-link>
                        </li>
                        <li v-if="$auth.check() && $auth.user().role == 1">
                            <router-link :to="{name: 'admin.dashboard'}" class="NavFg--text text-decoration-none">{{ $t('dashboard') }}</router-link>
                        </li>
                        <li v-if="$auth.check() && $auth.user().role == 2">
                            <router-link :to="{name: 'customer.earn'}" class="NavFg--text text-decoration-none">{{ $t('dashboard') }}</router-link>
                        </li>
                        <li v-if="$auth.check() && $auth.user().role == 3">
                            <router-link :to="{name: 'business.issue-points'}" class="NavFg--text text-decoration-none">{{ $t('dashboard') }}</router-link>
                        </li>
                        <li v-if="$auth.check() && $auth.user().role == 4">
                            <router-link :to="{name: 'staff.dashboard'}" class="NavFg--text text-decoration-none">{{ $t('dashboard') }}</router-link>
                        </li>
                    </ul>
                </v-col>
                <v-col cols="6" xs="6" lg="3">
                    <h3 class="title NavFg--text my-5" v-html="$t('legal')"></h3>
                    <ul class="nav-list">
                        <li>
                            <router-link :to="{name: 'terms'}" class="NavFg--text text-decoration-none">{{ $t('terms_and_conditions') }}</router-link>
                        </li>
                        <li>
                            <router-link :to="{name: 'privacy'}" class="NavFg--text text-decoration-none">{{ $t('privacy_policy') }}</router-link>
                        </li>
                    </ul>
                </v-col>
                <v-col cols="6" xs="6" lg="3">
                    <h3 class="title NavFg--text my-5" v-html="$t('contact')"></h3>
                    <ul class="nav-list">
                        <li>
                            <router-link :to="{name: 'contact'}" class="NavFg--text text-decoration-none">{{ $t('contact_us') }}</router-link>
                        </li>
                    </ul>
                </v-col>
                <v-col cols="6" xs="6" lg="3">
                    <div v-if="$init.app.social.length > 0">
                        <h3 class="title NavFg--text my-5" v-html="$t('follow_us')"></h3>

                        <v-btn v-for="(item, i) in $init.app.social" :key="'social_' + i" icon color="NavFg" :href="item.url" target="_blank">
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-btn>
                    </div>

                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" xs="6" lg="6" class="offset-sm-6">
                    <a href="https://apps.apple.com/us/app/piggy-bank-svg/id1502265988" target="_blank">
                        <img src="/img/store/appstore.png" />
                    </a>
                </v-col>
                <v-col cols="12" xs="6" lg="6" class="offset-sm-6">
                    <a href="https://play.google.com/store/apps/details?id=com.piggy" target="_blank">
                        <img src="/img/store/playstore.png" />
                    </a>
                </v-col>
            </v-row>
            <v-row no-gutters>
                <v-col class="text-center secondary--text body-2 pt-10 pb-5">
                    <!-- <v-divider class="mb-5" dark /> -->
                    <div v-html="$t('footer_copyright')" />
                </v-col>
            </v-row>
        </v-container>
    </v-footer>
    <!-- <v-snackbar
      v-model="showCookieConsent"
      :multi-line="true"
      :timeout="0"
      :bottom="true"
      :vertical="false"
      tile
      class="termsConsent"
    >
      <i18n path="legal_agreement_confirmation">
        <template #terms_and_conditions>
          <router-link
            :to="{name: 'terms'}"
            class="white--text"
          >{{ $t('terms_and_conditions').toLowerCase() }}</router-link>
        </template>
        <template #privacy_policy>
          <router-link
            :to="{name: 'privacy'}"
            class="white--text"
          >{{ $t('privacy_policy').toLowerCase() }}</router-link>
        </template>
      </i18n>
      <v-btn dark text icon depressed @click="$store.dispatch('setCookieConsent', false)">
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </v-snackbar>-->
    <confirm ref="confirm"></confirm>
    <snackbar ref="snackbar"></snackbar>
    <v-overlay opacity="1" :dark="$store.state.app.dark" :color="($store.state.app.dark) ? 'grey darken-4' : 'grey lighten-5'" :value="$store.state.app.loading" z-index="99999">
        <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
</v-app>
</template>

<script>
// import myNotifications from './Clean'
import {
    getAvailableLanguages,
    loadLanguageAsync
} from "@/plugins/i18n";
import axios from "axios";

export default {
    components: {
        // myNotifications
    },
    data: () => ({
        locale: "en",
        drawer: false,
        languages: null,
        pathname: "",
        screenWidth: "",
        gobackBtn: "",
        menu: false,
        hover: true,
        notifications: [],
        notification_count: 0
        // businesses: []
    }),
    beforeMount() {
        this.pathname = this.$route.name;
        this.screenWidth = window.innerWidth;
    },
    mounted() {
        this.$vuetify.theme.dark = this.$store.state.app.dark;
        this.$root.$snackbar = this.$refs.snackbar.show;
        this.$root.$confirm = this.$refs.confirm.open;
        this.getNotifications()
        /* Get available translations */
        getAvailableLanguages().then(result => (this.languages = result));

        /* Set language */
        let language = this.$route.query.l || null;
        if (language !== null) {
            loadLanguageAsync(language);
        }

        // Set locale
        let locale = Intl.DateTimeFormat().resolvedOptions().locale || "en";
        locale = this.$auth.check() ? this.$auth.user().locale : locale;
        this.locale = locale;
        this.moment.locale(this.locale.substr(0, 2));
    },
    methods: {
        markAsRead() {
            this.axios.post("/notifications")
                .then(response => {
                    console.log(response);
                    this.getNotifications()
                }).catch((error) => {
                    console.log(error);
                });
        },
        switchDarkTheme() {
            let dark = this.$vuetify.theme.dark;
            this.$vuetify.theme.dark = !dark;
            this.$store.dispatch("setDark", !dark);
        },
        changeLang(language) {
            loadLanguageAsync(language);
            this.$store.dispatch("setLanguage", language);
        },
        formatNumber(number) {
            return new Intl.NumberFormat(this.locale.replace("_", "-")).format(
                number
            );
        },
        getNotifications() {
            this.axios
                .get("/notifications", {
                    params: {
                        locale: this.$i18n.locale
                    }
                })
                .then(response => {
                    this.notifications = response.data.notifications
                    this.notification_count = response.data.notification_count
                    // this.locales = this.$_.toPairs(response.data);
                    this.locales = this.$_.toPairs({
                        en: "English",
                        en_US: "English (United States)",
                        en_GB: "English (United Kingdom)"
                    });
                });
        }
        // async getBusinesses() {
        //   const body = await this.axios(`/customer/businesses`);
        //   const data = body.data;

        //   const businesses = [];
        //   data.map(e => {
        //     businesses.push({
        //       label: e.name,
        //       icon: "mdi-menu-right"
        //     });
        //   });
        //   this.businesses = businesses;
        // }
    },
    watch: {
        "$route.name": function (newone, oldone) {
            this.pathname = newone;
            this.gobackBtn = oldone;
        }
    },
    computed: {
        showFooter() {
            this.pathname;
            if (this.$auth.check()) {
                // user is logged in, so don't show the footer.
                return false;
            } else {
                // user isn't logged in but he is on one of the following pages, so don't show the footer.
                return (
                    this.pathname !== "login" &&
                    this.pathname !== "register" &&
                    this.pathname !== "register-business" &&
                    this.pathname !== "privacy" &&
                    this.pathname !== "terms" &&
                    this.pathname !== "password.email"
                    // this.pathname !== "contact"
                );
            }
        },
        // showCookieConsent() {
        //   return this.$store.state.app.showCookieConsent;
        // },
        topMenuLeft() {
            return [{
                label: this.$t("about"),
                to: {
                    name: "about"
                },
                showIfLoggedIn: true,
                showIfNotLoggedIn: true
            }];
        },
        dashboardMenu() {
            let menu = [];
            if (this.$auth.check()) {
                if (this.$auth.user().role === 1) {
                    menu = [{
                            heading: this.$t("admin")
                        },
                        {
                            label: this.$t("dashboard"),
                            icon: "mdi-view-dashboard",
                            to: {
                                name: "admin.dashboard"
                            }
                        },
                        {
                            label: this.$t("users"),
                            icon: "mdi-account-multiple",
                            to: {
                                name: "admin.users"
                            }
                        },
                        {
                            heading: this.$t("settings")
                        },
                        {
                            label: this.$t("profile"),
                            icon: "mdi-account-circle",
                            to: {
                                name: "settings.profile"
                            }
                        }, {
                            label: this.$t('get_help'),
                            icon: 'mdi-help',
                            to: {
                                name: 'contact'
                            }
                        },
                        {
                            label: "Businesses",
                            icon: "mdi-city",
                            to: {
                                name: "admin.categories"
                            }
                        }
                    ];
                } else if (this.$auth.user().role === 2) {
                    /* user */
                    menu = [{
                            heading: this.$t("loyalty_points")
                        },
                        {
                            label: this.$t("earn_points"),
                            icon: "mdi-coins",
                            to: {
                                name: "customer.earn"
                            }
                        },
                        {
                            label: this.$t("wallet"),
                            icon: "mdi-wallet-outline",
                            to: {
                                name: "customer.wallet"
                            }
                        },
                        {
                            label: "Businesses",
                            icon: "mdi-city",
                            to: {
                                name: "customer.businesses"
                            }
                        },
                        {
                            heading: this.$t("settings")
                        },
                        {
                            label: this.$t("profile"),
                            icon: "mdi-account-circle",
                            to: {
                                name: "settings.profile"
                            }
                        }, {
                            label: this.$t('get_help'),
                            icon: 'mdi-help',
                            to: {
                                name: 'contact'
                            }
                        }
                    ];
                } else if (this.$auth.user().role === 3) {
                    menu = [{
                            heading: this.$t("loyalty_points")
                        },
                        {
                            label: this.$t("issue_points"),
                            icon: "mdi-coins",
                            to: {
                                name: "business.issue-points"
                            }
                        },
                        {
                            label: this.$t("redeem_points"),
                            icon: "mdi-wallet-giftcard",
                            to: {
                                name: "business.redeem-points"
                            }
                        },
                        {
                            label: this.$t("transactions"),
                            icon: "mdi-format-list-bulleted",
                            to: {
                                name: "business.transactions"
                            }
                        },
                        {
                            heading: this.$t("management")
                        },
                        {
                            label: this.$t("staff"),
                            icon: "mdi-account-tie-outline",
                            to: {
                                name: "business.staff"
                            }
                        },
                        {
                            label: this.$t("rules"),
                            icon: "mdi-cards-playing-outline",
                            to: {
                                name: "business.rules"
                            }
                        },
                        {
                            heading: this.$t("settings")
                        },
                        {
                            label: this.$t("profile"),
                            icon: "mdi-account-circle",
                            to: {
                                name: "settings.profile"
                            }
                        }, {
                            label: this.$t('get_help'),
                            icon: 'mdi-help',
                            to: {
                                name: 'contact'
                            }
                        },
                        {
                            label: this.$t("business"),
                            icon: "mdi-briefcase",
                            to: {
                                name: "settings.business"
                            }
                        },
                        {
                            label: "Billing",
                            icon: "mdi-domain",
                            to: {
                                name: "settings.business.subscription"
                            }
                        }, {
                            label: this.$t('business_promo'),
                            icon: 'mdi-account-arrow-right',
                            to: {
                                name: 'business.promo'
                            }
                        }
                    ];
                } else if (this.$auth.user().role === 4) {
                    menu = [{
                            label: this.$t("issue_points"),
                            icon: "mdi-coins",
                            to: {
                                name: "business.issue-points"
                            }
                        },
                        {
                            label: this.$t("redeem_points"),
                            icon: "mdi-wallet-giftcard",
                            to: {
                                name: "business.redeem-points"
                            }
                        },
                        {
                            label: this.$t("transactions"),
                            icon: "mdi-format-list-bulleted",
                            to: {
                                name: "business.transactions"
                            }
                        },
                        {
                            heading: this.$t("settings")
                        },
                        {
                            label: this.$t("profile"),
                            icon: "mdi-account-circle",
                            to: {
                                name: "settings.profile"
                            }
                        }, {
                            label: this.$t('get_help'),
                            icon: 'mdi-help',
                            to: {
                                name: 'contact'
                            }
                        }
                    ];
                }
            }
            return menu;
        }
    }
};
</script>

<style scoped>
.v-application--is-ltr .v-list-item__action:first-child,
.v-application--is-ltr .v-list-item__icon:first-child {
    margin-right: 12px;
}

.v-menu__content--fixed {
    max-height: 100vh !important;
}

.v-list-item__subtitle,
.v-list-item__title {
    white-space: break-spaces !important;
}
</style>
