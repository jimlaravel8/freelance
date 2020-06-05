<template>
  <div class="viewContainer">
    <v-container>
      <v-row>
        <v-col class="text-center my-10 py-10">
          <h1 class="display-4 my-10">Forbidden</h1>
          <h2 class="title mx-auto my-10" style="max-width: 800px">You do not have access to this page. If you think you should have permission to access the requested page, <a style="text-decoration: underline" @click="$auth.logout()">log out</a> and open the url again.</h2>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>