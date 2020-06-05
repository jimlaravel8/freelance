<template>
  <div class="viewContainer">
    <v-container class="content-container mt-10">
      <v-row class="pa-2">
        <p
          :style="{fontSize: 1.05+'em'}"
        >These partner businesses are authorized to issue and redeem Piggy Bank Points</p>
        <p
          :style="{fontSize: 1+'em'}"
          v-if="!categoryList.length"
        >There are no businesses available yet, please check back later.</p>
      </v-row>
      <h5>In {{this.$auth.user().location}}</h5>
      <v-row class="pa-2 mb-12">
        <v-divider></v-divider>
      </v-row>
      <div class="mt-5" v-for="(category, index) in categoryList" :key="index">
        <h3>{{category.name}}:</h3>
        <v-row>
          <v-col v-for="(item, i) in computedList(category.cid)" :key="i" cols="12" sm="12" md="8">
            <v-card :style="{backgroundColor: colors.card}" outlined>
              <v-card-title :style="{color: colors.icon}" class="title">{{item.name}}</v-card-title>
              <v-list :style="{backgroundColor: colors.card}">
                <v-list-item>
                  <v-icon class="mr-2" :color="colors.icon">mdi-phone</v-icon>
                  <v-list-item-text :style="{color: colors.icon}">{{item.phone}}</v-list-item-text>
                </v-list-item>
                <v-list-item>
                  <v-icon :color="colors.icon" class="mr-2">mdi-information-outline</v-icon>
                  <v-list-item-text
                    :style="{color: colors.icon}"
                  >{{item.short_description || `We\'re ${item.name}!`}}</v-list-item-text>
                </v-list-item>
              </v-list>
            </v-card>
          </v-col>
        </v-row>
      </div>
    </v-container>
  </div>
</template>

<script>
export default {
  data: () => ({
    loading: true,
    categoryList: [],
    businessList: []
  }),
  mounted() {
    this.$vuetify.theme.dark = this.$store.state.app.dark;
    this.getCategoryList();
    this.getBusinessList();
  },
  methods: {
    async getBusinessList() {
      const body = await this.axios(`/customer/businesses`);
      const data = body.data;

      this.businessList = data;
      this.loading = false;
    },
    async getCategoryList() {
      const body = await this.axios(`/category/used`);
      const data = body.data;
      this.categoryList = data;
    },
    computedList(categoryId) {
      return this.businessList.filter(
        val =>
          val.cid == categoryId && val.location == this.$auth.user().location
      );
    }
  },
  computed: {
    colors() {
      let isDark = this.$store.state.app.dark;
      const colors = {
        card: isDark ? "#121212" : "#FFFFFF",
        icon: !isDark ? "#000000" : "#FFFFFF",
        dark: isDark
      };
      return colors;
    }
  }
};
</script>
<style>
.v-overflow-btn .v-input__slot::before {
  border-color: grey !important;
}
</style>