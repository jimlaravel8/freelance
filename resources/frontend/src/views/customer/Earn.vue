<template>
<div class="viewContainer" v-resize="onResize" id="earn">
    <v-container class="pa-0">
        <v-img :src="tabImg" :key="tabImg" :min-height="tabImgHeight" :aspect-ratio="tabImgAspectRation" transition="fade-transition">
            <v-container class="inner-container">
                <v-card class="mt-md-12 mt-0 content-tabs-wrapper" tile elevation="0">
                    <v-tabs :color="($store.state.app.dark) ? 'white' : 'black'" class="px-5 content-tabs" icons-and-text height="100" slider-size="4" show-arrows>
                        <v-tabs-slider></v-tabs-slider>
                        <v-tab :to="{ name: 'customer.earn' }" class="no-caps" :ripple="false">
                            {{ $t('earn') }}
                            <v-icon size="24">mdi-coins</v-icon>
                        </v-tab>
                        <v-tab :to="{ name: 'customer.wallet' }" class="no-caps" :ripple="false">
                            {{ $t('wallet') }}
                            <v-icon size="24">mdi-wallet-outline</v-icon>
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
                                        <!-- <v-card-title primary-title> -->
                                        <!-- <v-rating v-model="ratings" readonly>
                                                <template v-slot:item="props">
                                                    <v-icon :color="props.isFilled ? genColor(props.index) : 'grey lighten-1'" large @click="props.click">
                                                        {{ props.isFilled ? 'mdi-star-circle' : 'mdi-circle-outline' }}
                                                    </v-icon>
                                                </template>
                                            </v-rating> -->

                                        <!-- <v-tooltip bottom v-model="show">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-rating v-model="rating" v-bind="attrs" v-on="on">
                                                    <template v-slot:item="props">
                                                        <v-icon :color="props.isFilled ? genColor(props.index) : 'grey lighten-1'" large @click="show = !show">
                                                            {{ props.isFilled ? 'mdi-star-circle' : 'mdi-circle-outline' }}
                                                        </v-icon>
                                                    </template>
                                                </v-rating>
                                            </template>
                                            <span>Programmatic tooltip</span>
                                        </v-tooltip> -->

                                        <v-tooltip bottom v-model="show" max-width="340" color="NavBg NagFg--text">
                                            <template v-slot:activator="{ on, attrs }">
                                                <!-- <v-btn icon v-bind="attrs" v-on="on"> -->
                                                <v-rating v-model="ratings" v-bind="attrs" v-on="on">
                                                    <template v-slot:item="props">
                                                        <v-icon :color="props.isFilled ? genColor(props.index) : 'grey lighten-1'" large @click="show = !show">
                                                            {{ props.isFilled ? 'mdi-star-circle' : 'mdi-star-outline' }}
                                                        </v-icon>
                                                    </template>
                                                </v-rating>
                                                <!-- </v-btn> -->
                                            </template>
                                            <span>Earn stars based on the number of times you earn points. These are the levels: 1 Star: 0-25, 2 Stars: 26-50, 3 Stars: 51-100, 4 Stars: 101-200,5 Stars 201 and above.</span>
                                        </v-tooltip>

                                        <!-- <v-tooltip v-model="show" bottom max-width="340" color="NavBg NagFg--text">
                                            <template v-slot:activator="{ on }">
                                                <v-rating v-model="ratings" readonly v-on="on">
                                                    <template v-slot:item="props">
                                                        <v-icon :color="props.isFilled ? genColor(props.index) : 'grey lighten-1'" size="30" @click="props.click">
                                                            {{ props.isFilled ? 'mdi-star-circle' : 'mdi-circle-outline' }}
                                                        </v-icon>
                                                    </template>
                                                </v-rating>
                                            </template>
                                            <span>Earn stars based on the number of times you earn points. These are the levels: 1 Star: 0-25, 2 Stars: 26-50, 3 Stars: 51-100, 4 Stars: 101-200,5 Stars 201 and above.</span>

                                        </v-tooltip> -->
                                        <!-- </v-card-title> -->
                                        <v-card-text>
                                            <p class="mb-0 fg--text subtitle-1">
                                                {{ $t('earn_points_head') }}
                                                <v-tooltip top max-width="340" color="NavBg NagFg--text">
                                                    <template v-slot:activator="{ on }">
                                                        <v-icon style="margin-top:-2px;" color="ctaBg" v-on="on">mdi-help-circle-outline</v-icon>
                                                    </template>
                                                    <span v-html="$t('earn_points_info')"></span>
                                                </v-tooltip>
                                            </p>
                                            <div class="mt-5">
                                                <v-text-field type="text" class="title" outlined readonly id="customerNumber" :value="$auth.user().phone_no" append-icon="mdi-content-copy" @click:append="copyStringToClipboard($auth.user().phone_no)"></v-text-field>
                                            </div>
                                            <div style="text-align:center !important">
                                                <qrcode class="mt-0" :value="$init.config.schemeAndHost + '/business/issue-points?c=' + $auth.user().phone_no" :options="{ marginLeft: 10, width: 220, errorCorrectionLevel: 'H', color: { light: '#ffffff00', dark: ($vuetify.theme.dark) ? '#ffffff' : '#000000' } }"></qrcode>
                                            </div>
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
</div>
</template>

<script>
import {
    copyStringToClipboard
} from "@/utils/helpers";

export default {
    data: () => ({
        locale: "en",
        tab: "tab-0",
        tabImg: "about:blank",
        tabImgHeight: null,
        tabImgDefaultHeight: 780,
        tabImgAspectRation: 1.7778,
        tabImgs: [],
        colors: ['green', 'purple', 'orange', 'indigo', 'red'],
        ratings: 0,
        show: false,

    }),
    created() {
        // Set locale
        this.locale = this.$auth.check() ?
            this.$auth.user().locale :
            Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale;
        this.moment.locale(this.locale.substr(0, 2));

        // Tab images
        this.tabImgs = [
            this.$init.config.schemeAndHost + "/img/customer-earn-tab.jpg"
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
    mounted() {
        this.getRatings();
    },
    methods: {
        genColor(i) {
            return this.colors[i]
        },
        getRatings() {
            this.axios.get("/customer/customerBadge", {
                    params: {
                        locale: this.$i18n.locale
                    }
                })
                .then(response => {
                    this.ratings = response.data
                }).catch((error) => {
                    console.log(error);
                });
        },
        copyStringToClipboard,
        formatNumber(number) {
            return new Intl.NumberFormat(this.locale.replace("_", "-")).format(
                number
            );
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
    }
};
</script>

<style scoped>
/* #earn .text--lighten-1 {
    font-size: 32px !important;
    border: 1px solid !important;
    padding: 0 !important;
} */
</style>
