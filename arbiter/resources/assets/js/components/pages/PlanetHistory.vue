<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default has-table">
                    <div class="card-header">History - <router-link :to="{ name: 'galaxy', params: { id: planet.galaxy_id }}"><a>{{ planet.x }}:{{ planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: planet.id }}"><a>{{ planet.z }}</a></router-link></div>

                    <div class="card-body">
                      <table id="planets" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-right align-middle">Tick</th>
                                <th class="text-right">Rank</th>
                                <th class="text-center" colspan="2">Size</th>
                                <th class="text-center" colspan="2">Value</th>
                                <th class="text-center" colspan="2">Score</th>
                                <th class="text-center" colspan="2">Xp</th>
                                <th class="text-right">Date / Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="planet in history.data">
                                <td class="text-right">{{ planet.tick.tick }}</td>
                                <td class="text-right">{{ planet.rank_score }}</td>
                                <td class="text-right" style="width: 20%">{{ planet.size.toLocaleString() }}</td>
                                <td class="text-right" v-bind:class="{ positive: Math.sign(planet.change_size) == 1, negative: Math.sign(planet.change_size) == -1 }">
                                    <span v-if="planet.change_size != 0">{{ planet.change_size.toLocaleString() }}</span>
                                </td>
                                <td class="text-right" style="width: 20%">{{ planet.value.toLocaleString() }}</td>
                                <td class="text-right" v-bind:class="{ positive: Math.sign(planet.change_value) == 1, negative: Math.sign(planet.change_value) == -1 }">
                                    <span v-if="planet.change_value != 0">{{ planet.change_value.toLocaleString() }}</span>
                                </td>
                                <td class="text-right" style="width: 20%">{{ planet.score.toLocaleString() }}</td>
                                <td class="text-right" v-bind:class="{ positive: Math.sign(planet.change_score) == 1, negative: Math.sign(planet.change_score) == -1 }">
                                    <span v-if="planet.change_score != 0">{{ planet.change_score.toLocaleString() }}</span>
                                </td>
                                <td class="text-right" style="width: 20%">{{ planet.xp.toLocaleString() }}</td>
                                <td class="text-right" v-bind:class="{ positive: Math.sign(planet.change_xp) == 1, negative: Math.sign(planet.change_xp) == -1 }">
                                    <span v-if="planet.change_xp != 0">{{ planet.change_xp.toLocaleString() }}</span>
                                </td>
                                <td class="text-right" style="width: 20%">{{ planet.tick.created_at }}</td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
        data() {
            return {
                'planet': {
                    id: 0,
                    galaxy_id: 0
                },
                'history': {}
            };
        },
        methods: {
            loadPlanet: function() {
                axios.get('/api/v1/planets/' + this.$route.params.id)
                .then((response) => {
                    this.planet = response.data;
                });
            },
            loadHistory: function() {
                axios.get('/api/v1/planets/' + this.$route.params.id + '/history', {
                    params: {
                        limit: 9999
                    }
                })
                .then((response) => {
                    this.history = response.data;
                });
            }
        },
        mounted() {
            this.loadPlanet();
            this.loadHistory();
        },
    }
</script>
