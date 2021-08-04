<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default has-table mb-3" v-if="galaxy">
                    <div class="card-header">
                        {{ galaxy.name }} (<router-link :to="{ name: 'galaxy', params: { id: galaxy.id }}"><a>{{ galaxy.x }}:{{ galaxy.y }}</a></router-link>)
                        <div class="float-right">
                            <span v-if="galaxy.prev_gal"><router-link :to="{ name: 'galaxy', params: { id: galaxy.prev_gal.id }}"><i class="fas fa-arrow-left"></i></router-link></span>
                            <span v-if="galaxy.next_gal"><router-link :to="{ name: 'galaxy', params: { id: galaxy.next_gal.id }}"><i class="fas fa-arrow-right"></i></router-link></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <preloader :loading.sync="loading"></preloader>
                        <table id="members" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
                            <thead>
                                <tr>
                                  <th class="text-center" colspan="4">Rank</th>
                                  <th class="text-right" rowspan="2">X:Y</th>
                                  <th rowspan="2">Ruler/Planet</th>
                                  <th rowspan="2">Race</th>
                                  <th class="text-right" rowspan="2">Size</th>
                                  <th class="text-right" rowspan="2">Value</th>
                                  <th class="text-right" rowspan="2">Score</th>
                                  <th class="text-right" rowspan="2">Xp</th>
                                  <th class="text-center" colspan="3">Growth</th>
                                  <th rowspan="2">Nick</th>
                                  <th rowspan="2">Alliance</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Score</th>
                                    <th class="text-center">Value</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Xp</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Value</th>
                                    <th class="text-center">Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(planet, index) in galaxy.planets" v-bind:class="{ is_alliance: planet.is_alliance }">
                                    <td class="text-right">{{ planet.rank_score }} <span v-if="planet.growth_rank_score > 0" v-tooltip:top="'up ' + planet.growth_rank_score + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_score < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_score) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_score == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ planet.rank_value }} <span v-if="planet.growth_rank_value > 0" v-tooltip:top="'up ' + planet.growth_rank_value + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_value < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_value) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_value == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ planet.rank_size }} <span v-if="planet.growth_rank_size > 0" v-tooltip:top="'up ' + planet.growth_rank_size + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_size < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_size) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_size == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ planet.rank_xp }} <span v-if="planet.growth_rank_xp > 0" v-tooltip:top="'up ' + planet.growth_rank_xp + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_xp < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_xp) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_xp == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right"><router-link :to="{ name: 'galaxy', params: { id: planet.galaxy_id }}"><a>{{ planet.x }}:{{ planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: planet.id }}"><a>{{ planet.z }}</a></router-link></td>
                                    <td><i class="fas fa-shield-alt" v-if="planet.is_protected" style="margin-right: 5px;" v-tooltip:top="'In protection for ' + (24-planet.age) + ' more tick(s)'"></i><span v-tooltip:top="planet.ruler_name + ' of ' + planet.planet_name">{{ planet.ruler_name + " of " + planet.planet_name | ellipsis('30') }}</span></td>
                                    <td><span class="race" v-bind:class="planet.race">{{ planet.race }}</span></td>
                                    <td class="text-right"><span v-tooltip:top="planet.size.toLocaleString()">{{ planet.size | shorten }}</span></td>
                                    <td class="text-right" v-bind:class="{ basher: planet.basher }"><span v-tooltip:top="planet.value.toLocaleString()">{{ planet.value | shorten }}</span></td>
                                    <td class="text-right" v-bind:class="{ basher: planet.basher }"><span v-tooltip:top="planet.score.toLocaleString()">{{ planet.score | shorten }}</span></td>
                                    <td class="text-right"><span v-tooltip:top="planet.xp.toLocaleString()">{{ planet.xp | shorten }}</span></td>
                                    <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_size == 0.0, green: planet.growth_percentage_size > 0.0, red: planet.growth_percentage_size < 0.0 }"><span v-tooltip:top="planet.growth_size.toLocaleString()">{{ planet.growth_percentage_size | shorten }}%</span></td>
                                    <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_value == 0.0, green: planet.growth_percentage_value > 0.0, red: planet.growth_percentage_value < 0.0 }"><span v-tooltip:top="planet.growth_value.toLocaleString()">{{ planet.growth_percentage_value | shorten }}%</span></td>
                                    <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_score == 0.0, green: planet.growth_percentage_score > 0.0, red: planet.growth_percentage_score < 0.0 }"><span v-tooltip:top="planet.growth_score.toLocaleString()">{{ planet.growth_percentage_score | shorten }}%</span></td>
                                    <td>{{ planet.nick }}</td>
                                    <td><router-link v-if="planet.alliance" :to="{ name: 'alliance', params: { id: planet.alliance.id }}"><a>{{ planet.alliance.name }}</a></router-link></td>
                                </tr>
                                <tr class="totals">
                                    <td class="text-right">{{ galaxy.rank_score }} <span v-if="galaxy.growth_rank_score > 0" v-tooltip:top="'up ' + galaxy.growth_rank_score + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="galaxy.growth_rank_score < 0" v-tooltip:top="'down ' + Math.abs(galaxy.growth_rank_score) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="galaxy.growth_rank_score == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ galaxy.rank_value }} <span v-if="galaxy.growth_rank_value > 0" v-tooltip:top="'up ' + galaxy.growth_rank_value + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="galaxy.growth_rank_value < 0" v-tooltip:top="'down ' + Math.abs(galaxy.growth_rank_value) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="galaxy.growth_rank_value == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ galaxy.rank_size }} <span v-if="galaxy.growth_rank_size > 0" v-tooltip:top="'up ' + galaxy.growth_rank_size + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="galaxy.growth_rank_size < 0" v-tooltip:top="'down ' + Math.abs(galaxy.growth_rank_size) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="galaxy.growth_rank_size == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ galaxy.rank_xp }} <span v-if="galaxy.growth_rank_xp > 0" v-tooltip:top="'up ' + galaxy.growth_rank_xp + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="galaxy.growth_rank_xp < 0" v-tooltip:top="'down ' + Math.abs(galaxy.growth_rank_xp) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="galaxy.growth_rank_xp == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ galaxy.planets.length }}</td>
                                    <td colspan="2"></td>
                                    <td class="text-right"><span v-tooltip:top="galaxy.size.toLocaleString()">{{ galaxy.size | shorten }}</span></td>
                                    <td class="text-right"><span v-tooltip:top="galaxy.value.toLocaleString()">{{ galaxy.value | shorten }}</span></td>
                                    <td class="text-right"><span v-tooltip:top="galaxy.score.toLocaleString()">{{ galaxy.score | shorten }}</span></td>
                                    <td class="text-right"><span v-tooltip:top="galaxy.xp.toLocaleString()">{{ galaxy.xp | shorten }}</span></td>
                                    <td class="text-right" v-bind:class="{ yellow: galaxy.growth_percentage_size == 0.0, green: galaxy.growth_percentage_size > 0.0, red: galaxy.growth_percentage_size < 0.0 }"><span v-tooltip:top="galaxy.growth_size.toLocaleString()">{{ galaxy.growth_percentage_size | shorten }}%</span></td>
                                    <td class="text-right" v-bind:class="{ yellow: galaxy.growth_percentage_value == 0.0, green: galaxy.growth_percentage_value > 0.0, red: galaxy.growth_percentage_value < 0.0 }"><span v-tooltip:top="galaxy.growth_value.toLocaleString()">{{ galaxy.growth_percentage_value | shorten }}%</span></td>
                                    <td class="text-right" v-bind:class="{ yellow: galaxy.growth_percentage_score == 0.0, green: galaxy.growth_percentage_score > 0.0, red: galaxy.growth_percentage_score < 0.0 }"><span v-tooltip:top="galaxy.growth_score.toLocaleString()">{{ galaxy.growth_percentage_score | shorten }}%</span></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <outgoing-summary :type="'galaxy'" :id.sync="galaxy.id"></outgoing-summary>
            </div>
            <div class="col-md-6">
                <incoming-summary :type="'galaxy'" :id.sync="galaxy.id"></incoming-summary>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <galaxy-history :id.sync="galaxy.id"></galaxy-history>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <planet-movements :galaxyId.sync="galaxy.id"></planet-movements>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
        data() {
            return {
                'galaxy': {
                    id: this.$route.params.id,
                    current_galaxy: {},
                    planets: {},
                    incoming: {},
                    outgoing: {},
                    history:{}
                },
                'type': 'galaxy',
                'loading': true
            };
        },
        methods: {
            loadGalaxy: function() {
                this.loading = true;
                axios.get('api/v1/galaxies/' + this.$route.params.id)
                .then((response) => {
                    this.galaxy = response.data;
                    this.loading = false;
                });
            }
        },
        watch: {
           '$route': 'loadGalaxy'
        },
        mounted() {
            this.loadGalaxy();
        },
    }
</script>
