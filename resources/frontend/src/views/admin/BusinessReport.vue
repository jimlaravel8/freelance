<template>
<div class="viewContainer">
    <v-container class="pt-2 pb-4">
        <v-row no-gutters>
            <v-col cols="12" sm="12" md="12" lg="12" xl="12">
                <v-card>
                    <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="form.date" transition="scale-transition" offset-y max-width="290px" min-width="290px">
                        <template v-slot:activator="{ on, attrs }">
                            <v-text-field v-model="form.date" label="Picker in date" prepend-icon="mdi-calendar" v-bind="attrs" v-on="on"></v-text-field>
                        </template>
                        <v-date-picker v-model="form.date" no-title scrollable range>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                            <v-btn text color="primary" @click="filter_data">OK</v-btn>
                        </v-date-picker>
                    </v-menu>
                </v-card>
                <v-col cols="12">
                    <v-card>
                        <v-card-title>
                            Business Report
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="business_report" :search="search"></v-data-table>
                    </v-card>
                </v-col>
        </v-row>
    </v-container>
</div>
</template>

<script>
export default {
    data() {
        return {
            search: '',
            headers: [{
                    text: 'Name',
                    value: 'name'
                },
                {
                    text: 'phone',
                    value: 'phone'
                },
                {
                    text: 'Category',
                    value: 'category'
                },
                {
                    text: 'Billings summary',
                    value: 'billings'
                }
            ],
            form: {
                date: ['2020-08-10', '2020-08-20'],
                // date: new Date().toISOString().substr(0, 10),
            },
            menu: false,
            business_report: [],
        }
    },

    methods: {
        loadReport() {
            this.axios.post("/admin/business_report", this.form)
                .then(response => {
                    let stats = response.data;
                    this.business_report = stats;

                    // this.overlay = false;
                    // this.loading = false;
                });
        },
    },
    mounted() {
        this.loadReport();
    },
}
</script>
