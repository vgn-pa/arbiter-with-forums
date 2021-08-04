<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-4 my-2">
                <widget-alliance-roid-gain></widget-alliance-roid-gain>
            </div>
            <div class="col-md-12 col-lg-4 my-2">
                <widget-alliance-value-gain></widget-alliance-value-gain>
            </div>
            <div class="col-md-12 col-lg-4 my-2">
                <widget-alliance-score-gain></widget-alliance-score-gain>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-4 my-2">
                <widget-alliance-roid-loss></widget-alliance-roid-loss>
            </div>
            <div class="col-md-12 col-lg-4 my-2">
                <widget-alliance-value-loss></widget-alliance-value-loss>
            </div>
            <div class="col-md-12 col-lg-4 my-2">
                <widget-alliance-score-loss></widget-alliance-score-loss>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default has-table mb-3">
                    <div class="card-header">
                        Alliances
                        <div class="float-right">
                            <span class="toggle-wrapper"><span class="toggle-label">Avg</span><toggle-button v-model="showAverages" class="toggle-button" /></span>
                            <span class="toggle-wrapper"><span class="toggle-label">Res</span><toggle-button v-model="showResources" class="toggle-button" /></span>
                        </div>
                    </div>

                    <div class="card-body">
                        <preloader :loading.sync="loading"></preloader>
                        <table id="alliances" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
                            <thead>
                                <tr>
                                    <th rowspan="2">#</th>
                                    <th class="text-center" colspan="3" v-if="!showAverages">Rank</th>
                                    <th class="text-center" colspan="3" v-if="showAverages">Avg Rank</th>
                                    <th rowspan="2">Name</th>
                                    <th class="text-right" rowspan="2"><sort-heading field="members" text="Members" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" colspan="3" v-if="showAverages">Avg</th>
                                    <th class="text-center" colspan="3" v-if="showAverages">Avg Growth</th>
                                    <th class="text-right" rowspan="2" v-if="!showAverages"><sort-heading field="size" text="Size" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-right" rowspan="2" v-if="!showAverages"><sort-heading field="value" text="Value" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-right" rowspan="2" v-if="!showAverages"><sort-heading field="score" text="Score" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" colspan="3" v-if="!showAverages">Growth</th>
                                    <th class="text-right" rowspan="2" v-if="showResources">Stock</th>
                                    <th class="text-right" rowspan="2" v-if="showResources">Prod</th>
                                </tr>
                                <tr>
                                    <th class="text-center" v-if="!showAverages"><sort-heading field="size" text="Size" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" v-if="!showAverages"><sort-heading field="value" text="Value" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" v-if="!showAverages"><sort-heading field="score" text="Score" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" v-if="showAverages"><sort-heading field="avg_size" text="Size" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" v-if="showAverages"><sort-heading field="avg_value" text="Value" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" v-if="showAverages"><sort-heading field="avg_score" text="Score" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-right" v-if="showAverages"><sort-heading field="avg_size" text="Size" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-right" v-if="showAverages"><sort-heading field="avg_value" text="Value" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-right" v-if="showAverages"><sort-heading field="avg_score" text="Score" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" v-if="showAverages"><sort-heading field="growth_avg_size" text="Size" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" v-if="showAverages"><sort-heading field="growth_avg_value" text="Value" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" v-if="showAverages"><sort-heading field="growth_avg_score" text="Score" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" v-if="!showAverages"><sort-heading field="growth_size" text="Size" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" v-if="!showAverages"><sort-heading field="growth_value" text="Value" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" v-if="!showAverages"><sort-heading field="growth_score" text="Score" :sort.sync="sort"></sort-heading></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(alliance,index) in pager.data" v-bind:class="{ is_alliance: alliance.is_alliance }">
                                    <td>{{ index+1 }}</td>
                                    <td class="text-right" v-if="!showAverages">{{ alliance.rank_size }} <span v-if="alliance.growth_rank_size > 0" v-tooltip:top="'up ' + alliance.growth_rank_size + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_size < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_size) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_size == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right" v-if="!showAverages">{{ alliance.rank_value }} <span v-if="alliance.growth_rank_value > 0" v-tooltip:top="'up ' + alliance.growth_rank_value + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_value < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_value) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_value == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right" v-if="!showAverages">{{ alliance.rank_score }} <span v-if="alliance.growth_rank_score > 0" v-tooltip:top="'up ' + alliance.growth_rank_score + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_score < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_score) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_score == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td v-if="showAverages" class="text-right">{{ alliance.rank_avg_size }} <span v-if="alliance.growth_rank_avg_size > 0" v-tooltip:top="'up ' + alliance.growth_rank_avg_size + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_avg_size < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_avg_size) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_avg_size == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td v-if="showAverages" class="text-right">{{ alliance.rank_avg_value }} <span v-if="alliance.growth_rank_avg_value > 0" v-tooltip:top="'up ' + alliance.growth_rank_avg_value + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_avg_value < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_avg_value) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_avg_value == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td v-if="showAverages" class="text-right">{{ alliance.rank_avg_score }} <span v-if="alliance.growth_rank_avg_score > 0" v-tooltip:top="'up ' + alliance.growth_rank_avg_score + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_avg_score < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_avg_score) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_avg_score == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td style="width: 100%"><span v-if="alliance.is_locked" style="margin-right:5px;"><i class="fa fa-lock" v-tooltip:top="'intel complete'"></i></span><router-link :to="{ name: 'alliance', params: { id: alliance.id }}"><a>{{ alliance.name }}</a></router-link></td>
                                    <td class="text-right"><span v-if="!alliance.is_locked"><span v-tooltip:top="'planets in intel'">{{ alliance.planets_count }}</span>/</span><span v-tooltip:top="'actual planets'">{{ alliance.members }}</span> <span v-if="alliance.growth_members != 0">(<span v-bind:class="{ positive: Math.sign(alliance.growth_members) == 1, negative: Math.sign(alliance.growth_members) == -1 }">{{ alliance.growth_members }}</span>)</span></td>
                                    <td v-if="showAverages" class="text-right"><span v-tooltip:top="alliance.avg_size.toLocaleString()">{{ alliance.avg_size | shorten }}</span></td>
                                    <td v-if="showAverages" class="text-right"><span v-tooltip:top="alliance.avg_value.toLocaleString()">{{ alliance.avg_value | shorten }}</span></td>
                                    <td v-if="showAverages" class="text-right"><span v-tooltip:top="alliance.avg_score.toLocaleString()">{{ alliance.avg_score | shorten }}</span></td>
                                    <td v-if="showAverages" class="text-right" v-bind:class="{ yellow: alliance.growth_percentage_avg_size == 0.0, green: alliance.growth_percentage_avg_size > 0.0, red: alliance.growth_percentage_avg_size < 0.0 }"><span v-tooltip:top="alliance.growth_avg_size.toLocaleString()">{{ alliance.growth_percentage_avg_size | shorten }}%</span></td>
                                    <td v-if="showAverages" class="text-right" v-bind:class="{ yellow: alliance.growth_percentage_avg_value == 0.0, green: alliance.growth_percentage_avg_value > 0.0, red: alliance.growth_percentage_avg_value < 0.0 }"><span v-tooltip:top="alliance.growth_avg_value.toLocaleString()">{{ alliance.growth_percentage_avg_value | shorten }}%</span></td>
                                    <td v-if="showAverages" class="text-right" v-bind:class="{ yellow: alliance.growth_percentage_avg_score == 0.0, green: alliance.growth_percentage_avg_score > 0.0, red: alliance.growth_percentage_avg_score < 0.0 }"><span v-tooltip:top="alliance.growth_avg_score.toLocaleString()">{{ alliance.growth_percentage_avg_score | shorten }}%</span></td>
                                    <td class="text-right" v-if="!showAverages"><span v-tooltip:top="alliance.size.toLocaleString()">{{ alliance.size | shorten }}</span></td>
                                    <td class="text-right" v-if="!showAverages"><span v-tooltip:top="alliance.value.toLocaleString()">{{ alliance.value | shorten }}</span></td>
                                    <td class="text-right" v-if="!showAverages"><span v-tooltip:top="alliance.score.toLocaleString()">{{ alliance.score | shorten }}</span></td>
                                    <td v-if="!showAverages" class="text-right" v-bind:class="{ yellow: alliance.growth_percentage_size == 0.0, green: alliance.growth_percentage_size > 0.0, red: alliance.growth_percentage_size < 0.0 }"><span v-tooltip:top="alliance.growth_size.toLocaleString()">{{ alliance.growth_percentage_size | shorten }}%</span></td>
                                    <td v-if="!showAverages" class="text-right" v-bind:class="{ yellow: alliance.growth_percentage_value == 0.0, green: alliance.growth_percentage_value > 0.0, red: alliance.growth_percentage_value < 0.0 }"><span v-tooltip:top="alliance.growth_value.toLocaleString()">{{ alliance.growth_percentage_value | shorten }}%</span></td>
                                    <td v-if="!showAverages" class="text-right" v-bind:class="{ yellow: alliance.growth_percentage_score == 0.0, green: alliance.growth_percentage_score > 0.0, red: alliance.growth_percentage_score < 0.0 }"><span v-tooltip:top="alliance.growth_score.toLocaleString()">{{ alliance.growth_percentage_score | shorten }}%</span></td>
                                    <td class="text-right" v-if="showResources"><span v-tooltip:top="alliance.hidden_resources.toLocaleString()">{{ alliance.hidden_resources | shorten }}</span></td>
                                    <td class="text-right" v-if="showResources"><span v-tooltip:top="alliance.prod_resources.toLocaleString()">{{ alliance.prod_resources | shorten }}</span></td>
                                </tr>
                            </tbody>
                            <tfoot><tr><td colspan="100"><pager :pager.sync="pager" v-if="!loading"></pager></td></tr></tfoot>
                        </table>
                    </div>
                </div>

                <div class="card card-default has-table mb-3" v-if="intel.length">
                    <div class="card-header">
                        Intel changes<br/>
                        <small>Add these ingame and mark as seen</small>
                    </div>

                    <div class="card-body">
                        <table id="alliances" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Tick</th>
                                    <th>Co-ords</th>
                                    <th>Ally From</th>
                                    <th>Ally To</th>
                                    <th v-if="settings.role == 'Admin'"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(change,index) in intel" v-if="change.planet">
                                    <td>{{ change.tick }}</td>
                                    <td><span><router-link :to="{ name: 'galaxy', params: { id: change.planet.galaxy_id }}"><a>{{ change.planet.x }}:{{ change.planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: change.planet.id }}"><a>{{ change.planet.z }}</a></router-link></span></td>
                                    <td><span v-if="change.alliance_from_id && change.alliance_from"><router-link :to="{ name: 'alliance', params: { id: change.alliance_from.id }}"><a>{{ change.alliance_from.name }}</a></router-link></span></td>
                                    <td><span v-if="change.alliance_to_id && change.alliance_to"><router-link :to="{ name: 'alliance', params: { id: change.alliance_to.id }}"><a>{{ change.alliance_to.name }}</a></router-link></span></td>
                                    <td v-if="settings.role == 'Admin'" class="options"><button class="btn btn-sm btn-danger" v-on:click="seenIntel(change.id)"><i class="fa fa-eye"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card card-default">
                    <div class="card-header">
                        Allianceless
                    </div>

                    <div class="card-body">
                        <router-link :to="{ name: 'alliance', params: { id: 0 }}"><a>Allianceless</a></router-link>
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
                'alliances': {},
                'sort': '-score',
                'intel': {},
                'loading': true,
                'pager': {},
                'showAverages': false,
                'showResources': false
            };
        },
        methods: {
            loadAlliances: function() {
                this.loading = true;
                axios.get('api/v1/alliances', {
                    params: {
                        sort: this.sort
                    }
                })
                .then((response) => {
                    this.loading = false;
                    this.pager = response.data;
                });
            },
            loadIntel: function() {
                axios.get('api/v1/intel')
                .then((response) => {
                    this.intel = response.data;
                });
            },
            seenIntel: function(id) {
                axios.get('api/v1/intel/' + id + '/seen')
                .then((response) => {
                    this.intel = response.data;
                    this.$notify({
                      group: 'foo',
                      title: 'Success',
                      text: 'Intel marked seen',
                      type: 'success'
                    });
                });
            }
        },
        mounted() {
            this.loadAlliances();
            this.loadIntel();
            this.user = this.$currentUser;
        },
        watch: {
           'sort': 'loadAlliances'
        }
    }
</script>
