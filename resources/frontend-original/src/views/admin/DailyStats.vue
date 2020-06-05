<template>
  <div class="viewContainer">
    <v-container class="pt-2 pb-4">
      <v-row no-gutters>
        <v-col cols="12" sm="12" md="12" lg="12" xl="6">
          <GChart type="ColumnChart" :data="chartDataEarned" :options="chartOptionsEarned" />
        </v-col>
        <v-col cols="12" sm="12" md="12" lg="12" xl="6">
          <GChart type="ColumnChart" :data="chartDataSpent" :options="chartOptionsSpent" />
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import axios from "axios";
import { GChart } from "vue-google-charts";
export default {
  components: {
    GChart
  },
  data: () => ({
    chartHeader: ["Hour", "Points"],
    chartDataEarned: [],
    chartDataSpent: [],
    chartOptionsEarned: {
      hAxis: {
        title: "Point Earnings Today",
        0: { baseline: 0 }
      },
      vAxes: {
        0: { baseline: 0 }
      },
      height: 500,
      colors: ["#e57"]
    },
    chartOptionsSpent: {
      hAxis: {
        title: "Point Redeems Today",
        0: { baseline: 0 }
      },
      vAxes: {
        0: { baseline: 0 }
      },
      height: 500,
      colors: ["#ee7"]
    }
  }),
  async beforeMount() {
    this.getDailyPoints();
  },
  mounted() {
    console.log(this.chartData);
  },
  methods: {
    async getDailyPoints() {
      const body = await axios.get(`/admin/dailypoints`);
      const data = body.data;
      const data_earned = [[...this.chartHeader]];
      const data_spent = [[...this.chartHeader]];
      for (let i in data) {
        if (!isNaN(data[i].points_earned)) {
          data_earned.push([data[i].hour, parseInt(data[i].points_earned)]);
        } else {
          data_spent.push([data[i].hour, parseInt(data[i].points_spent)]);
        }
      }
      this.chartDataEarned = data_earned;
      this.chartDataSpent = data_spent;
    },
    async getTotalCustomers() {
      const body = await axios.get(`/admin/customers`);
      const data = body.data;
      this.totalCustomers = data;
    },
    async getTotalBusinesses() {
      const body = await axios.get(`/admin/businesses`);
      const data = body.data;
      this.totalBusinesses = data;
    }
  }
};
</script>