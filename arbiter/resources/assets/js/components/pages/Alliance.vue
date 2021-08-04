<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div v-if="!allyLoading" class="card"><h2>{{ alliance.name }} <span v-if="alliance.is_locked"><i class="fa fa-lock" v-tooltip:top="'intel complete'"></i></span><span v-if="!alliance.is_locked">({{ members.length }}/{{ alliance.members }})</span></h2></div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-default has-table mb-3">
                    <div class="card-header">Overview</div>

                    <div class="card-body">
                        <preloader :loading.sync="allyLoading"></preloader>
                        <table id="alliances" class="table table-striped table-bordered" style="width:100%" v-if="!allyLoading">
                            <thead>
                                <tr>
                                    <th colspan="2"></th>
                                    <th class="text-right">Growth</th>
                                    <th class="text-right">Rank</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-right">Size</td>
                                    <td class="text-right">{{ alliance.size.toLocaleString() }}</td>
                                    <td class="text-right" v-bind:class="{ positive: Math.sign(alliance.growth_size) == 1, negative: Math.sign(alliance.growth_size) == -1 }">{{ alliance.growth_size.toLocaleString() }}</td>
                                    <td class="text-right">{{ alliance.rank_size }} <span v-if="alliance.growth_rank_size > 0" v-tooltip:top="'up ' + alliance.growth_rank_size + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_size < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_size) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_size == 0"><i class="fa fa-caret-right"></i></span></td>
                                </tr>
                                <tr>
                                    <td class="text-right">Value</td>
                                    <td class="text-right">{{ alliance.value.toLocaleString() }}</td>
                                    <td class="text-right" v-bind:class="{ positive: Math.sign(alliance.growth_value) == 1, negative: Math.sign(alliance.growth_value) == -1 }">{{ alliance.growth_value.toLocaleString() }}</td>
                                    <td class="text-right">{{ alliance.rank_value }} <span v-if="alliance.growth_rank_value > 0" v-tooltip:top="'up ' + alliance.growth_rank_value + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_value < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_value) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_value == 0"><i class="fa fa-caret-right"></i></span></td>
                                </tr>
                                <tr>
                                    <td class="text-right">Score</td>
                                    <td class="text-right">{{ alliance.score.toLocaleString() }}</td>
                                    <td class="text-right" v-bind:class="{ positive: Math.sign(alliance.growth_score) == 1, negative: Math.sign(alliance.growth_score) == -1 }">{{ alliance.growth_score.toLocaleString() }}</td>
                                    <td class="text-right">{{ alliance.rank_score }} <span v-if="alliance.growth_rank_score > 0" v-tooltip:top="'up ' + alliance.growth_rank_score + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_score < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_score) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_score == 0"><i class="fa fa-caret-right"></i></span></td>
                                </tr>
                                <tr>
                                    <td class="text-right">Avg. size</td>
                                    <td class="text-right">{{ alliance.avg_size.toLocaleString() }}</td>
                                    <td class="text-right" v-bind:class="{ positive: Math.sign(alliance.growth_avg_size) == 1, negative: Math.sign(alliance.growth_avg_size) == -1 }">{{ alliance.growth_avg_size.toLocaleString() }}</td>
                                    <td class="text-right">{{ alliance.rank_avg_size }} <span v-if="alliance.growth_rank_avg_size > 0" v-tooltip:top="'up ' + alliance.growth_rank_avg_size + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_avg_size < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_avg_size) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_avg_size == 0"><i class="fa fa-caret-right"></i></span></td>
                                </tr>
                                <tr>
                                    <td class="text-right">Avg. value</td>
                                    <td class="text-right">{{ alliance.avg_value.toLocaleString() }}</td>
                                    <td class="text-right" v-bind:class="{ positive: Math.sign(alliance.growth_avg_value) == 1, negative: Math.sign(alliance.growth_avg_value) == -1 }">{{ alliance.growth_avg_value.toLocaleString() }}</td>
                                    <td class="text-right">{{ alliance.rank_avg_value }} <span v-if="alliance.growth_rank_avg_value > 0" v-tooltip:top="'up ' + alliance.growth_rank_avg_value + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_avg_value < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_avg_value) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_avg_value == 0"><i class="fa fa-caret-right"></i></span></td>
                                </tr>
                                <tr>
                                    <td class="text-right">Avg. score</td>
                                    <td class="text-right">{{ alliance.avg_score.toLocaleString() }}</td>
                                    <td class="text-right" v-bind:class="{ positive: Math.sign(alliance.growth_avg_score) == 1, negative: Math.sign(alliance.growth_avg_score) == -1 }">{{ alliance.growth_avg_score.toLocaleString() }}</td>
                                    <td class="text-right">{{ alliance.rank_avg_score }} <span v-if="alliance.growth_rank_avg_score > 0" v-tooltip:top="'up ' + alliance.growth_rank_avg_score + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="alliance.growth_rank_avg_score < 0" v-tooltip:top="'down ' + Math.abs(alliance.growth_rank_avg_score) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="alliance.growth_rank_avg_score == 0"><i class="fa fa-caret-right"></i></span></td>
                                </tr>
                                <tr>
                                    <td class="text-right">Stock</td>
                                    <td class="text-right">{{ alliance.hidden_resources.toLocaleString() }}</td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr>
                                    <td class="text-right">Prod resources</td>
                                    <td class="text-right">{{ alliance.prod_resources.toLocaleString() }}</td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr v-if="!alliance.is_locked">
                                    <td class="text-right">Size diff</td>
                                    <td class="text-right">{{ alliance.size_diff }}</td>
                                    <td colspan="2"></td>
                                </tr>
                                <tr v-if="!alliance.is_locked">
                                    <td class="text-right">Xp diff</td>
                                    <td class="text-right">{{ alliance.xp_diff }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-default mb-3" v-if="settings.role == 'Admin'">
                    <div class="card-header">Settings</div>
                    <div class="card-body">
                        <preloader :loading.sync="allyLoading"></preloader>
                        <div class="row justify-content-center" v-if="!allyLoading">
                            <div class="col-md-6">
                                <label>Nickname</label><br/>
                                <input style="width: 120px" v-model="alliance.nickname" v-on:keyup="submitAlliance" />
                            </div>
                            <div class="col-md-6">
                                <div v-if="alliance.id != settings.alliance">
                                    <label>Relation</label><br/>
                                    <select v-model="alliance.relation" v-on:change="submitAlliance" >
                                        <option value="neutral">Neutral</option>
                                        <option value="friendly">Friendly</option>
                                        <option value="hostile">Hostile</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-default mb-3" v-if="members">
                    <div class="card-header">
                        Co-ords
                        <div class="float-right">
                            <button v-if="members.length" v-on:click="copyCoords()" class="btn btn-sm btn-link"><i class="fa fa-files-o" v-tooltip:top="'copy'"></i></button>
                        </div>  
                    </div>

                    <div class="card-body">
                        <preloader :loading.sync="allyLoading"></preloader>
                        <div id="allianceCoords" v-if="!allyLoading"><template v-for="member in members">{{ member.x }}:{{ member.y }}:{{ member.z }} </template></div>
                    </div>
              </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-default mb-3 alliance-ships" v-if="alliance.ships">
                    <div class="card-header">
                        Ships<br/>
                        <small>This is the total of ships on the most up to date AU scans stored for all members of this alliance</small>

                    </div>

                    <div class="card-body">
                        <div class="ships-list">
                            <table>
                                <template v-for="(row, index) in alliance.ships.ships">
                                    <tr>
                                        <template v-for="ship in row">
                                            <td class="name text-left race" v-bind:class="ship.race">{{ ship.name }}</td>
                                            <td class="amount text-right race" v-bind:class="ship.race">{{ ship.amount }}</td>
                                        </template>
                                    </tr>
                                </template>
                            </table>
                        </div>
                        <br/>
                        <GChart
                          type="PieChart"
                          :data="alliance.ships.classes"
                          :options="shipChartOptions"
                        />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-default mb-3 has-table" v-if="alliance.ships">
                    <div class="card-header">
                        Race breakdown</br>
                        <small>Shows the races of members recorded in intel</small>
                    </div>

                    <div class="card-body">
                      <GChart
                        type="PieChart"
                        :data="alliance.races"
                        :options="raceChartOptions"
                      />
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card card-default mb-3 has-table">
                    <div class="card-header">
                        Scans
                    </div>

                    <div class="card-body">
                        <preloader :loading.sync="devLoading"></preloader>
                        <table class="table table-striped table-bordered" style="width:100%" v-if="!devLoading">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Amps</th>
                                    <th>Dist</th>
                                    <th>Inc Scan</th>
                                </tr>
                                </thead>
                            <tbody>
                                <tr>
                                    <td class="label-green">Avg</td>
                                    <td>{{ development.amps.avg }}</td>
                                    <td>{{ development.dists.avg }}</td>
                                    <td>n/a</td>
                                </tr>
                                <tr>
                                    <td class="label-green">Tot</td>
                                    <td>{{ development.amps.total }}</td>
                                    <td>{{ development.dists.total }}</td>
                                    <td>{{ development.has_inc }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card card-default mb-3 has-table">
                    <div class="card-header">
                        Finance
                    </div>

                    <div class="card-body">
                        <preloader :loading.sync="devLoading"></preloader>
                        <table class="table table-striped table-bordered" style="width:100%" v-if="!devLoading">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>M Ref</th>
                                    <th>C Ref</th>
                                    <th>E Ref</th>
                                    <th>FC</th>
                                    <th>Magma</th>
                                </tr>
                                </thead>
                            <tbody>
                                <tr>
                                    <td class="label-green">Avg</td>
                                    <td>{{ development.mr.avg }}</td>
                                    <td>{{ development.cr.avg }}</td>
                                    <td>{{ development.er.avg }}</td>
                                    <td>{{ development.fc.avg }}</td>
                                    <td>n/a</td>
                                </tr>
                                <tr>
                                    <td class="label-green">Total</td>
                                    <td>{{ development.mr.total }}</td>
                                    <td>{{ development.cr.total }}</td>
                                    <td>{{ development.er.total }}</td>
                                    <td>{{ development.fc.total }}</td>
                                    <td>{{ development.has_magma }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-default mb-3 has-table">
                    <div class="card-header">
                        Security
                    </div>

                    <div class="card-body">
                        <preloader :loading.sync="devLoading"></preloader>
                        <table class="table table-striped table-bordered" style="width:100%" v-if="!devLoading">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>SC</th>
                                    <th>SD</th>
                                </tr>
                                </thead>
                            <tbody>
                                <tr>
                                    <td class="label-green">Avg</td>
                                    <td>{{ development.sc.avg }}</td>
                                    <td>{{ development.sd.avg }}</td>
                                </tr>
                                <tr>
                                    <td class="label-green">Tot</td>
                                    <td>{{ development.sc.total }}</td>
                                    <td>{{ development.sd.total }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <alliance-history :id.sync="this.id"></alliance-history>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <planet-list :perPage="60" :scans="true" :alliance="this.id" :tech="true"></planet-list>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <outgoing-summary :type="'alliance'" :id.sync="alliance.id" v-if="alliance.id || alliance.id == 0"></outgoing-summary>
            </div>
            <div class="col-md-6">
                <incoming-summary :type="'alliance'" :id.sync="alliance.id" v-if="alliance.id || alliance.id == 0"></incoming-summary>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default has-table">
                    <div class="card-header">Galaxies</div>

                    <div class="card-body">
                        <preloader :loading.sync="galLoading"></preloader>
                        <table id="galaxies" class="table table-striped table-bordered" style="width:100%" v-if="!galLoading">
                            <thead>
                                <tr>
                                    <th class="align-bottom" rowspan="2">#</th>
                                    <th class="text-center" colspan="4">Rank</th>
                                    <th class="align-bottom text-right" rowspan="2">X:Y</th>
                                    <th class="align-bottom text-center" colspan="3">Members</th>
                                    <th class="align-bottom" rowspan="2">Name</th>
                                    <th class="align-bottom text-right" rowspan="2">Planets</th>
                                    <th class="align-bottom text-right" rowspan="2">Ratio</th>
                                    <th class="align-bottom text-right" rowspan="2">Size</th>
                                    <th class="align-bottom text-right" rowspan="2">Value</th>
                                    <th class="align-bottom text-right" rowspan="2">Score</th>
                                    <th class="align-bottom text-right" rowspan="2">Xp</th>
                                    <th class="align-bottom" rowspan="2">Alliances</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Score</th>
                                    <th class="text-center">Value</th>
                                    <th class="text-center">Xp</th>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Avg</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(galaxy, index) in galaxies" v-bind:class="{ is_alliance: galaxy.has_members }">
                                    <td>{{ index+1 }}</td>
                                    <td class="text-right">{{ galaxy.rank_score }} <span v-if="galaxy.growth_rank_score > 0" v-tooltip:top="'up ' + galaxy.growth_rank_score + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="galaxy.growth_rank_score < 0" v-tooltip:top="'down ' + Math.abs(galaxy.growth_rank_score) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="galaxy.growth_rank_score == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ galaxy.rank_value }} <span v-if="galaxy.growth_rank_value > 0" v-tooltip:top="'up ' + galaxy.growth_rank_value + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="galaxy.growth_rank_value < 0" v-tooltip:top="'down ' + Math.abs(galaxy.growth_rank_value) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="galaxy.growth_rank_value == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ galaxy.rank_size }} <span v-if="galaxy.growth_rank_size > 0" v-tooltip:top="'up ' + galaxy.growth_rank_size + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="galaxy.growth_rank_size < 0" v-tooltip:top="'down ' + Math.abs(galaxy.growth_rank_size) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="galaxy.growth_rank_size == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ galaxy.rank_xp }} <span v-if="galaxy.growth_rank_xp > 0" v-tooltip:top="'up ' + galaxy.growth_rank_xp + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="galaxy.growth_rank_xp < 0" v-tooltip:top="'down ' + Math.abs(galaxy.growth_rank_xp) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="galaxy.growth_rank_xp == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right"><router-link :to="{ name: 'galaxy', params: { id: galaxy.id }}"><a>{{ galaxy.x }}:{{ galaxy.y }}</a></router-link></td>
                                    <td class="text-right">{{ galaxy.member_count }}</td>
                                    <td class="text-right">{{ galaxy.member_size | shorten }}</td>
                                    <td class="text-right">{{ parseInt(galaxy.member_size / galaxy.member_count) }}</td>
                                    <td style="width: 100%">{{ galaxy.name }}</td>
                                    <td class="text-right">{{ galaxy.planet_count }}</td>
                                    <td class="text-right">{{ (Math.round(galaxy.ratio * 100) / 100).toFixed(2) }}</td>
                                    <td class="text-right">{{ galaxy.size | shorten }}</td>
                                    <td class="text-right">{{ galaxy.value | shorten }}</td>
                                    <td class="text-right">{{ galaxy.score | shorten }}</td>
                                    <td class="text-right">{{ galaxy.xp | shorten }}</td>
                                    <td class="alliances">
                                        <span class="button" v-for="alliance in galaxy.alliances" v-tooltip:top="alliance.name" v-bind:class="{ green: alliance.relation == 'friendly', red: alliance.relation == 'hostile'}">{{ alliance.count }} {{ alliance.nickname }}</span>
                                    </td>
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
        props: ['settings'],
        data() {
            return {
                id: Number(this.$route.params.id),
                'history': {},
                'members': {},
                'alliance': {
                    id: this.id,
                    current_alliance: {},
                    outgoing: {},
                    incoming: {},
                    scans: {},
                    cons: {}
                },
                development: {
                    amps: 0,
                    dists: 0,
                    has_inc: 0,
                    mr: 0,
                    cr: 0,
                    er: 0,
                    fc: 0,
                    has_magma: 0,
                    sd: 0,
                    sc: 0,
                },
                'planets': {},
                'sort': '-coords',
                'type': 'alliance',
                galaxies: {},
                galLoading: true,
                devLoading: true,
                allyLoading: true,
                raceChartOptions: {
                    title: '',
                    backgroundColor: {
                      stroke: '#424242',
                      fill: '#424242',
                      strokeWidth: '0'
                    },
                    height: '300',
                    pieSliceText: 'value',
                    legend: {
                        textStyle: {
                            color: '#fff'
                        },
                        labelValueText: 'both',
                        alignment: 'center'
                    },
                    slices: [
                      { color: '#aa7744' },
                      { color: '#6699cc' },
                      { color: '#00cc00' },
                      { color: '#cccc00' },
                      { color: '#cccccc' },
                    ],
                    pieSliceBorderColor: '#424242'
                },
                shipChartOptions: {
                    title: '',
                    backgroundColor: {
                      stroke: '#424242',
                      fill: '#424242',
                      strokeWidth: '0'
                    },
                    height: '300',
                    legend: {
                        textStyle: {
                            color: '#fff'
                        },
                        alignment: 'center'
                    },
                    pieSliceBorderColor: '#424242'
                }
            };
        },
        methods: {
            loadAlliance: function() {
                axios.get('api/v1/alliances/' + this.id)
                .then((response) => {
                    this.members = response.data.planets;
                    this.alliance = response.data;
                    this.allyLoading = false;
                });
            },
            copyCoords: function() {
                var str = document.getElementById('allianceCoords').innerHTML;
                function listener(e) {
                    e.clipboardData.setData("text/html", str);
                    e.clipboardData.setData("text/plain", str);
                    e.preventDefault();
                }
                document.addEventListener("copy", listener);
                document.execCommand("copy");
                document.removeEventListener("copy", listener);
            },
            loadGalaxies: function() {
                this.galLoading = true;
                axios.get('api/v1/galaxies', {
                    params: {
                        sort: '-member_count',
                        alliance_id: this.id
                    }
                })
                .then((response) => {
                    this.galLoading = false;
                    this.galaxies = response.data.data;
                });
            },
            loadDevelopment: function() {
                this.devLoading = true;
                axios.get('api/v1/alliances/' + this.id + '/development')
                .then((response) => {
                    this.devLoading = false;
                    this.development = response.data;
                });
            },
            submitAlliance: _.debounce(function () {
                axios.put('api/v1/alliances/' + this.id, {
                    relation: this.alliance.relation,
                    nickname: this.alliance.nickname
                }).then((response) => {
                    this.$notify({
                        group: 'foo',
                        title: 'Success',
                        text: 'Alliance updated',
                        type: 'success'
                    });
                });
            }, 1000)
        },
        watch: {
           '$route': 'loadAlliance'
        },
        mounted() {
            this.loadAlliance();
            this.loadGalaxies();
            this.loadDevelopment();
        },
    }
</script>
