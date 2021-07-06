<template>
    <main class="my-3">
      <div class="row justify-content-center">
          <div class="col-md-12">
              <div class="card card-default has-table">
                  <div class="card-header">Outgoing fleets - <router-link :to="{ name: 'alliance', params: { id: alliance.id }}"><a>{{ alliance.name }}</a></router-link></div>

                  <div class="card-body">
                      <preloader :loading.sync="loading"></preloader>
                      <outgoing-component v-bind:tick="settings.tick" :outgoing.sync="fleets" v-if="!loading"></outgoing-component>
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
                alliance: {},
                fleets: {},
                page: 1,
                type: 'alliance',
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
                        movement: 'outgoing',
                        id: this.alliance.id,
                        limit: 999999
                    }
                }).then((response) => {
                    this.fleets = response.data.data;
                    this.loading = false;
                });
            },
            loadAlliance: function() {
                axios.get('/api/v1/alliances/' + this.$route.params.id + '?all=1')
                .then((response) => {
                    this.alliance = response.data.alliance;
                    this.loadData();
                });
            }
        },
        watch: {
           '$route': 'loadAlliance'
        },
        mounted() {
            this.loadAlliance();
        },
    }
</script>
