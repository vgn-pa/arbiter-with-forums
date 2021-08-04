<template>
    <div class="card card-default has-table">
        <div class="card-header">Biggest score loss</div>

        <div class="card-body">
            <preloader :loading.sync="loading"></preloader>
            <table id="members" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
                <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th class="align-bottom text-right">X:Y Z</th>
                      <th class="align-bottom text-right">Race</th>
                      <th class="align-bottom text-right">Score</th>
                      <th class="align-bottom text-right">Intel</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(planet, index) in pager.data" v-bind:class="{ is_alliance: planet.is_alliance }">
                        <td class="text-right">{{ planet.rank_score }} <span v-if="planet.growth_rank_score > 0" v-tooltip:top="'up ' + planet.growth_rank_score + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_score < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_score) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_score == 0"><i class="fa fa-caret-right"></i></span></td>
                        <td class="text-right"><router-link :to="{ name: 'galaxy', params: { id: planet.galaxy_id }}"><a>{{ planet.x }}:{{ planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: planet.id }}"><a>{{ planet.z }}</a></router-link></td>
                        <td class="text-right"><span class="race" v-bind:class="planet.race">{{ planet.race }}</span></td>
                        <td class="text-right">{{ planet.score | shorten }} (<span v-bind:class="{ yellow: planet.growth_score == 0, green: planet.growth_score > 0, red: planet.growth_score < 0 }">{{ planet.growth_score | shorten }}</span>)</td>
                        <td class="text-right">{{ planet.nick }} <span v-if="planet.nick && planet.alliance"> </span><router-link v-if="planet.alliance" :to="{ name: 'alliance', params: { id: planet.alliance.id }}"><a>{{ planet.alliance.name }}</a></router-link></td>
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
              sort: '+growth_score',
              loading: true
          };
      },
      methods: {
          loadPlanets: function() {
              axios.get('api/v1/planets', {
                  params: {
                      sort: this.sort,
                      page: 1,
                      perPage: 5,
                      exclude_c200: true
                  }
              })
              .then((response) => {
                  this.pager = response.data;
                  this.loading = false;
              });
          }
      },
      mounted() {
          this.loadPlanets();
      }
    }
</script>
