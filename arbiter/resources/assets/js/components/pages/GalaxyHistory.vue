<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card card-default has-table">
                    <div class="card-header">History - <router-link :to="{ name: 'galaxy', params: { id: galaxy.id }}"><a>{{ galaxy.x }}:{{ galaxy.y }}</a></router-link></div>

                    <div class="card-body">
                      <table id="galaxies" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Tick</th>
                                <th class="text-right">Rank</th>
                                <th class="text-right">Planets</th>
                                <th class="text-center" colspan="2">Size</th>
                                <th class="text-center" colspan="2">Value</th>
                                <th class="text-center" colspan="2">Score</th>
                                <th class="text-center" colspan="2">Xp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="gal in history.data">
                                <td class="text-right">{{ gal.tick.tick }}</td>
                                <td class="text-right">{{ gal.rank_score }}</td>
                                <td class="text-right">{{ galaxy.planets_count }}</td>
                                <td class="text-right" style="width: 20%">{{ gal.size }}</td>
                                <td class="text-right" v-bind:class="{ positive: Math.sign(gal.change_size) == 1, negative: Math.sign(gal.change_size) == -1 }">
                                    <span v-if="gal.change_size != 0">{{ gal.change_size.toLocaleString() }}</span>
                                </td>
                                <td class="text-right" style="width: 20%">{{ gal.value }}</td>
                                <td class="text-right" v-bind:class="{ positive: Math.sign(gal.change_value) == 1, negative: Math.sign(gal.change_value) == -1 }">
                                    <span v-if="gal.change_value != 0">{{ gal.change_value.toLocaleString() }}</span>
                                </td>
                                <td class="text-right" style="width: 20%">{{ gal.score }}</td>
                                <td class="text-right" v-bind:class="{ positive: Math.sign(gal.change_score) == 1, negative: Math.sign(gal.change_score) == -1 }">
                                    <span v-if="gal.change_score != 0">{{ gal.change_score.toLocaleString() }}</span>
                                </td>
                                <td class="text-right" style="width: 20%">{{ gal.xp }}</td>
                                <td class="text-right" v-bind:class="{ positive: Math.sign(gal.change_xp) == 1, negative: Math.sign(gal.change_xp) == -1 }">
                                    <span v-if="gal.change_xp != 0">{{ gal.change_xp.toLocaleString() }}</span>
                                </td>
                                <td class="text-right" style="width: 20%">{{ gal.tick.created_at }}</td>
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
                'galaxy': {
                    'id': 0,
                    'planets': {}
                },
                'history' : {}
            };
        },
        methods: {
            loadGalaxy: function() {
              axios.get('/api/v1/galaxies/' + this.$route.params.id)
              .then((response) => {
                  this.galaxy = response.data;
              });
            },
            loadHistory: function() {
                axios.get('/api/v1/galaxies/' + this.$route.params.id + '/history', {
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
            this.loadGalaxy();
            this.loadHistory();
        },
    }
</script>
