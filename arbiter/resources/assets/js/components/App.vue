<template>

    <div id="app">

        <nav-menu :links.sync="links" :settings.sync="settings"></nav-menu>

        <search-component :settings.sync="settings"></search-component>

        <div class="app-content">

            <router-view :key="$route.fullPath" :settings.sync="settings"></router-view>

            <notifications group="foo" position="bottom right" />

        </div>

    </div>

</template>

<script>
    export default {
      name: 'App',
      data() {
          return {
              user: "",
              links: [],
              appName: "",
              settings: {
                  tick: '',
                  user: {
                      name: '',
                      is_admin: '',
                      planet: {}
                  }
              }
          }
      },
      methods: {
          loadSettings: function() {
              axios.get('api/v1/settings').then((response) => {
                  this.settings = response.data;
              });
          }
      },
      watch: {
          $route: function(to, from) {
              this.loadSettings();
          }
      },
      mounted() {
          this.loadSettings();
      }
    }
</script>
