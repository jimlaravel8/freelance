<template>
<div class="viewContainer">
    <v-container class="pt-2 pb-4">
        <v-row no-gutters>
            <v-col cols="12">

                <v-row>
                    <v-col sm2>
                        <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="date" transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field v-model="form.start_date" label="Picker in menu" prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="form.start_date" no-title scrollable>
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" @click="menu = false">
                                    Cancel
                                </v-btn>
                                <v-btn text color="primary" @click="$refs.menu.save(form.start_date)">
                                    OK
                                </v-btn>
                            </v-date-picker>
                        </v-menu>
                    </v-col>
                    <v-col sm2>
                        <v-menu ref="menu" v-model="menu_1" :close-on-content-click="false" :return-value.sync="date" transition="scale-transition" offset-y min-width="auto">
                            <template v-slot:activator="{ on, attrs }">
                                <v-text-field v-model="form.end_date" label="Picker in menu" prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="form.end_date" no-title scrollable>
                                <v-spacer></v-spacer>
                                <v-btn text color="primary" @click="menu = false">
                                    Cancel
                                </v-btn>
                                <v-btn text color="primary" @click="$refs.menu.save(form.end_date)">
                                    OK
                                </v-btn>
                            </v-date-picker>
                        </v-menu>
                    </v-col>

                    <v-col sm2>
                        <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on" @click="getData">
                                    <v-icon color="grey lighten-1">
                                        mdi-magnify
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Filter</span>
                        </v-tooltip>
                    </v-col>
                </v-row>

                <!-- <v-card>
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Maiores recusandae dolorum, veritatis voluptatem tempora, expedita eum sapiente quibusdam aut repellat autem velit culpa commodi animi nostrum labore, porro harum voluptate?
                    {{ business }}
                </v-card> -->

                <v-card>
                    <v-card-title>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn icon v-bind="attrs" v-on="on" @click="getData">
                                    <v-icon color="grey lighten-1">
                                        mdi-refresh
                                    </v-icon>
                                </v-btn>
                            </template>
                            <span>Filter</span>
                        </v-tooltip>
                        <!-- <v-btn text icon color="primary" @click="getData">
                            <v-icon>mdi-refresh</v-icon>
                        </v-btn> -->
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <!-- <v-data-table :headers="headers" :items="business" :search="search"></v-data-table> -->
                    <v-data-table :headers="headers" :items="business" :items-per-page="10" class="elevation-1" :loading="loading" :search="search"></v-data-table>
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
            date: new Date().toISOString().substr(0, 10),
            menu: false,
            menu_1: false,
            business: [],
            form: {},
            search: '',
            headers: [{
                text: 'Business Name',
                value: 'name',
            }, {
                text: 'Phone',
                value: 'phone',
            }, {
                text: 'Created On',
                value: 'created_at',
            }, ],
        }
    },

    methods: {
        getData() {
            this.axios.post('/admin/business_filter', this.form).then((response) => {
                this.business = response.data
                console.log(this.business);
            }).catch((error) => {
                console.log(error);
            })
        }
    },
    mounted() {
        this.getData();
    },
}
</script>
