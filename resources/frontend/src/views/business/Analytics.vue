<template>
<div class="viewContainer">
    <v-container class="content-container py-1">
        <v-row>
            <v-col cols="12" sm="6" md="6" lg="6" xl="6">
                <v-card :loading="loading">
                    <v-card-text>
                        <h3>{{this.points_earned}}</h3>
                    </v-card-text>
                    <v-card-subtitle>
                        <p>Total points earned last 24h</p>
                        <p style="color: red">Total points issuance session: {{ this.count_issuance }}</p>
                    </v-card-subtitle>
                </v-card>
            </v-col>
            <v-col cols="12" sm="6" md="6" lg="6" xl="6">
                <v-card :loading="loading">
                    <v-card-text>
                        <h3>{{this.points_spent}}</h3>
                    </v-card-text>
                    <v-card-subtitle>
                        <p>Total points spent last 24h</p>
                        <p style="color: red">Total points redemption sessions: {{ this.count_redemption }}</p>
                    </v-card-subtitle>
                </v-card>
            </v-col>
            <!-- <v-col cols="12" sm="12" md="12" lg="4" xl="3">
                <v-card :loading="loading">
                    <v-card-text>
                        <h3>{{this.total_businesses}}</h3>
                    </v-card-text>
                    <v-card-subtitle>Total Businesses registered.</v-card-subtitle>
                    <br>
                    <br>
                </v-card>
            </v-col> -->
        </v-row>
        <v-row>
            <v-col cols="12" sm="6" md="6" lg="4" xl="3">
                <v-hover>
                    <template v-slot:default="{ hover }">
                        <v-card>
                            <v-skeleton-loader v-if="stats===null" type="card" />
                            <div v-if="stats!==null">
                                <v-responsive :aspect-ratio="2.4">
                                    <v-sparkline :labels="stats.userChartLabels" :value="stats.userChartValues" type="trend" smooth stroke-linecap="round" color="grey" line-width="3" padding="16" height="100%"></v-sparkline>
                                </v-responsive>
                                <v-divider />
                                <v-card-text>
                                    <h2 class="title">
                                        {{ $t('customers') }}
                                        <span>({{ stats.total.users }})</span>
                                    </h2>
                                    <span :class="{'red--text': stats.users.signupsChange < 0, 'green--text': stats.users.signupsChange > 0}">
                                        <v-icon size="14" v-if="stats.users.signupsChange < 0" :class="{'red--text': stats.users.signupsChange < 0, 'green--text': stats.users.signupsChange > 0}">mdi-arrow-down</v-icon>
                                        <v-icon size="14" v-if="stats.users.signupsChange > 0" :class="{'red--text': stats.users.signupsChange < 0, 'green--text': stats.users.signupsChange > 0}">mdi-arrow-up</v-icon>
                                        {{ formatNumber(stats.users.signupsChange) }}
                                    </span>
                                    {{ $t('vs_past_7_days') }}
                                </v-card-text>
                            </div>
                            <v-fade-transition>
                                <v-overlay v-if="hover" absolute>
                                    <!-- <v-btn x-large rounded :to="{ name: 'business.users' }" color="primary">
                                        {{ $t('more') }}
                                        <v-icon size="15" class="ml-1">mdi-arrow-right</v-icon>
                                    </v-btn> -->
                                </v-overlay>
                            </v-fade-transition>
                        </v-card>
                    </template>
                </v-hover>
            </v-col>
        </v-row>
    </v-container>
</div>
</template>

<script>
export default {
    data: () => ({
        locale: "en",
        stats: null,
        points_earned: 0,
        points_spent: 0,
        total_businesses: 0,
        loading: true
    }),
    created() {
        // Set locale
        this.locale = this.$auth.check() ?
            this.$auth.user().locale :
            Intl.DateTimeFormat().resolvedOptions().locale || this.$i18n.locale;
        this.moment.locale(this.locale.substr(0, 2));

        // Load dashboard stats
        this.loadStats();
        this.loadDailyStats();
    },
    methods: {
        loadStats() {
            this.axios
                .get("/business/stats", {
                    params: {
                        locale: this.$i18n.locale
                    }
                })
                .then(response => {
                    let stats = response.data;
                    this.stats = stats;

                    let userChartLabels = this.$_.map(
                        stats.users.signupsCurrentPeriod,
                        (count, date) => {
                            return this.moment(date).format("D");
                        }
                    );

                    let userChartValues = this.$_.map(
                        stats.users.signupsCurrentPeriod,
                        (count, date) => {
                            return count;
                        }
                    );

                    this.stats.userChartLabels = userChartLabels;
                    this.stats.userChartValues = userChartValues;

                    this.overlay = false;
                    this.loading = false;
                });
        },
        formatNumber(number) {
            return new Intl.NumberFormat(this.locale.replace("_", "-")).format(
                number
            );
        },
        async loadDailyStats() {
            const stats = await this.axios(`/business/dailypoints`);
            const data = stats.data;

            this.points_earned = parseInt(data[0].points_earned) || 0;
            this.points_spent = parseInt(data[1].points_spent) || 0;
            this.count_issuance = parseInt(data.count_issuance) || 0;
            this.count_redemption = parseInt(data.count_redemption) || 0;
            this.total_businesses = parseInt(data.total_businesses) || 0;

            this.loading = false;
        }
    }
};
</script>
