<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-header">
                                Covert Ops
                            </div>
                            <div class="card-body">
                        
                                <p>Min alert assumes Democracy government and no population in security. Max alert assumes Nationalism government and 50% population in security. Scans may not be up to date so always double check before attempting to covert op a target.</p>
                                <p>Please press hit button if you attempt to covert op the target, this will remove the target from the list for one tick.</p>
                                <p style="color: red">NOTE: There's new filters for the target list below, this includes stealth/agents that'll tailor the list to your parameters.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-header">Calculator</div>

                            <div class="card-body">
                                <form @submit.prevent="handleSubmit">
                                    <div class="form-group">
                                        <label for="size">Size</label><br>
                                        <input type="number" v-model="calc.size"><br/>
                                    </div>
                                    <div class="form-group">
                                        <label for="guards">Guards</label><br>
                                        <input type="number" v-model="calc.guards"><br/>
                                    </div>
                                    <div class="form-group">
                                        <label for="centres">Centres</label><br>
                                        <input type="number" v-model="calc.centres"><br/>
                                    </div>
                                    <div class="form-group">
                                        <label for="population">Population (%)</label><br>
                                        <input type="number" max="50" v-model="calc.population"><br/>
                                    </div>
                                    <div class="form-group">
                                        <label for="government">Government</label><br>
                                        <select v-model="calc.government">
                                            <option v-bind:value="'Anarchy'">Anarchy</option>
                                            <option v-bind:value="'Corporatism'">Corporatism</option>
                                            <option v-bind:value="'Democracy'">Democracy</option>
                                            <option v-bind:value="'Nationalism'">Nationalism</option>
                                            <option v-bind:value="'Socialism'">Socialism</option>
                                            <option v-bind:value="'Totalitarian'">Totalitarian</option>
                                        </select><br/>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Calc</button>
                                    <div v-if="calc.result">
                                        <p>Alert is: {{ calc.result }}</p>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default mb-3" v-bind:class="{ 'has-table': planets.length }">
                    <div class="card-header">
                        Targets
                        <div class="float-right">
                            <button v-if="!loading" v-on:click="copyTargetsCoords()" class="btn btn-sm btn-link"><i class="fa fa-files-o" v-tooltip:top="'copy'"></i></button>
                            <button class="btn btn-sm btn-link" v-on:click="filterTargets = !filterTargets"><i class="fa" v-bind:class="{ 'fa-plus': !filterTargets, 'fa-minus': filterTargets }"></i> filter</button>
                        </div>
                    </div>

                    <div class="card-body filters" v-if="filterTargets">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            Has dev scan</br>
                                            <input type="checkbox" v-model="hasd"> <span class="checkbox-label">Only include planets with dev scans</span></input>
                                        </label><br/>
                                    </div>
                                    <div class="form-group">
                                        <label>Stealth</label><br/>
                                        <input type="number" v-model="stealth" />
                                    </div>
                                </div>    
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Population ({{ population }}%)</label><br/>
                                        <input type="range" min="0" max="50" class="slider" id="myRange" v-model="population"/><br/>
                                    </div>
                                    <div class="form-group">
                                        <label>Agents</label><br/>
                                        <input type="number" v-model="agents" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p style="margin-bottom:0">Bear in mind that there is an element of randomness in the covert op formula, the green for min/max assumes that the randomness is the worst possible result so you *should* succeed. You may also be able to succeed on the lower alert red targets too, depending on your luck.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <preloader :loading.sync="loading"></preloader>
                        <p style="margin-bottom: 0" v-if="!loading && !planets.length">There's no targets based on the current filters.</p>
                        <table id="members" class="table table-striped table-bordered" style="width:100%" v-if="!loading && planets.length">
                            <thead>
                                <tr>
                                    <th class="align-bottom" colspan="2" rowspan="2">Co-ords</th>
                                    <th class="align-bottom" rowspan="2">Ruler/Planet</th>
                                    <th class="align-bottom" rowspan="2">Race</th>
                                    <th class="align-bottom" rowspan="2">Size</th>
                                    <th class="align-bottom text-center" colspan="3">Growth 24h</th>
                                    <th class="text-center" colspan="2">Security</th>
                                    <th class="text-center" colspan="2">Alert</th>
                                    <th class="align-bottom" rowspan="2">Scans</th>
                                    <th class="align-bottom" rowspan="2">Intel</th>
                                    <th class="align-bottom" rowspan="2">Last covopped</th>
                                    <th class="align-bottom" rowspan="2">Hit</th>
                                </tr>
                                <tr>
                                    <th>Size</th>
                                    <th>%</th>
                                    <th>Last 5</th>
                                    <th>Guards</th>
                                    <th>Centres</th>
                                    <th>Min</th>
                                    <th>Max</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="planet in planets" v-bind:class="{ is_alliance: planet.is_alliance }">
                                    <td>
                                        <router-link :to="{ name: 'galaxy', params: { id: planet.galaxy_id }}"><a>{{ planet.x }}:{{ planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: planet.id }}"><a>{{ planet.z }}</a></router-link>
                                    </td>
                                    <td><i class="fas fa-shield-alt" v-if="planet.is_protected" style="margin-right: 5px;" v-tooltip:top="'In protection for ' + (24-planet.age) + ' more tick(s)'"></i></td>
                                    <td style="width: 100%">{{ planet.ruler_name }} of {{ planet.planet_name }}</td>                                    
                                    <td><span class="race" v-bind:class="planet.race">{{ planet.race }}</span></td>
                                    <td>{{ planet.size }}</td>
                                    <td v-bind:class="{ yellow: planet.growth_percentage_size == 0.0, green: planet.growth_percentage_size > 0.0, red: planet.growth_percentage_size < 0.0 }">{{ planet.growth_size.toLocaleString() }}</td>
                                    <td v-bind:class="{ yellow: planet.growth_percentage_size == 0.0, green: planet.growth_percentage_size > 0.0, red: planet.growth_percentage_size < 0.0 }">{{ planet.growth_percentage_size | shorten }}%</td>
                                    <td>
                                        <div class="last-5-meter">
                                            <span v-for="history in planet.history" class="last-5-item" v-bind:class="{ positive: history.change_size > 0, none: history.change_size == 0, negative: history.change_size < 0 }">
                                            </span>
                                        </div>
                                        
                                    </td>
                                    <td>{{ planet.latest_p.guards }}</td>
                                    <td><span v-if="planet.latest_d">{{ planet.latest_d.security_centre }}</span><span v-if="!planet.latest_d">n/a</span></td>
                                    <td v-bind:class="{ green: planet.min_success, red: !planet.min_success }">{{ planet.min_alert }}</td>
                                    <td v-bind:class="{ green: planet.max_success, red: !planet.max_success }">{{ planet.max_alert }}</td>
                                    <td class="scans">
                                        <template v-if="planet.latest_p">
                                        <a v-if="planet.latest_p.scan.tick >= (settings.tick-24)" v-bind:href="planet.latest_p.scan.link" target="_blank" v-bind:class="{ new: planet.latest_p.scan.tick >= (settings.tick-1), mid: (planet.latest_p.scan.tick >= (settings.tick-12) && planet.latest_p.scan.tick < (settings.tick-1)), old: planet.latest_p.scan.tick >= (settings.tick-24)  && planet.latest_p.scan.tick < (settings.tick-12) }">P</a>
                                        </template>
                                        <template v-if="planet.latest_d">
                                        <a v-if="planet.latest_d.scan.tick >= (settings.tick-24)" v-bind:href="planet.latest_d.scan.link" target="_blank" v-bind:class="{ new: planet.latest_d.scan.tick >= (settings.tick-1), mid: (planet.latest_d.scan.tick >= (settings.tick-12) && planet.latest_d.scan.tick < (settings.tick-1)), old: planet.latest_d.scan.tick >= (settings.tick-24) && planet.latest_d.scan.tick < (settings.tick-12) }">D</a>
                                        </template>
                                    </td>
                                    <td class="text-right">
                                        <span v-if="planet.last_covopped">{{ settings.tick - planet.last_covopped }} tick(s) ago</span>
                                        <span v-if="!planet.last_covopped">n/a</span>
                                    </td>
                                    <td>{{ planet.nick }} <span v-if="planet.nick && planet.alliance"> </span><router-link v-if="planet.alliance" :to="{ name: 'alliance', params: { id: planet.alliance.id }}"><a>{{ planet.alliance.name }}</a></router-link></td>
                                    <td><button v-on:click="hit(planet.id)" class="btn btn-sm btn-danger">Hit!</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12" v-if="latest.length">
                <div class="card card-default mb-3 has-table">
                    <div class="card-header">
                        Recently Hit
                        <div class="float-right">
                            <button v-if="!loading" v-on:click="copyLatestCoords()" class="btn btn-sm btn-link"><i class="fa fa-files-o" v-tooltip:top="'copy'"></i></button>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="members" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="align-bottom">Co-ords</th>
                                    <th class="align-bottom">Ruler/Planet</th>
                                    <th class="align-bottom text-center">Race</th>
                                    <th class="align-bottom text-center">Size</th>
                                    <th class="align-bottom text-center">Growth</th>
                                    <th class="align-bottom">Scans</th>
                                    <th class="align-bottom">Alliance</th>
                                    <th class="align-bottom text-right">Last Covopped</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="planet in latest">
                                    <td><router-link :to="{ name: 'galaxy', params: { id: planet.galaxy_id }}"><a>{{ planet.x }}:{{ planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: planet.id }}"><a>{{ planet.z }}</a></router-link></td>
                                    <td style="width: 100%">{{ planet.ruler_name }} of {{ planet.planet_name }}</td>
                                    <td><span class="race" v-bind:class="planet.race">{{ planet.race }}</span></td>
                                    <td class="text-right">{{ planet.size }}</td>
                                    <td class="text-right" v-tooltip:top="planet.growth_size" v-bind:class="{ yellow: planet.growth_percentage_size == 0.0, green: planet.growth_percentage_size > 0.0, red: planet.growth_percentage_size < 0.0 }">{{ planet.growth_percentage_size | shorten }}%</td>
                                    <td class="scans">
                                        <template v-if="planet.latest_p">
                                        <a v-if="planet.latest_p.scan.tick >= (settings.tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+planet.latest_p.scan.pa_id" target="_blank" v-bind:class="{ new: planet.latest_p.scan.tick >= (settings.tick-1), mid: (planet.latest_p.scan.tick >= (settings.tick-12) && planet.latest_p.scan.tick < (settings.tick-1)), old: planet.latest_p.scan.tick >= (settings.tick-24)  && planet.latest_p.scan.tick < (settings.tick-12) }">P</a>
                                        </template>
                                        <template v-if="planet.latest_d">
                                        <a v-if="planet.latest_d.scan.tick >= (settings.tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+planet.latest_d.scan.pa_id" target="_blank" v-bind:class="{ new: planet.latest_d.scan.tick >= (settings.tick-1), mid: (planet.latest_d.scan.tick >= (settings.tick-12) && planet.latest_d.scan.tick < (settings.tick-1)), old: planet.latest_d.scan.tick >= (settings.tick-24) && planet.latest_d.scan.tick < (settings.tick-12) }">D</a>
                                        </template>
                                    </td>
                                    <td>{{ planet.nick }} <span v-if="planet.nick && planet.alliance"> </span><router-link v-if="planet.alliance" :to="{ name: 'alliance', params: { id: planet.alliance.id }}"><a>{{ planet.alliance.name }}</a></router-link></td>
                                    <td class="text-right">{{ settings.tick - planet.last_covopped }} tick(s) ago</td>
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
                planets: {},
                calc: {
                    size: 0,
                    guards: 0,
                    centres: 0,
                    population: 0,
                    government: 'Anarchy',
                    result: ''
                },
                latest: {},
                hasd: true,
                loading: true,
                filterTargets: false,
                population: 50,
                stealth: 80,
                agents: 9
            };
        },
        methods: {
            hit: function(id) {
                axios.get('api/v1/covops/'+id+'/hit')
                .then((response) => {
                    this.loadTargets();
                });
            },
            reloadTargets: _.debounce(function() {
                this.loadTargets();
            }, 750),
            loadTargets: function() {
                this.loading = true;
                axios.get('api/v1/covops', {
                    params: {
                        has_d: this.hasd,
                        population: this.population,
                        stealth: this.stealth,
                        agents: this.agents
                    }
                })
                .then((response) => {
                    this.planets = response.data;
                    this.loading = false;
                });
            },
            loadLatest: function() {
              axios.get('api/v1/covops/latest')
              .then((response) => {
                  this.latest = response.data;
              });
            },
            handleSubmit() {
                axios.post('api/v1/covops', this.calc).then((response) => {
                    this.calc.result = response.data;
                });
            },
            copyLatestCoords: function() {
                var str = "";
                this.latest.forEach(function(item, index) {
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
            },
            copyTargetsCoords: function() {
                var str = "";
                this.planets.forEach(function(item, index) {
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
        mounted() {
            this.loadTargets();
            this.loadLatest();
            this.$parent.loadSettings();
        },
        watch: {
           'hasd': 'loadTargets',
           'population': 'reloadTargets',
           'agents': 'reloadTargets',
           'stealth': 'reloadTargets'
        },
    }
</script>
