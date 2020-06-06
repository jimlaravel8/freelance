'use strict';

import Vue from 'vue';
import Vuetify from 'vuetify/lib';

Vue.use(Vuetify);

export default new Vuetify({
  theme: {
      options: {
        customProperties: true,
      },
    themes: {
      light: {
        NavBg: '#000000',
        NavFg: '#ffffff',
        ctaBg: '#000000',
        ctaFg: '#ffffff',
        fg: '#000000',
        primary: '#000000',
        secondary: '#424242',
        accent: '#146eff',
        error: '#9e0000',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FFC107'
      },
      dark: {
        primary: '#ffffff',
        NavBg: '#000000',
        NavFg: '#ffffff',
        ctaBg: '#ffffff',
        ctaFg: '#000000',
        fg: '#ffffff',
        error: '#c50000'
        
      },
    },
  },
});
