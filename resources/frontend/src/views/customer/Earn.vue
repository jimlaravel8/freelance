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
                      <v-card-text>
                        <p class="mb-0 fg--text subtitle-1">
                          {{ $t('earn_points_head') }}
                          <v-tooltip top max-width="340" color="NavBg NagFg--text">
                            <template v-slot:activator="{ on }">
                              <v-icon
                                style="margin-top:-2px;"
                                color="ctaBg"
                                v-on="on"
                              >mdi-help-circle-outline</v-icon>
                            </template>
                            <span v-html="$t('earn_points_info')"></span>
                          </v-tooltip>
                        </p>
                        <div class="mt-5">
                          <v-text-field
                            type="text"
                            class="title"
                            outlined
                            readonly
                            id="customerNumber"
                            :value="$auth.user().customer_number"
                            append-icon="mdi-content-copy"
                            @click:append="copyStringToClipboard($auth.user().customer_number)"
                          ></v-text-field>
                        </div>
                        <qrcode
                          class="mt-0"
                          :value="$init.config.schemeAndHost + '/business/issue-points?c=' + $auth.user().customer_number"
                          :options="{ margin:0, width: 180, errorCorrectionLevel: 'H', color: { light: '#ffffff00', dark: ($vuetify.theme.dark) ? '#ffffff' : '#000000' } }"
                        ></qrcode>
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
import { copyStringToClipboard } from "@/utils/helpers";

export default {
  data: () => ({
    locale: "en",
    tab: "tab-0",
    tabImg: "about:blank",
    tabImgHeight: null,
    tabImgDefaultHeight: 780,
    tabImgAspectRation: 1.7778,
    tabImgs: []
  }),
  created() {
    // Set locale
    this.locale = this.$auth.check()
      ? this.$auth.user().locale
      : Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale;
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
    this.tabImgs.forEach(function(img, i) {
      images[i] = new Image();
      images[i].src = img;
    });
  },
  methods: {
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
.v-application .mt-0 {
    /* margin-top: 0!important; */
    height: 50vh !important;
    width: 66% !important;
}
</style>
