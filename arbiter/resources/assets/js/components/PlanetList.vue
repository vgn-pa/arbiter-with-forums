<template>
    <div class="card card-default mb-3 has-table">
        <div class="card-header">
            Planets
            <div class="float-right">
                <button v-if="!loading" v-on:click="copyCoords()" class="btn btn-sm btn-link"><i class="fa fa-files-o" v-tooltip:top="'copy'"></i></button>
                <span class="toggle-wrapper"><span class="toggle-label">Scans</span><toggle-button :width="40" v-model="scans" class="toggle-button" /></span>
                <span class="toggle-wrapper"><span class="toggle-label">Tech</span><toggle-button :width="40" v-model="tech" class="toggle-button" /></span>
            </div>
        </div>

        <div class="card-body">
            <preloader :loading.sync="loading"></preloader>
            <table id="members" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
                <thead>
                    <tr>
                        <th class="align-bottom" rowspan="2">#</th>
                        <th class="text-center" colspan="4">Rank</th>
                        <th class="text-right" rowspan="2">Gal <i class="fa fa-info-circle" v-tooltip:top="'Galaxy rank (number of planets in same ally)'" style="margin-left: 5px;"></i></th>
                        <th class="align-bottom text-right" rowspan="2"><sort-heading field="coords" text="x:y: z" :sort.sync="sort"></sort-heading></th>
                        <th class="align-bottom text-center" rowspan="2">Ruler</th>
                        <th class="text-center" colspan="3" v-if="this.scans">Scan age</th>
                        <th class="align-bottom text-center" rowspan="2">Race</th>
                        <th class="align-bottom text-center" rowspan="2"><sort-heading field="size" text="Size" :sort.sync="sort"></sort-heading></th>
                        <th class="align-bottom text-center" rowspan="2"><sort-heading field="value" text="Value" :sort.sync="sort"></sort-heading></th>
                        <th class="align-bottom text-center" rowspan="2"><sort-heading field="score" text="Score" :sort.sync="sort"></sort-heading></th>
                        <th class="align-bottom text-center" rowspan="2"><sort-heading field="xp" text="Xp" :sort.sync="sort"></sort-heading></th>
                        <th class="text-center" colspan="3">Growth</th>
                        <th class="align-bottom text-center" colspan="3" v-if="this.tech">Waves</th>
                        <th class="align-bottom text-center" rowspan="2">Intel</th>
                    </tr>
                    <tr>
                        <th class="text-center"><sort-heading field="size" text="Size" :sort.sync="sort"></sort-heading></th>
                        <th class="text-center"><sort-heading field="value" text="Value" :sort.sync="sort"></sort-heading></th>
                        <th class="text-center"><sort-heading field="score" text="Score" :sort.sync="sort"></sort-heading></th>
                        <th class="text-center"><sort-heading field="xp" text="Xp" :sort.sync="sort"></sort-heading></th>
                        <th class="text-center" v-if="this.scans">P</th>
                        <th class="text-center" v-if="this.scans">D</th>
                        <th class="text-center" v-if="this.scans">A</th>
                        <th class="text-center"><sort-heading field="growth_size" text="Size" :sort.sync="sort"></sort-heading></sortarrow-component></span></th>
                        <th class="text-center"><sort-heading field="growth_value" text="Value" :sort.sync="sort"></sort-heading></sortarrow-component></span></sortarrow-component></span></th>
                        <th class="text-center"><sort-heading field="growth_score" text="Score" :sort.sync="sort"></sort-heading></sortarrow-component></span></th>
                        <th class="text-center" v-if="this.tech"><span><sort-heading field="amps" text="A" :sort.sync="sort"></sort-heading></span></th>
                        <th class="text-center" v-if="this.tech"><span><sort-heading field="dists" text="D" :sort.sync="sort"></sort-heading></span></th>
                        <th class="text-center" v-if="this.tech"><span>T</span></th></tr>
                </thead>
                <tbody>
                    <tr v-for="(planet, index) in pager.data" v-bind:class="{ is_alliance: planet.is_alliance }">
                        <td>{{ pager.from+index }}</td>
                        <td class="text-right">{{ planet.rank_size }} <span v-if="planet.growth_rank_size > 0" v-tooltip:top="'up ' + planet.growth_rank_size + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_size < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_size) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_size == 0"><i class="fa fa-caret-right"></i></span></td>
                        <td class="text-right">{{ planet.rank_value }} <span v-if="planet.growth_rank_value > 0" v-tooltip:top="'up ' + planet.growth_rank_value + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_value < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_value) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_value == 0"><i class="fa fa-caret-right"></i></span></td>
                        <td class="text-right">{{ planet.rank_score }} <span v-if="planet.growth_rank_score > 0" v-tooltip:top="'up ' + planet.growth_rank_score + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_score < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_score) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_score == 0"><i class="fa fa-caret-right"></i></span></td>
                        <td class="text-right">{{ planet.rank_xp }} <span v-if="planet.growth_rank_xp > 0" v-tooltip:top="'up ' + planet.growth_rank_xp + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_xp < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_xp) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_xp == 0"><i class="fa fa-caret-right"></i></span></td>
                        <td class="text-right">#{{ planet.galaxy.rank_score }} ({{ planet.galaxy.planets_count }})</td>
                        <td class="text-right"><router-link :to="{ name: 'galaxy', params: { id: planet.galaxy_id }}"><a>{{ planet.x }}:{{ planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: planet.id }}"><a>{{ planet.z }}</a></router-link></td>
                        <td style="width: 100%"><i class="fas fa-shield-alt" v-if="planet.is_protected" style="margin-right: 5px;" v-tooltip:top="'In protection for ' + (24-planet.age) + ' more tick(s)'"></i><span v-tooltip:top="planet.ruler_name + ' of ' + planet.planet_name">{{ planet.ruler_name }}</span></td>
                        <td class="text-center" v-if="scans"><span v-if="planet.latest_p && planet.latest_p.scan">{{ planet.tick - planet.latest_p.scan.tick }}</span></td>
                        <td class="text-center" v-if="scans"><span v-if="planet.latest_d && planet.latest_d.scan">{{ planet.tick - planet.latest_d.scan.tick }}</span></td>
                        <td class="text-center" v-if="scans"><span v-if="planet.latest_a">{{ planet.tick - planet.latest_a.tick }}</span></td>
                        <td><span class="race" v-bind:class="planet.race">{{ planet.race }}</span></td>
                        <td class="text-right"><span v-tooltip:top="planet.size.toLocaleString()">{{ planet.size | shorten }}</span></td>
                        <td class="text-right" v-bind:class="{ basher: planet.basher }"><span v-tooltip:top="planet.value.toLocaleString()">{{ planet.value | shorten }}</span></td>
                        <td class="text-right" v-bind:class="{ basher: planet.basher }"><span v-tooltip:top="planet.score.toLocaleString()">{{ planet.score | shorten }}</span></td>
                        <td class="text-right"><span v-tooltip:top="planet.xp.toLocaleString()">{{ planet.xp | shorten }}</span></td>
                        <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_size == 0.0, green: planet.growth_percentage_size > 0.0, red: planet.growth_percentage_size < 0.0 }"><span v-tooltip:top="planet.growth_size.toLocaleString()">{{ planet.growth_percentage_size | shorten }}%</span></td>
                        <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_value == 0.0, green: planet.growth_percentage_value > 0.0, red: planet.growth_percentage_value < 0.0 }"><span v-tooltip:top="planet.growth_value.toLocaleString()">{{ planet.growth_percentage_value | shorten }}%</span></td>
                        <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_score == 0.0, green: planet.growth_percentage_score > 0.0, red: planet.growth_percentage_score < 0.0 }"><span v-tooltip:top="planet.growth_score.toLocaleString()">{{ planet.growth_percentage_score | shorten }}%</span></td>
                        <td class="text-right" v-if="tech">{{ planet.amps }}</td>
                        <td class="text-right" v-if="tech">{{ planet.dists }}</td>
                        <td class="text-right" v-if="tech">{{ planet.waves }}</td>
                        <td class="text-right">{{ planet.nick }} <span v-if="!this.alliance"></span><router-link v-if="planet.alliance" :to="{ name: 'alliance', params: { id: planet.alliance.id }}"><a>{{ planet.alliance.name }}</a></router-link></td>
                    </tr>
                </tbody>
                <tfoot v-if="pager.total > this.perPage"><tr><td colspan="100"><pager :pager.sync="pager" v-if="!loading"></pager></td></tr></tfoot>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            perPage: Number,
            scans: {
                default: false,
                type: Boolean
            },
            alliance: Number,
            tech: {
                default: false,
                type: Boolean
            }
        },
        methods: {
            loadPlanets: function() {
                this.loading = true;
                axios.get('api/v1/planets', {
                    params: {
                        sort: this.sort,
                        page: this.page,
                        perPage: this.perPage,
                        scans: this.scans,
                        alliance_id: this.alliance
                    }
                })
                .then((response) => {
                    this.loading = false;
                    this.pager = response.data;
                });
            },
            setPage: function(page) {
                this.page = this.page + page;
            },
            copyCoords: function() {
                var str = "";
                this.pager.data.forEach(function(item, index) {
                    str = str + item.coords + " ";
                })
                
                function listener(e) {
                    e.clipboardData.setData("text/html", str);
                    e.clipboardData.setData("text/plain", str);
                    e.preventDefault();
                }
                document.addEventListener("copy", listener);
                document.execCommand("copy");
                document.removeEventListener("copy", listener);
            }
        },
        data: function () {
            return {
                sort: '-score',
                page: 1,
                loading: true,
                pager: {}
            };
        },
        mounted() {
            this.loadPlanets();
        },
        watch: {
           'sort': 'loadPlanets',
           'page': 'loadPlanets'
        },

    }
</script>
