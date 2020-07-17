<template>
<div class="viewContainer" v-resize="onResize">
    <v-container class="pa-0">
        <v-img src="/img/business-transactions-tab.jpg" :min-height="tabImgDefaultHeight" :aspect-ratio="tabImgAspectRation" transition="fade-transition">
            <v-container class="inner-container">
                <v-row no-gutters>
                    <v-col cols="12" md="12">

                        <v-card flat tile class="content-tabs-card pa-5 pt-0 mb-0 mb-md-12 mx-auto overflow-hidden">
                            <v-app-bar color="white" dark>
                                <v-btn color="primary" text @click="openSend">Send Promo message</v-btn>
                                <v-spacer></v-spacer>
                                <v-tooltip v-model="show" top>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn icon v-bind="attrs" v-on="on" @click="refresh_data">
                                            <v-icon>mdi-refresh</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Refresh</span>
                                </v-tooltip>
                            </v-app-bar>

                            <v-card-title>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search" append-icon="mdi-magnify" :label="$t('search')" single-line hide-details style="max-width: 320px"></v-text-field>
                            </v-card-title>
                            <v-card-text>
                                <v-divider />
                                <v-data-table :loading="loading" :loading-text="$t('loading_items')" :no-data-text="$t('no_data_available')" :no-results-text="$t('no_results_found')" :headers="headers" :items="transactions" :items-per-page="5" multi-sort :search="search" class="elevation-1" :footer-props="{
                                        itemsPerPageOptions: itemsPerPageOptions,
                                        itemsPerPageText: $t('rows_per_page'),
                                        itemsPerPageAllText: $t('all'),
                                        pageText: $t('page_of'),
                                        showFirstLastPage: true,
                                        firstIcon: 'mdi-chevron-double-left',
                                        lastIcon: 'mdi-chevron-double-right',
                                        prevIcon: 'mdi-chevron-left',
                                        nextIcon: 'mdi-chevron-right'
                                    }">
                                    <template v-slot:item.promo_message="{ item }">
                                        <!-- <span id="message_tab">{{ item.promo_message }}</span> -->
                                        <p class="mb-0 fg--text subtitle-1">
                                            <v-tooltip top max-width="340" color="NavBg NagFg--text">
                                                <template v-slot:activator="{ on }">
                                                    <span v-on="on" id="message_tab">{{ item.promo_message }}</span>
                                                </template>
                                                <span>{{ item.promo_message }}</span>
                                                <!-- <span v-html="$t('earn_points_info')"></span> -->
                                            </v-tooltip>
                                        </p>
                                    </template>
                                    <template v-slot:item.value="{ item }">{{ formatCurrency(item.value) }}</template>
                                    <template v-slot:item.created_at="{ item }">{{ formatDate(item.created_at, 'lll') }}</template>
                                </v-data-table>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-img>
    </v-container>
    <mySend />
</div>
</template>

<script>
import mySend from "./promo/send";
export default {
    components: {
        mySend,
    },
    data: () => ({
        locale: "en",
        tab: "tab-0",
        tabImg: "about:blank",
        tabImgHeight: null,
        tabImgDefaultHeight: 780,
        tabImgAspectRation: 1.7778,
        tabImgs: [],
        loading: true,
        business: null,
        search: "",
        itemsPerPageOptions: [10, 25, 50, 75, 100],
        transactions: []
    }),
    created() {
        this.refresh_data()
        // Set locale
        this.locale = this.$auth.check() ?
            this.$auth.user().locale :
            Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale;
        this.moment.locale(this.locale.substr(0, 2));

        // Tab images
        this.tabImgs = [
            this.$init.config.schemeAndHost + "/img/business-transactions-tab.jpg"
        ];

        // Set image depending on the size of the browser window
        if (window.innerWidth > 960) {
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

        eventBus.$on('refreshDataEvent', data => {
            this.refresh_data()
        })
    },
    methods: {
        refresh_data() {
            this.axios
                .get("/business/promo", {
                    params: {
                        locale: this.$i18n.locale
                    }
                })
                .then(response => {
                    this.transactions = response.data;
                    this.loading = false;
                });

        },
        openSend() {
            eventBus.$emit('sendMessageEvent')
        },
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
        formatDate(dt, format) {
            if (dt === null) {
                dt = "-";
            } else {
                dt =
                    format == "ago" ?
                    this.moment(dt).fromNow() :
                    this.moment(dt).format(format);
            }
            return dt;
        },
        onResize() {
            if (window.innerWidth <= 960) {
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
        headers: function () {
            return [{
                    text: 'Message',
                    align: "left",
                    sortable: true,
                    filterable: true,
                    value: "promo_message"
                },
                {
                    text: this.$t("staff_member"),
                    align: "left",
                    sortable: true,
                    filterable: true,
                    value: "user_name"
                },
                {
                    text: 'Sent to',
                    align: "left",
                    sortable: true,
                    filterable: true,
                    value: "customer_count"
                },
                {
                    text: this.$t("date"),
                    align: "left",
                    sortable: true,
                    value: "created_at"
                }
            ];
        }
    }
};
</script>

<style scoped>
#message_tab {
    font-style: inherit;
    font-weight: inherit;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 200px;
    overflow: hidden;
    display: block;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.no-gutters {
    margin-top: 60px;
}
</style>
