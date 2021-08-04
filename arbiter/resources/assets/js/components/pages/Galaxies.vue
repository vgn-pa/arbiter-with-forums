<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-4 my-2">
                <widget-galaxy-roid-gain></widget-galaxy-roid-gain>
            </div>
            <div class="col-md-12 col-lg-4 my-2">
                <widget-galaxy-value-gain></widget-galaxy-value-gain>
            </div>
            <div class="col-md-12 col-lg-4 my-2">
                <widget-galaxy-score-gain></widget-galaxy-score-gain>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-4 my-2">
                <widget-galaxy-roid-loss></widget-galaxy-roid-loss>
            </div>
            <div class="col-md-12 col-lg-4 my-2">
                <widget-galaxy-value-loss></widget-galaxy-value-loss>
            </div>
            <div class="col-md-12 col-lg-4 my-2">
                <widget-galaxy-score-loss></widget-galaxy-score-loss>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default has-table">
                    <div class="card-header">
                        Galaxies
                        <div class="float-right">
                            <span class="toggle-wrapper"><span class="toggle-label">Races</span><toggle-button v-model="showRaces" class="toggle-button" /></span>
                            <span class="toggle-wrapper"><span class="toggle-label">Res</span><toggle-button v-model="showResources" class="toggle-button" /></span>
                            <span class="toggle-wrapper"><span class="toggle-label">Allys</span><toggle-button v-model="showAllys" class="toggle-button" /></span>
                        </div>
                    </div>

                    <div class="card-body">
                        <preloader :loading.sync="loading"></preloader>
                        <table id="galaxies" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
                            <thead>
                                <tr>
                                    <th class="align-bottom" rowspan="2">#</th>
                                    <th class="text-center" colspan="4">Rank</th>
                                    <th class="align-bottom text-right" rowspan="2"><sort-heading field="coords" text="x:y" :sort.sync="sort"></sort-heading></th>
                                    <th class="align-bottom" rowspan="2">Name</th>
                                    <th class="align-bottom text-right" rowspan="2"><sort-heading field="planet_count" text="Planets" :sort.sync="sort"></sort-heading></th>
                                    <th class="align-bottom text-right" rowspan="2"><sort-heading field="ratio" text="Ratio" :sort.sync="sort"></sort-heading></th>
                                    <th class="align-bottom text-right" rowspan="2"><sort-heading field="size" text="Size" :sort.sync="sort"></sort-heading></th>
                                    <th class="align-bottom text-right" rowspan="2"><sort-heading field="value" text="Value" :sort.sync="sort"></sort-heading></th>
                                    <th class="align-bottom text-right" rowspan="2"><sort-heading field="score" text="Score" :sort.sync="sort"></sort-heading></th>
                                    <th class="align-bottom text-right" rowspan="2"><sort-heading field="xp" text="Xp" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" colspan="3">Growth</th>
                                    <th class="align-bottom text-right" rowspan="2" v-if="showResources">Stock</th>
                                    <th class="text-center" colspan="5" v-if="showRaces">Races</th>
                                    <th class="align-bottom" rowspan="2" v-if="showAllys">Alliances</th>
                                </tr>
                                <tr>
                                    <th class="text-center"><sort-heading field="size" text="Size" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center"><sort-heading field="value" text="Value" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center"><sort-heading field="score" text="Score" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center"><sort-heading field="xp" text="Xp" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center"><sort-heading field="growth_size" text="Size" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center"><sort-heading field="growth_value" text="Value" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center"><sort-heading field="growth_score" text="Score" :sort.sync="sort"></sort-heading></th>
                                    <th class="race Ter text-center" v-if="showRaces">T</th>
                                    <th class="race Cat text-center" v-if="showRaces">C</th>
                                    <th class="race Xan text-center" v-if="showRaces">X</th>
                                    <th class="race Zik text-center" v-if="showRaces">Z</th>
                                    <th class="race Etd text-center" v-if="showRaces">E</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(galaxy, index) in pager.data" v-bind:class="{ is_alliance: galaxy.has_members }">
                                    <td>{{ pager.from+index }}</td>
                                    <td class="text-right">{{ galaxy.rank_size }} <span v-if="galaxy.growth_rank_size > 0" v-tooltip:top="'up ' + galaxy.growth_rank_size + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="galaxy.growth_rank_size < 0" v-tooltip:top="'down ' + Math.abs(galaxy.growth_rank_size) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="galaxy.growth_rank_size == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ galaxy.rank_value }} <span v-if="galaxy.growth_rank_value > 0" v-tooltip:top="'up ' + galaxy.growth_rank_value + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="galaxy.growth_rank_value < 0" v-tooltip:top="'down ' + Math.abs(galaxy.growth_rank_value) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="galaxy.growth_rank_value == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ galaxy.rank_score }} <span v-if="galaxy.growth_rank_score > 0" v-tooltip:top="'up ' + galaxy.growth_rank_score + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="galaxy.growth_rank_score < 0" v-tooltip:top="'down ' + Math.abs(galaxy.growth_rank_score) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="galaxy.growth_rank_score == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right">{{ galaxy.rank_xp }} <span v-if="galaxy.growth_rank_xp > 0" v-tooltip:top="'up ' + galaxy.growth_rank_xp + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="galaxy.growth_rank_xp < 0" v-tooltip:top="'down ' + Math.abs(galaxy.growth_rank_xp) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="galaxy.growth_rank_xp == 0"><i class="fa fa-caret-right"></i></span></td>
                                    <td class="text-right"><router-link :to="{ name: 'galaxy', params: { id: galaxy.id }}"><a>{{ galaxy.x }}:{{ galaxy.y }}</a></router-link></td>
                                    <td style="width: 100%"><span v-if="galaxy.is_fort" v-tooltip:top="'Fort gal'"><i class="fas fa-chess-rook" style="margin-right: 5px"></i></span>{{ galaxy.name }}</td>
                                    <td class="text-right">{{ galaxy.planet_count }} <span v-if="galaxy.growth_planet_count != 0">(<span v-bind:class="{ positive: Math.sign(galaxy.growth_planet_count) == 1, negative: Math.sign(galaxy.growth_planet_count) == -1 }">{{ galaxy.growth_planet_count }}</span>)</span></td>
                                    <td class="text-right">{{ galaxy.ratio.toLocaleString() }}</td>
                                    <td class="text-right"><span v-tooltip:top="galaxy.size.toLocaleString()">{{ galaxy.size | shorten }}</span></td>
                                    <td class="text-right"><span v-tooltip:top="galaxy.value.toLocaleString()">{{ galaxy.value | shorten }}</span></td>
                                    <td class="text-right"><span v-tooltip:top="galaxy.score.toLocaleString()">{{ galaxy.score | shorten }}</span></td>
                                    <td class="text-right"><span v-tooltip:top="galaxy.xp.toLocaleString()">{{ galaxy.xp | shorten }}</span></td>
                                    <td class="text-right" v-bind:class="{ yellow: galaxy.growth_percentage_size == 0.0, green: galaxy.growth_percentage_size > 0.0, red: galaxy.growth_percentage_size < 0.0 }"><span v-tooltip:top="galaxy.growth_size.toLocaleString()">{{ galaxy.growth_percentage_size | shorten }}%</span></td>
                                    <td class="text-right" v-bind:class="{ yellow: galaxy.growth_percentage_value == 0.0, green: galaxy.growth_percentage_value > 0.0, red: galaxy.growth_percentage_value < 0.0 }"><span v-tooltip:top="galaxy.growth_value.toLocaleString()">{{ galaxy.growth_percentage_value | shorten }}%</span></td>
                                    <td class="text-right" v-bind:class="{ yellow: galaxy.growth_percentage_score == 0.0, green: galaxy.growth_percentage_score > 0.0, red: galaxy.growth_percentage_score < 0.0 }"><span v-tooltip:top="galaxy.growth_score.toLocaleString()">{{ galaxy.growth_percentage_score | shorten }}%</span></td>
                                    <td class="text-right" v-if="showResources"><span v-tooltip:top="galaxy.hidden_resources.toLocaleString()">{{ galaxy.hidden_resources | shorten }}</span></td>
                                    <td class="race Ter text-center" v-if="showRaces">{{ galaxy.races.Ter }}</td>
                                    <td class="race Cat text-center" v-if="showRaces">{{ galaxy.races.Cat }}</td>
                                    <td class="race Xan text-center" v-if="showRaces">{{ galaxy.races.Xan }}</td>
                                    <td class="race Zik text-center" v-if="showRaces">{{ galaxy.races.Zik }}</td>
                                    <td class="race Etd text-center" v-if="showRaces">{{ galaxy.races.Etd }}</td>
                                    <td class="alliances" v-if="showAllys">
                                        <span class="button" v-for="alliance in galaxy.alliances" v-tooltip:top="alliance.name" v-bind:class="{ green: alliance.relation == 'friendly', red: alliance.relation == 'hostile', blue: alliance.is_alliance }">{{ alliance.count }} {{ alliance.nickname }}</span>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot><tr><td colspan="100"><pager :pager.sync="pager" v-if="!loading"></pager></td></tr></tfoot>
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
                'sort': '-score',
                'loading': true,
                'pager': {},
                'showResources': false,
                'showAllys': false,
                'showRaces': false
            };
        },
        methods: {
            loadGalaxies: function() {
                this.loading = true;
                axios.get('api/v1/galaxies', {
                    params: {
                        sort: this.sort
                    }
                })
                .then((response) => {
                    this.loading = false;
                    this.pager = response.data;
                });
            }
        },
        mounted() {
            this.loadGalaxies();
        },
        watch: {
           'sort': 'loadGalaxies'
        }
    }
</script>
