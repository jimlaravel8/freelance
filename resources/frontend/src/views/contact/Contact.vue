<template>
  <div class="viewContainer">
    <v-container class="content-container">
      <v-row class="d-flex d-sm-none d-md-none d-lg-none d-xl-none">
        <router-link
          :to="{name: this.$store.state.app.gobackData}"
          class="NavFg--text text-decoration-none"
        >
          <v-icon large class="ml-1">mdi-keyboard-backspace</v-icon>
        </router-link>
      </v-row>
      <v-row>
        <v-col class="ma-3">
          <h1 class="display-2 mt-5 mb-10">Contact</h1>
          <!-- <div v-for="(paragraph, i) in $t('contact_paragraphs')" :key="'p_' + i">
            <p class="headline my-5" v-html="paragraph" />
          </div>-->
          <div>
            <p
              class="headline my-5" style="font-size: 20px !important;"
            >If you want more information or have any questions, please send an email to piggybankloyalty@gmail.com.</p>
            <p class="headline" style="font-size: 20px !important;">
              You may also reach us via WhatsApp:
              <a href="https://wa.me/17845303260?text=Hello" target="_blank">+1-784-530-3260</a>
            </p>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>

