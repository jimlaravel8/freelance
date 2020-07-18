<template>
<v-row justify="center">
    <v-dialog v-model="dialog" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline">Promo Message: Here you can send an alert or promotional message to all customers who earn points from you.</span>
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12">
                            <v-textarea counter :rules="rules" label="Message" v-model="form.promo_message" @input="assertMaxChars"></v-textarea>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                <v-btn color="blue darken-1" text @click="save" :loading="loading" :disabled="loading">Send</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <v-snackbar v-model="snackbar">
        {{ text }}
        <template v-slot:action="{ attrs }">
            <v-btn color="pink" text v-bind="attrs" @click="snackbar = false">
                Close
            </v-btn>
        </template>
    </v-snackbar>
</v-row>
</template>

<script>
export default {
    data: () => ({
        dialog: false,
        form: {
            promo_message: '',
        },
        snackbar: false,
        text: 'Message sent',
        loading: false,
        rules: [v => v.length <= 200 || 'Max 200 characters'],
        maxTextLength: 200

    }),

    created() {
        eventBus.$on('sendMessageEvent', data => {
            this.dialog = true
        })
    },

    methods: {
        assertMaxChars() {
            console.log(this.form.promo_message.length + '::::::' + this.maxTextLength);

            if (this.form.promo_message.length >= this.maxTextLength) {
                this.form.promo_message = this.form.promo_message.substring(0, this.maxTextLength);
            }
        },
        save() {
            this.loading = true
            this.axios.post("/business/promo", this.form)
                .then(res => {
                    this.loading = false
                    // console.log(res);

                    if (res.data.status === "success") {
                        this.snackbar = true
                        // let action = this.uuid === null ? "item_created" : "item_saved";
                        this.dialog = false
                        this.form.promo_message = ''

                        eventBus.$emit('refreshDataEvent')
                        // this.$root.$snackbar(this.$t(action));
                        // this.$emit("data-list-events", { closeDialog: true, reload: true });
                    }
                })
                .catch(err => {
                    this.snackbar = true
                    this.text = 'Something went wrong',
                        this.loading = false
                    console.log(err);

                })
        }
    },
}
</script>
