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
              class="headline my-5"
            >If you want more information or have any questions, please send an email to hello@piggybanksvg.com.</p>
            <p class="headline">
              You may also reach us via WhatsApp:
              <a href="tel:1-784-500-0000">+1-784-500-0000</a>
            </p>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
