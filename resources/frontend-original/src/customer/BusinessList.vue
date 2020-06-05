<template>
  <div class="viewContainer">
    <v-container class="content-container py-1">
      <v-row>
        <h2>These businesses are authorized to issue and redeem Piggy Bank Points.</h2>
      </v-row>
      <div v-for="(category, index) in categoryList" :key="index">
        <h3>{{category.name}}:</h3>
        <v-row dense>
          <v-col v-for="(item, i) in computedList(category.name)" :key="i" cols="8" sm="8" xl="8">
            <v-card dark>
              <v-card-title class="headline">{{item.name}}</v-card-title>
              <v-list-item two-line>
                <v-list-item-content>
                  <v-list-item-title>Contact:</v-list-item-title>
                  <v-list-item-subtitle>{{item.phone}}</v-list-item-subtitle>
                  <v-list-item-subtitle>{{item.short_description}}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
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
      const body = await this.axios(`/category/get/u`);
      const data = body.data;
      this.categoryList = data;
    },
    computedList(category) {
      return this.businessList.filter(val => val.category == category);
    }
  }
};
</script>