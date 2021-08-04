<template>
    <div class="card card-default has-table">
        <div class="card-header">Biggest roid loss</div>

        <div class="card-body">
            <preloader :loading.sync="loading"></preloader>
            <table id="members" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
              <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="align-bottom text-right">Name</th>
                    <th class="align-bottom text-right">Size</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="(alliance, index) in pager.data" v-bind:class="{ is_alliance: alliance.is_alliance }">
                      <td class="text-right">{{ alliance.rank_size }} <span v-if="alliance.growth_rank_size > 0" v-tooltip:top="'up ' + alliance.growth_rank_size + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_size < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_size) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_size == 0"><i class="fa fa-caret-right"></i></span></td>
                      <td style="width: 100%; white-space: normal"><router-link :to="{ name: 'alliance', params: { id: alliance.id }}"><a>{{ alliance.name }}</a></router-link></td>
                      <td class="text-right">{{ alliance.size | shorten }} (<span v-bind:class="{ yellow: alliance.growth_size == 0, green: alliance.growth_size > 0, red: alliance.growth_size < 0 }">{{ alliance.growth_size | shorten }}</span>)</td>
                  </tr>
              </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
      data: function () {
          return {
              pager: {},
              sort: '+growth_size',
              loading: true
          };
      },
      methods: {
          loadGalaxies: function() {
              axios.get('api/v1/alliances', {
                  params: {
                      sort: this.sort,
                      page: 1,
                      perPage: 5
                  }
              })
              .then((response) => {
                  this.pager = response.data;
                  this.loading = false;
              });
          }
      },
      mounted() {
          this.loadGalaxies();
      }
    }
</script>
