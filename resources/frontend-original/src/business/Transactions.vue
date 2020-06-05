<template>
  <div class="viewContainer" v-resize="onResize">
    <v-container class="pa-0">
      <v-img
        :src="tabImg"
        :key="tabImg"
        :min-height="tabImgHeight"
        :aspect-ratio="tabImgAspectRation"
        transition="fade-transition"
      >
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
            <v-divider/>
          </v-card>
          <v-tabs-items v-model="tab" style="background-color: transparent;">
            <v-tab-item value="tab-0">
              <v-container class="pa-0">
                <v-row no-gutters>
                  <v-col
                    cols="12"
                    md="12"
                  >
                    <v-card flat tile class="content-tabs-card pa-5 pt-0 mb-0 mb-md-12">
                      <v-card-title>
                        <v-spacer></v-spacer>
                        <v-text-field
                          v-model="search"
                          append-icon="mdi-magnify"
                          :label="$t('search')"
                          single-line
                          hide-details
                          style="max-width: 320px"
                        ></v-text-field>
                      </v-card-title>
                      <v-card-text>
                        <v-divider/>
                        <v-data-table
                          :loading="loading"
                          :loading-text="$t('loading_items')"
                          :no-data-text="$t('no_data_available')"
                          :no-results-text="$t('no_results_found')"
                          :headers="headers"
                          :items="transactions"
                          :items-per-page="5"
                          multi-sort
                          :search="search"
                          class="elevation-1"
                          :footer-props="{
                            itemsPerPageOptions: itemsPerPageOptions,
                            itemsPerPageText: $t('rows_per_page'),
                            itemsPerPageAllText: $t('all'),
                            pageText: $t('page_of'),
                            showFirstLastPage: true,
                            firstIcon: 'mdi-chevron-double-left',
                            lastIcon: 'mdi-chevron-double-right',
                            prevIcon: 'mdi-chevron-left',
                            nextIcon: 'mdi-chevron-right'
                          }"
                        >
                          <template v-slot:item.points="{ item }">
                            {{ formatNumber(item.points) }}
                          </template>
                          <template v-slot:item.value="{ item }" v-if="business">
                            {{ formatCurrency(item.value) }}
                          </template>
                          <template v-slot:item.expires_at="{ item }">
                            {{ formatDate(item.expires_at, 'ago') }}
                          </template>
                          <template v-slot:item.created_at="{ item }">
                            {{ formatDate(item.created_at, 'lll') }}
                          </template>

                        </v-data-table>
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
export default {
  data: () => ({
    locale: 'en',
    tab: 'tab-0',
    tabImg: 'about:blank',
    tabImgHeight: null,
    tabImgDefaultHeight: 780,
    tabImgAspectRation: 1.7778,
    tabImgs: [],
    loading: true,
    business: null,
    search: '',
    itemsPerPageOptions: [10, 25, 50, 75, 100],
    transactions: []
  }),
  created () {
    // Set locale
    this.locale = (this.$auth.check()) ? this.$auth.user().locale: Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale
    this.moment.locale(this.locale.substr(0,2))

    this.axios
      .get('/business/settings', { params: { locale: this.$i18n.locale }})
      .then(response => {
        this.business = response.data.business
      })
  
    this.axios
      .get('/business/transactions', { params: { locale: this.$i18n.locale }})
      .then(response => {
        this.transactions = response.data
        this.loading = false
      })

    // Tab images
    this.tabImgs = [
      this.$init.config.schemeAndHost + '/img/business-transactions-tab.jpg'
    ]

    // Set image depending on the size of the browser window
    if (window.innerWidth > 960) {
      this.tabImg = this.tabImgs[0]
      this.tabImgHeight = this.tabImgDefaultHeight
      this.tabImgAspectRation = 1.7778
    } else {
      this.tabImgHeight = null
      this.tabImgAspectRation = 3
    }

    // Preload
    let images = new Array()
    this.tabImgs.forEach(function(img, i) {
      images[i] = new Image()
      images[i].src = img
    })
  },
  methods: {
    formatNumber (number) {
      return new Intl.NumberFormat(this.locale.replace('_', '-')).format(number)
    },
    formatCurrency (number) {
      return new Intl.NumberFormat(this.locale.replace('_', '-'), {style: 'currency', currency: this.business.currency}).format(number)
    },
    formatDate (dt, format) {
      if (dt === null) {
        dt = '-'
      } else {
        dt = (format == 'ago') ? this.moment(dt).fromNow() : this.moment(dt).format(format)
      }
      return dt
    },
    onResize () {
      if (window.innerWidth <= 960) {
        this.tabImg = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='
        this.tabImgHeight = null
        this.tabImgAspectRation = 3
      } else if (this.tabImg == 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=') {
        let index = this.tab.split('-')
        this.tabImg = this.tabImgs[index[1]]
        this.tabImgHeight = this.tabImgDefaultHeight
        this.tabImgAspectRation = 1.7778
      }
    }
  },
  computed: {
    headers: function () {
      return [
        {
          text: this.$t('customer'),
          align: 'left',
          sortable: true,
          filterable: true,
          value: 'customer_number',
        },
        {
          text: this.$t('staff_member'),
          align: 'left',
          sortable: true,
          filterable: true,
          value: 'staff_name',
        },
        {
          text: this.$t('event'),
          align: 'left',
          sortable: true,
          filterable: true,
          value: 'event',
        },
        {
          text: this.$t('value'),
          align: 'left',
          sortable: true,
          value: 'value',
        },
        {
          text: this.$t('points'),
          align: 'left',
          sortable: true,
          value: 'points',
        },
        {
          text: this.$t('expires'),
          align: 'left',
          sortable: true,
          value: 'expires_at',
        },
        {
          text: this.$t('date'),
          align: 'left',
          sortable: true,
          value: 'created_at',
        }
      ]
    }
  }
}
</script>