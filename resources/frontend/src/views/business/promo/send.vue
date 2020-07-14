<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" persistent max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Promo Message</span>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-textarea
                    label="Message"
                    v-model="form.promo_message"
                    ></v-textarea>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
          <v-btn color="blue darken-1" text @click="save">Send</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
  export default {
    data: () => ({
      dialog: false,
      form: {
          promo_message: '',
      }
    }),

    created () {
        eventBus.$on('sendMessageEvent', data => {
            this.dialog = true
        })
    },

    methods: {
        save() {
      this.axios.post("/business/promo", this.form)
        .then(res => {
            console.log(res);
            
          if (res.data.status === "success") {
            // let action = this.uuid === null ? "item_created" : "item_saved";
            // this.$root.$snackbar(this.$t(action));
            // this.$emit("data-list-events", { closeDialog: true, reload: true });
          }
        })
        .catch(err => {
            console.log(err);
            
        })
        .finally(() => {
          that.loading = false;
          that.form.loading = false;
        }); 
        }
    },
  }
</script>