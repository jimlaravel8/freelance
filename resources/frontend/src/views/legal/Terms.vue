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
          <h1 class="display-2 mt-5 mb-10">{{ $t('terms_and_conditions') }}</h1>
          <div
            v-for="(paragraphs, title) in $t('terms_and_conditions_paragraphs')"
            :key="'p_' + title"
          >
            <h2 class="headline my-5" v-html="title" />
            <div v-for="(p, title) in paragraphs" :key="'p_' + title">
              <p v-if="!Array.isArray(p)" class="subtitle-1" v-html="p" />
              <ol v-else-if="Array.isArray(p)" class="my-5">
                <li v-for="(li, i) in p" :key="'li_' + i" v-html="li" />
              </ol>
            </div>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>