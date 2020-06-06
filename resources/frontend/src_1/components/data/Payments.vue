<template>
  <v-dialog max-width="600px">
    <v-card>
      <v-toolbar-title>Check User Payments</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn
        flat
        icon
        color="primary"
        @click="$emit('data-payment', { closeDialog: true, reload: false })"
      >
        <v-icon>mdi-close</v-icon>
      </v-btn>
      <h2>Bak, şöyle yarrak yarrak şeyler yapmayın, skicem artık ama ha.</h2>
    </v-card>
  </v-dialog>
</template>

<script>
export default {};
</script>

<style>
</style>