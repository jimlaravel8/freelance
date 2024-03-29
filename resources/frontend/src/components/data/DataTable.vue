<template>
<div class="fill-height">
    <v-container fluid v-if="!loaded" class="fill-height">
        <v-row align="center" justify="center" class="fill-height">
            <v-progress-circular :size="32" width="3" color="ctaBg" indeterminate class="ma-12"></v-progress-circular>
        </v-row>
    </v-container>

    <v-card flat v-show="loaded" color="transparent">
        <v-toolbar flat color="transparent" class="toolbar-content-no-padding mt-2 mb-4" v-if="filteredResults || rows.length > 0" height="40">
            <v-toolbar-title>{{ translations.items }}</v-toolbar-title>

            <v-btn v-if="((rows.length == 0 && filteredResults) || parseInt(rows.length) > 0) && settings.create === true" @click="uuid = null; dataForm = true" class="ml-2 no-caps" small text color="ctaBg ctaFg--text">
                <v-icon left>mdi-plus</v-icon>
                {{ translations.create_item }}
            </v-btn>

            <v-spacer></v-spacer>

            <v-text-field v-if="loaded && ((rows.length == 0 && filteredResults) || parseInt(rows.length) > 0)" v-model="search" append-icon="mdi-magnify" :label="$t('search')" single-line clearable flat dense solo-inverted hide-details :style="{'max-width': ($vuetify.breakpoint.xs) ? '135px' : '320px'}"></v-text-field>

            <v-menu offset-y v-if="parseInt(rows.length) > 0 && showExport">
                <template v-slot:activator="{ on }">
                    <v-btn class="ml-2 mr-1" icon v-on="on">
                        <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item @click="exportRows">
                        <v-list-item-title>{{ $t('download_excel') }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-toolbar>

        <div v-if="!filteredResults && !loading && loaded && rows.length == 0" class="text-center my-10">
            <div class="mt-10">
                <slot name="empty-head"></slot>
            </div>
            <h1 class="title my-4">{{ $t('no_items_yet', {items: translations.items_lowercase}) }}</h1>
            <div class="mx-5" :class="{'pb-4': !settings.create}">
                <slot name="empty-text"></slot>
            </div>
            <div>
                <v-btn @click="uuid = null; dataForm = true" v-if="settings.create === true" depressed large class="mt-3 mb-5 no-caps" color="ctaBg ctaFg--text">
                    <v-icon left>mdi-plus</v-icon>
                    {{ translations.create_item }}
                </v-btn>
            </div>
        </div>

        <v-toolbar flat color="transparent" v-show="filters.length > 0 && loaded && ((rows.length == 0 && filteredResults) || parseInt(rows.length) > 0)">
            <v-spacer></v-spacer>
            <div v-for="(filter, filter_index) in filters" :key="'filter_' + filter_index">
                <v-autocomplete @change="changeFilter" v-model="filter.model" :items="filter.items" :placeholder="filter.placeholder" :prepend-inner-icon="filter.icon" item-text="val" item-value="pk" flat dense chips :multiple="true" solo clearable hide-no-data hide-details deletable-chips></v-autocomplete>
            </div>
        </v-toolbar>

        <v-btn v-if="((rows.length == 0 && filteredResults) || parseInt(rows.length) > 0) && settings.create === true" color="pink" dark large fixed right bottom fab id="createBtn" @click="uuid = null; dataForm = true">
            <v-icon>mdi-plus</v-icon>
        </v-btn>

        <v-tooltip bottom v-model="showFabToolTip" activator="#createBtn" v-if="((rows.length == 0 && filteredResults) || parseInt(rows.length) > 0) && settings.create === true">
            <span>{{ translations.create_item }}</span>
        </v-tooltip>

        <v-data-table :mobile-breakpoint="0" class="elevation-1" v-show="loaded && ((rows.length == 0 && filteredResults) || parseInt(rows.length) > 0)" v-model="selected" :headers="headers" v-bind="$attrs" :items="rows" :loading="loading" item-key="uuid" :options.sync="options" :sort-desc.sync="descending" :server-items-length="totalItems" :show-select="settings.select_all" header-props.sort-icon="arrow_drop_down" :items-per-page-options="itemsPerPageOptions" :loading-text="$t('loading_items')" :no-data-text="$t('no_data_available')" :no-results-text="$t('no_results_found')" :footer-props="{
          itemsPerPageText: $t('rows_per_page'),
          itemsPerPageAllText: $t('all'),
          pageText: $t('page_of'),
          showFirstLastPage: true,
          firstIcon: 'mdi-chevron-double-left',
          lastIcon: 'mdi-chevron-double-right',
          prevIcon: 'mdi-chevron-left',
          nextIcon: 'mdi-chevron-right'
        }">
            <template :slot="'item.' + item.value" slot-scope="props" v-for="(item, index) in headers">
                <div v-if="index != Object.keys(headers).length - 1 || ! settings.actions" :style="item.style" :key="'col_' + index">
                    <div v-if="item.type === 'boolean'" class="text-center">
                        <div v-if="props.item[item.value] === 1 || props.item[item.value] === '1' || props.item[item.value] === true">
                            <v-icon small>mdi-check</v-icon>
                        </div>
                        <div v-else>
                            <v-icon small>mdi-close</v-icon>
                        </div>
                    </div>
                    <div v-else-if="item.type === 'link'">
                        <a :href="props.item[item.value]" target="_blank">{{ props.item[item.value].replace('http://', '').replace('https://', '').replace('//', '') }}</a>
                    </div>
                    <div v-else-if="item.type === 'number' || item.type === 'currency'">
                        <div class="text-right">{{ props.item[item.value] }}</div>
                    </div>
                    <div v-else-if="item.type === 'date_time' || item.type === 'date'">
                        <span v-html="parseDateTime(props.item[item.value], item.format, item.color_is_past, item.color_is_future)"></span>
                    </div>
                    <div v-else-if="item.type === 'image'">
                        <v-img :src="props.item[item.value]" class="elevation-1 my-3 d-block" v-if="props.item[item.value]" contain :max-width="item.max_width" :max-height="item.max_height"></v-img>
                    </div>
                    <div v-else-if="item.value === 'avatar'" class="text-center">
                        <v-avatar :tile="false" size="32" color="grey lighten-4">
                            <v-img :src="props.item[item.value]" v-if="props.item[item.value]"></v-img>
                        </v-avatar>
                    </div>
                    <div v-else>{{ props.item[item.value] }}</div>
                </div>
                <div v-if="index == Object.keys(headers).length - 1 && settings.actions" :style="{width: settings.actions_width}" :key="'col_' + index">
                    <span v-for="(item, index) in actions" :key="'action_' + index">
                        <v-tooltip top v-if="item.action">
                            <template v-slot:activator="{ on }">
                                <v-btn class="mr-1 mt-0 mb-0" v-on="on" :color="item.color" icon small @click="executeAction(item.action, props.item.uuid)">
                                    <v-icon small>{{ item.icon }}</v-icon>
                                </v-btn>
                            </template>
                            <span>{{ item.text }}</span>
                        </v-tooltip>
                        <v-divider v-else vertical style="vertical-align: middle;height: 16px" class="mx-1"></v-divider>
                    </span>
                </div>
            </template>

            <template v-slot:footer>
                <div class="ma-3">
                    <v-btn v-if="settings.select_all" depressed small color="error" :disabled="disableDeleteSelected" @click="deleteSelected">{{ $t('delete_selected') }}</v-btn>
                </div>
            </template>

            <template v-slot:no-data>
                <div v-if="!loading" class="text-center">{{ $t('data_table_no_result') }}</div>
                <div v-if="loading" class="text-center">{{ $t('loading_items') }}</div>
            </template>
        </v-data-table>


        <v-dialog persistent :width="settings.dialog_width || 480" :fullscreen="$vuetify.breakpoint.xsOnly" v-model="dataForm" :dataForm="dataForm" @keydown.esc="dataForm = false">
            <data-form v-if="dataForm" v-on:data-list-events="dataListEvents = $event" :model="model" :uuid="uuid"></data-form>
        </v-dialog>


        <v-dialog persistent :width="settings.dialog_width || 480" :fullscreen="$vuetify.breakpoint.xsOnly" v-model="passwordForm" :passwordForm="passwordForm" @keydown.esc="passwordForm = false">
            <password-form v-if="passwordForm" v-on:data-list-events="dataListEvents = $event" :model="model" :uuid="uuid"></password-form>
        </v-dialog>

        <v-dialog v-model="payments.dialogPayment" max-width="680">
            <!-- <v-card outlined :loading="payments.paymentDataLoading" shaped>
          <v-card-title primary-title>
            <div class="m-3">
              <h3 class="headline mb-0">Update Business Payments</h3>
              <div>From here you can update the business' payments</div>
            </div>
          </v-card-title>
          <v-autocomplete v-model="payments.months" :item-text="0" :item-value="0"></v-autocomplete>
          <v-card-actions>
            <v-btn flat @click="payments.dialogPayment=false">Close</v-btn>
            <v-btn flat @click="updatePaymentStatus()">Save</v-btn>
          </v-card-actions>
        </v-card>-->
            <v-card flat tile class="pa-5 mb-0">
                <v-card-text>
                    <v-card outlined>
                        <v-card-title>Monthly Billing Report</v-card-title>
                        <v-select class="ma-5" label="Select a year" v-model="billings.transactionYear" :items="billings.transactionYears" item-text="0" item-value="0"></v-select>
                        <v-select class="ma-5" label="Select a month" v-model="billings.transactionMonth" :items="getMonths" item-text="text" item-value="value"></v-select>
                        <div v-if="billings.loaded && billings.status_code == 200">
                            <v-input class="ma-5" prepend-icon="mdi-clock" color="black" :messages="['Due Payment Date (Net 7)']">{{this.billings.due_details.date}}</v-input>
                            <v-input class="ma-5" prepend-icon="mdi-cash" color="black" :messages="['Due Amount']">{{this.billings.due_details.amount}}</v-input>
                            <v-input class="ma-5" prepend-icon="mdi-cash" color="black" :messages="[`Points Issuance Sessions: ${this.billings.transaction_details.earnings.count}`]">
                                <v-icon slot="prepend" color="green darken-2">mdi-cash</v-icon>
                                {{
                  `Total Points Issued: ${parseInt(this.billings.transaction_details.earnings.amount).toLocaleString()}`
                  }}
                            </v-input>
                            <v-input class="ma-5" color="black" :messages="[`Points Redemption Sessions: ${this.billings.transaction_details.spendings.count}`]">
                                <v-icon slot="prepend" color="red darken-2">mdi-cash</v-icon>
                                {{
                  `Total Points Redeemed: ${parseInt(this.billings.transaction_details.spendings.amount).toLocaleString()}`
                  }}
                            </v-input>
                            <v-input class="ma-5" prepend-icon="mdi-file-document" color="black" :messages="['Invoice NO']">{{this.billings.invoice_no}}</v-input>
                            <v-checkbox class="ma-5" v-model="payments.paid" @change="updatePaid" label="Payment is Paid"></v-checkbox>
                        </div>
                        <div v-if="billings.status_code == 404">
                            <h3 class="ma-5">No Transactions found</h3>
                        </div>
                    </v-card>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-card>
</div>
</template>

<script>
import _ from "lodash";
import moment from "moment";

export default {
    data: () => {
        return {
            billings: {
                status_code: 200,
                userid: "",
                transactionMonth: "",
                transactionYears: [],
                transactions: [],
                transaction_details: {
                    earnings: "",
                    spendings: ""
                },
                due_details: {
                    date: "",
                    amount: "",
                    paid: ""
                },
                invoice_no: "",
                loaded: false
            },
            payments: {
                paid: false,
                dialogPayment: false,
                paymentDataLoading: false,
                months: moment.months(),
                transactions: 0,
                totaldue: 0,
                //duedate: moment.endOf("month").format("YYYY-MM-DD hh:mm"),
                status: null
            },
            descending: false,
            search: "",
            showExport: false,
            totalItems: 0,
            pageCount: 0,
            dataListEvents: null,
            filteredResults: false,
            showFabToolTip: false,
            dataForm: false,
            passwordForm: false,
            uuid: null,
            disableDeleteSelected: true,
            loading: true,
            loaded: false,
            selected: [],
            settings: [],
            headers: [],
            actions: [],
            translations: [],
            rows: [],
            filters: [],
            itemsPerPageOptions: [10, 25, 50, 75, 100],
            watchPagination: false,
            options: {
                page: 1,
                itemsPerPage: 10,
                filters: []
            },
            optionsOld: []
        };
    },
    props: {
        api: {
            default: "/app/data-table",
            required: false,
            type: String
        },
        create: {
            default: null,
            required: false,
            type: String
        },
        edit: {
            default: null,
            required: false,
            type: String
        },
        model: {
            default: "",
            required: false,
            type: String
        }
    },
    watch: {
        options: {
            handler(val) {
                if (this.watchPagination) {
                    let string_val = String(JSON.stringify(val));
                    let string_old = String(this.optionsOld);

                    if (string_val !== string_old) {
                        this.reloadData();
                    }

                    this.optionsOld = string_old;
                }
            },
            deep: true
        },
        search() {
            this.searchData();
        },
        selected() {
            this.disableDeleteSelected = this.selected.length > 0 ? false : true;
        },
        dataListEvents() {
            if (this.dataListEvents.closeDialog) {
                this.dataForm = false;
                this.passwordForm = false;
            }
            if (this.dataListEvents.reload) {
                this.reloadData();
            }
        },
        "billings.transactionMonth": function (newOne, oldOne) {
            this.billings.transactionMonth = newOne;
            this.getTransactions();
        }
    },
    mounted() {
        if (typeof this.$route.params.showSnackbar !== "undefined") {
            this.$root.$snackbar(this.$t(this.$route.params.showSnackbar));
        }
        this.getDataFromApi().then(data => {
            this.rows = data.items;
            this.watchPagination = true;
        });
    },
    methods: {
        async getTransactions() {
            const body = await this.axios.get(
                `/admin/transaction/${this.billings.transactionMonth}/${this.uuid}`
            );
            this.billings.status_code = body.data.status_code ?? 200;
            const data = body.data;

            const {
                transactions,
                transaction_details,
                due_details,
                invoice_no
            } = data;
            this.payments.paid = due_details.status == 1;
            this.billings.transactions = transactions;
            this.billings.transaction_details.earnings = transaction_details.earnings;
            this.billings.transaction_details.spendings =
                transaction_details.spendings;
            this.billings.due_details.date = moment(
                new Date(due_details.date)
            ).format("DD MMM YYYY");
            this.billings.due_details.amount = due_details.amount;
            this.billings.due_details.paid = due_details.status;
            this.billings.invoice_no = invoice_no;
            this.billings.loaded = true;
        },
        async updatePaid(newval, oldval) {
            const paid = newval ? 1 : 0;
            const url = `/admin/transaction/${this.billings.transactionMonth}/${this.uuid}/${paid}`;

            const body = await this.axios(url);
            const data = body.data;
            console.log(data);
            this.$root.$snackbar(data.message);
        },
        searchData: _.debounce(function () {
            this.loading = true;
            this.reloadData();
        }, 400),
        getDataFromApi() {
            this.loading = true;
            return new Promise(resolve => {
                let that = this;

                this.axios
                    .get(this.api, {
                        params: {
                            locale: this.$i18n.locale,
                            model: this.model,
                            search: this.search,
                            page: this.options.page,
                            itemsPerPage: this.options.itemsPerPage,
                            sortBy: this.options.sortBy,
                            descending: this.descending,
                            filters: this.options.filters
                        }
                    })
                    .then(res => {
                        if (res.data.status === "success") {
                            that.headers = Object.keys(res.data.headers).map(
                                k => res.data.headers[k]
                            );
                            that.filters = res.data.filters;
                            that.settings = res.data.settings;
                            that.filteredResults = res.data.filteredResults;
                            that.actions = res.data.actions;
                            that.showExport = res.data.showExport;
                            that.translations = res.data.translations;
                            that.totalItems = res.data.total;
                            that.loading = false;
                            that.loaded = true;

                            let items = res.data.records;

                            resolve({
                                items
                            });
                        }
                    })
                    .catch(err => console.log(err.response.data))
                    .finally(() => (that.loading = false));
            });
        },
        reloadData() {
            this.watchPagination = false;
            this.loading = true;
            this.getDataFromApi().then(data => {
                this.rows = data.items;
                this.watchPagination = true;
            });
        },
        changeFilter() {
            let filters = {};
            for (let f in this.filters) {
                let filter = this.filters[f].column;
                let pk = this.filters[f].model;
                if (pk.length > 0) filters[filter] = pk;
            }
            this.options.filters = filters;
        },
        executeAction(action, uuid) {
            if (action == "delete") {
                this.uuid = null;
                this.deleteRecords([uuid]);
            }
            if (action == "edit") {
                this.uuid = uuid;
                this.dataForm = true;
            }
            if (action == "edit_password") {
                this.uuid = uuid;
                this.passwordForm = true;
            }
            if (action == "log_in_as") {
                this.$auth.impersonate({
                    params: {
                        uuid: uuid
                    },
                    success: function () {},
                    error: function () {},
                    redirect: {
                        name: "home"
                    }
                });
            }
            if (action == "open_payments") {
                this.uuid = uuid;
                this.axios(`/admin/transaction-years/${this.uuid}`).then(body => {
                    this.billings.transactionYear = "";
                    this.billings.transactionMonth = "";
                    this.billings.transactionYears = body.data.years;
                    this.payments.dialogPayment = true;
                    this.billings.loaded = false;
                });
            }
        },
        deleteSelected() {
            if (this.selected.length > 0) {
                let uuids = this.selected;
                uuids = this.$_.map(uuids, function (o) {
                    return o.uuid;
                });
                this.deleteRecords(uuids);
            }
        },
        deleteRecords(uuids) {
            this.$root
                .$confirm(this.$t("delete"), this.$t("confirm_delete"))
                .then(confirm => {
                    if (confirm) {
                        this.loading = true;

                        let that = this;
                        this.axios
                            .post(this.api + "/delete", {
                                locale: this.$i18n.locale,
                                model: this.model,
                                uuids: uuids
                            })
                            .then(res => {
                                if (res.data.status === "success") {
                                    this.reloadData();
                                    this.$root.$snackbar(
                                        this.$t("items_deleted", {
                                            items: res.data.deleted
                                        })
                                    );
                                }
                            })
                            .catch(err => {
                                if (err.response.data.status === "error") {
                                    this.$root.$snackbar(err.response.data.msg);
                                    this.reloadData();
                                }
                            })
                            .finally(() => (that.loading = false));
                    }
                });
        },
        parseDateTime(datetime, format, color_is_past, color_is_future) {
            this.moment.locale(this.$auth.user().locale);

            let color = null;
            if (
                typeof color_is_past !== "undefined" &&
                this.moment(datetime).isBefore(this.moment())
            ) {
                color = color_is_past;
            }
            if (
                typeof color_is_future !== "undefined" &&
                this.moment(datetime).isAfter(this.moment())
            ) {
                color = color_is_future;
            }

            if (datetime === null) {
                datetime = "-";
            } else {
                datetime =
                    format == "ago" ?
                    this.moment(datetime).fromNow() :
                    this.moment(datetime).format(format);
            }

            if (color !== null) {
                return (
                    '<div style="font-weight:bold;color: ' +
                    color +
                    '">' +
                    datetime +
                    "</div>"
                );
            } else {
                return datetime;
            }
        },
        exportRows() {
            this.loading = true;
            let that = this;
            let uuids = this.selected;
            uuids = this.$_.map(uuids, function (o) {
                return o.uuid;
            });

            this.axios({
                    url: this.api + "/export",
                    method: "GET",
                    responseType: "blob",
                    params: {
                        locale: this.$i18n.locale,
                        model: this.model,
                        uuids: uuids
                    }
                })
                .then(res => {
                    const url = window.URL.createObjectURL(new Blob([res.data]));
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", that.translations.items + ".xlsx");
                    document.body.appendChild(link);
                    link.click();
                })
                .catch(err => {
                    if (err.response.data.status === "error") {
                        this.$root.$snackbar(err.response.data.msg);
                    }
                })
                .finally(() => (this.loading = false));
        }
        // async getPaymentData() {
        //   const body = await this.axios.get(`/admin/payments/${this.userid}`);
        //   const data = body.data;
        //   this.transactions = data.transactions;
        //   this.totaldue = data.totaldue;
        //   this.status = data.status;
        // },
    },
    computed: {
        app() {
            return this.$store.getters.app;
        },
        getMonths: function () {
            let months = [];
            let monthsNames = moment.months();
            let year = new Date().getFullYear();
            for (let i in monthsNames) {
                months.push({
                    value: `1-${parseInt(i) + 1}-${year}`,
                    text: monthsNames[i]
                });
            }
            return months;
        }
    }
};
</script>
