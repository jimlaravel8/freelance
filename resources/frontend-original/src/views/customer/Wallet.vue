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
      >
      </v-img>
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
          </v-tabs>
          <v-divider/>
        </v-card>
        <v-tabs-items v-model="tab" style="background-color: transparent;">
          <v-tab-item value="tab-0">
            <v-container class="pa-0">
              <v-row no-gutters>
                <v-col
                  cols="12"
                  sm="9"
                  md="6"
                >
                  <v-card flat tile class="content-tabs-card pa-5 mb-0 mb-md-12" elevation="0">
                    <v-card-text>
                      <v-progress-linear
                        :active="loading"
                        indeterminate
                        color="ctaBg"
                      ></v-progress-linear>
                      <p class="mb-0 fg--text subtitle-1" v-if="!loading && wallet !== null && Array.isArray(wallet) && wallet.length == 0">{{ $t('wallet_head_empty') }}</p>
                      <div v-if="wallet !== null && !loading">
                        <v-card
                          v-for="(item, index) in wallet"
                          :key="index"
                          :class="(index > 0) ? 'mt-5' : ''"
                          flat
                          tile
                          elevation="1"
                          outlined
                          :to="{ name: 'customer.card', params: { uuid: item.uuid }}"
                        >
                          <v-img
                            v-if="item.cover"
                            :src="item.cover"
                          />
                          <v-card-title>
                            <v-icon left color="ctaBg">
                              mdi-wallet-giftcard
                            </v-icon>
                            <span class="title font-weight-medium">{{ item.name }}</span>
                          </v-card-title>

                          <v-row
                            align="center"
                            class="mx-4"
                          >
                            <span class="body-1 font-weight-medium" v-html="formatNumber(item.balance.points)"/> <v-icon right size="18">mdi-coins</v-icon>
                            <div class="grey--text ml-5"><span v-html="formatCurrency(item.balance.value, item.balance.currency)"/></div>
                          </v-row>
                          <v-card-text>
                            <div class="subtitle-1 mb-3" v-if="item.desc" v-html="item.desc"/>
                            <div class="body-2">
                              <div v-if="!item.balance.redeemable">{{ $t('points_required_for_discount', { points: formatNumber(item.balance.pointsRequired) }) }}</div>
                              <div v-if="item.balance.redeemable" class="success--text">{{ $t('enough_for_discount') }} <v-icon color="success" small>mdi-arrow-right</v-icon></div>
                            </div>
                          </v-card-text>
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
    dialogGenerateDiscountCode: false,
    selectedCard: null,
    generateDiscountCodeForm: {
      loading: false
    },
    loading: true,
    wallet: null
  }),
  created () {
    // Set locale
    this.locale = (this.$auth.check()) ? this.$auth.user().locale: Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale
    this.moment.locale(this.locale.substr(0,2))

    this.axios
      .get('/customer/wallet', { params: { locale: this.$i18n.locale }})
      .then(response => {
        this.wallet = response.data.wallet
        this.loading = false
      })

    // Tab images
    this.tabImgs = [
      this.$init.config.schemeAndHost + '/img/customer-wallet-tab.jpg'
    ]

    // Set image depending on the size of the browser window
    if (window.innerWidth > 760) {
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
    formatCurrency (number, currency) {
      return new Intl.NumberFormat(this.locale.replace('_', '-'), {style: 'currency', currency: currency}).format(number)
    },
    onResize () {
      if (window.innerWidth <= 760) {
        this.tabImg = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='
        this.tabImgHeight = null
        this.tabImgAspectRation = 3
      } else if (this.tabImg == 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=') {
        let index = this.tab.split('-')
        this.tabImg = this.tabImgs[index[1]]
        this.tabImgHeight = this.tabImgDefaultHeight
        this.tabImgAspectRation = 1.7778
      }
    },
  }
}
</script>