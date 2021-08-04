<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card card-default mb-3 has-table">
                    <div class="card-header">History <router-link :to="{ name: 'alliance', params: { id: alliance.id }}" v-if="!loadingAlliance"><a>{{ alliance.name }}</a></router-link></div>

                    <div class="card-body">
                        <preloader :loading.sync="loadingHistory"></preloader>
                        <table id="alliances" class="table table-striped table-bordered" style="width:100%" v-if="!loadingHistory">
                            <thead>
                                <tr>
                                    <th>Tick</th>
                                    <th class="text-right">Rank</th>
                                    <th class="text-right">Members</th>
                                    <th class="text-center" colspan="2">Size</th>
                                    <th class="text-center" colspan="2">Value</th>
                                    <th class="text-center" colspan="2">Score</th>
                                    <th class="text-right">Date / Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="alliance in history">
                                    <td class="text-right">{{ alliance.tick.tick }}</td>
                                    <td class="text-right">{{ alliance.rank_score }}
                                    <td class="text-right">{{ alliance.members }} <span v-if="alliance.change_members != 0">(<span v-bind:class="{ positive: Math.sign(alliance.change_members) == 1, negative: Math.sign(alliance.change_members) == -1 }">{{ alliance.change_members }}</span>)</span></td>
                                    <td class="text-right" style="width: 25%">{{ alliance.size }}</td>
                                    <td class="text-right" v-bind:class="{ positive: Math.sign(alliance.change_size) == 1, negative: Math.sign(alliance.change_size) == -1 }">
                                        <span v-if="alliance.change_size != 0">{{ alliance.change_size.toLocaleString() }}</span>
                                    </td>
                                    <td class="text-right" style="width: 25%">{{ alliance.total_value }}</td>
                                    <td class="text-right" v-bind:class="{ positive: Math.sign(alliance.change_value) == 1, negative: Math.sign(alliance.change_value) == -1 }">
                                        <span v-if="alliance.change_value != 0">{{ alliance.change_value.toLocaleString() }}</span>
                                    </td>
                                    <td class="text-right" style="width: 25%">{{ alliance.counted_score }}</td>
                                    <td class="text-right" v-bind:class="{ positive: Math.sign(alliance.change_score) == 1, negative: Math.sign(alliance.change_score) == -1 }">
                                        <span v-if="alliance.change_score != 0">{{ alliance.change_score.toLocaleString() }}</span>
                                    </td>
                                    <td class="text-right" style="width: 25%">{{ alliance.tick.created_at }}</td>
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
                history: {},
                alliance: {},
                loadingHistory: true,
                loadingAlliance: true
            };
        },
        methods: {
            loadAlliance: function() {
                axios.get('api/v1/alliances/' + this.$route.params.id)
                .then((response) => {
                    this.alliance = response.data;
                    this.loadingAlliance = false;
                });
            },
            loadHistory: function() {
                axios.get('api/v1/alliances/' + this.$route.params.id + '/history', {
                    params: {
                        all: 1
                    }
                })
                .then((response) => {
                    this.history = response.data.data;
                    this.loadingHistory = false;
                });
            }
        },
        mounted() {
            this.loadAlliance();
            this.loadHistory();
            
        },
    }
</script>
