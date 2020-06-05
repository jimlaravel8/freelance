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
              v-model="tab"
              :color="($store.state.app.dark) ? 'white' : 'black'"
              class="px-5 content-tabs"
              icons-and-text
              height="100"
              slider-size="4"
              show-arrows
            >
              <v-tabs-slider></v-tabs-slider>

              <v-tab href="#tab-0" class="no-caps" :ripple="false">
                {{ $t('about_tab1') }}
                <v-icon size="24">mdi-account-outline</v-icon>
              </v-tab>

              <v-tab href="#tab-1" class="no-caps" :ripple="false">
                {{ $t('about_tab2') }}
                <v-icon size="24">mdi-account-tie-outline</v-icon>
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
                    sm="6"
                  >
                    <v-card flat tile class="content-tabs-card pa-5 mb-0 mb-md-12">
                      <v-card-text>
                        <h1 class="fg--text display-2 font-weight-medium" style="line-height: 4rem; max-width: 310px">{{ $t('about_tab1_title') }}</h1>
                        <p class="mt-5 fg--text subtitle-1">{{ $t('about_tab1_desc1') }}</p>
                        <p class="mt-5 fg--text subtitle-1">{{ $t('about_tab1_desc2') }}</p>
                        <p class="fg--text subtitle-1">{{ $t('about_tab1_desc3') }}</p>
                        <p class="mb-0 fg--text subtitle-1">{{ $t('about_tab1_desc4') }}</p>
                        <v-btn v-if="!$auth.check()" class="mt-5 no-caps" color="ctaBg ctaFg--text" x-large tile depressed :to="{ name: 'register' }">{{ $t('get_started_now') }}</v-btn>
                      </v-card-text>
                    </v-card>
                  </v-col>
                </v-row>
              </v-container>
            </v-tab-item>
            <v-tab-item value="tab-1">
              <v-container class="pa-0">
                <v-row no-gutters>
                  <v-col
                    cols="12"
                    sm="6"
                  >
                    <v-card flat tile class="content-tabs-card pa-5 mb-0 mb-md-12">
                      <v-card-text>
                        <h1 class="fg--text display-2 font-weight-medium" style="line-height: 4rem; max-width: 280px">{{ $t('about_tab2_title') }}</h1>
                        <p class="mt-5 fg--text subtitle-1">{{ $t('about_tab2_desc1') }}</p>
                        <p class="fg--text subtitle-1">{{ $t('about_tab2_desc2') }}</p>
                        <p class="fg--text subtitle-1 font-weight-medium">{{ $t('about_tab2_desc3') }}</p>
                        <p class="fg--text title">{{ $t('about_tab2_title2') }}</p>
                        <p class="fg--text subtitle-1">{{ $t('about_tab2_desc4') }}</p>
                        <p class="fg--text mb-0 subtitle-1 pl-4 font-weight-medium" style="border-left: 4px solid #ccc;">{{ $t('about_tab2_desc5') }}</p>
                        <p class="fg--text pt-4 subtitle-1 pl-4 font-weight-medium" style="border-left: 4px solid #ccc;">{{ $t('about_tab2_desc6') }}</p>
                        <v-btn v-if="!$auth.check()" class="mt-5 no-caps" color="ctaBg ctaFg--text" x-large tile depressed :to="{ name: 'register-business' }">{{ $t('get_started_now') }}</v-btn>
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
    tab: null,
    tabImg: 'about:blank',
    tabImgHeight: null,
    tabImgDefaultHeight: 780,
    tabImgAspectRation: 1.7778,
    tabImgs: []
  }),
  mounted () {
    document.title = this.$t('about_page_title')

    // Tab images
    this.tabImgs = [
      this.$init.config.schemeAndHost + '/img/about-customers-tab.jpg',
      this.$init.config.schemeAndHost + '/img/about-businesses-tab.jpg'
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
  },
  watch: {
    tab: function (val) {
      if (window.innerWidth > 760) {
        let index = val.split('-')
        this.tabImg = this.tabImgs[index[1]]
        this.tabImgHeight = this.tabImgDefaultHeight
        this.tabImgAspectRation = 1.7778
      }
    }
  }
};
</script>