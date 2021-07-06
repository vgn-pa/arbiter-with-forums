<template>
    <main class="my-3">
      <div class="row justify-content-center">
          <div class="col-md-12">
              <div class="card card-default has-table">
                  <div class="card-header">Incoming fleets - <router-link :to="{ name: 'galaxy', params: { id: galaxy.id }}"><a>{{ galaxy.name }}</a></router-link></div>

                  <div class="card-body">
                      <preloader :loading.sync="loading"></preloader>
                      <incoming-component v-bind:tick="settings.tick" :incoming.sync="fleets" v-if="!loading"></incoming-component>
                  </div>
              </div>
          </div>
      </div>
    </main>
</template>

<script>
    export default {
        props: ['settings'],
        data() {
            return {
                galaxy: {},
                fleets: {},
                page: 1,
                type: 'galaxy',
                loading: true
            };
        },
        methods: {
            loadData: function() {
                this.loading = true;
                axios.get('/api/v1/fleets', {
                    params: {
                        page: this.page,
                        type: this.type,
                        movement: 'incoming',
                        id: this.galaxy.id,
                        limit: 999999
                    }
                }).then((response) => {
                    this.fleets = response.data.data;
                    this.loading = false;
                });
            },
            loadGalaxy: function() {
                axios.get('/api/v1/galaxies/' + this.$route.params.id + '?all=1')
                .then((response) => {
                    this.galaxy = response.data;
                    this.loadData();
                });
            },
        },
        watch: {
           '$route': 'loadGalaxy'
        },
        mounted() {
            this.loadGalaxy();
        },
    }
</script>
