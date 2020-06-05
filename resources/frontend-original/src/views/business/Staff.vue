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
              <v-tab :to="{ name: 'business.staff' }" class="no-caps" :ripple="false">
                {{ $t('staff') }}
                <v-icon size="24">mdi-account-tie-outline</v-icon>
              </v-tab>
              <v-tab :to="{ name: 'business.rules' }" class="no-caps" :ripple="false">
                {{ $t('rules') }}
                <v-icon size="24">mdi-cards-playing-outline</v-icon>
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
                    <v-card flat tile class="content-tabs-card pa-5 mb-0 mb-md-12">
                      <v-card-text>
                        <v-alert
                          :value="!$auth.user().premium"
                          type="info"
                          tile
                          class="mb-5"
                        >
                          <i18n path="premium_account_required">
                            <template #premium_account>
                              <router-link :to="{name: 'settings.business.subscription'}" class="white--text">{{ $t('premium_account').toLowerCase() }}</router-link>
                            </template>
                          </i18n>
                        </v-alert>
                        <data-table
                          model="App\User"
                        >
                          <template v-slot:empty-head>
                            <v-icon size="72">mdi-account-tie-outline</v-icon>
                          </template>
                          <template v-slot:empty-text>
                            <p class="subheading" v-html="$t('staff_description')"></p>
                          </template>
                        </data-table>
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
    tabImgs: []
  }),
  created () {
    // Set locale
    this.locale = (this.$auth.check()) ? this.$auth.user().locale: Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale
    this.moment.locale(this.locale.substr(0,2))

    // Tab images
    this.tabImgs = [
      this.$init.config.schemeAndHost + '/img/business-staff-tab.jpg'
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
  }
}
</script>