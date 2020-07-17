<template>
<v-menu v-if="$auth.check()" offset-y bottom left origin="top right" :close-on-content-click="true" tile>
    <template v-slot:activator="{ on }">
        <div class="d-flex align-center">
            <v-app-bar-nav-icon v-on="on" color="NavFg">
                <v-icon>mdi-bell</v-icon>
            </v-app-bar-nav-icon>
        </div>
    </template>
    <v-card tile max-width="460">
        <v-list dense tile class="pt-0">
            <v-list-item-group>
                <template v-for="item in notifications">
                    <v-layout :key="item.id">
                        <v-subheader class="text-uppercase">Notifications</v-subheader>
                    </v-layout>

                    <v-list-item exact :key="item.id">
                        <v-list-item-content>
                            <v-list-item-title>
                                {{ item.data.notification }} | <b> {{ item.date }}</b>
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </template>
                <v-divider class="my-2" />
            </v-list-item-group>
        </v-list>
    </v-card>
</v-menu>
</template>
<script>
export default {
    props: ['notifications']
}
</script>
