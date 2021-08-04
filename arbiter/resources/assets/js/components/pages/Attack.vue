<template>
    <main class="my-3">
        <div class="row justify-content-center attack">
            <div class="col-md-12">

                <bookings-component :bookings.sync="bookings" :settings='settings' @loadTargets="loadTargets"></bookings-component>

                <div class="card card-default mb-3 has-table">
                    <div class="card-header">
                        Attack {{ attack.id }}<br/>
                        <small v-if="attack.notes">{{ attack.notes }}</small>
                        <div class="float-right">
                            <span class="toggle-wrapper"><span class="toggle-label">Classic</span><toggle-button :width="40" v-model="showClassic" class="toggle-button" /></span>
                            <span class="toggle-wrapper"><span class="toggle-label">Growth</span><toggle-button :width="40" v-model="showGrowth" class="toggle-button" /></span>
                        </div>
                    </div>

                    <div class="card-body">
                        <preloader :loading.sync="loading"></preloader>
                        <table id="alliances" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle sticky-col" rowspan="2"><sort-heading field="coords" text="x:y z" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center align-middle" rowspan="2">Race</th>
                                    <th class="text-center align-middle" rowspan="2"><sort-heading field="size" text="Size" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center align-middle" rowspan="2"><sort-heading field="value" text="Value" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center align-middle" rowspan="2"><sort-heading field="score" text="Score" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center align-middle" rowspan="2" v-if="!showClassic"><sort-heading field="amps" text="Amps" :sort.sync="sort"></sort-heading></th>
                                    <th class="text-center" colspan="6" v-if="!showClassic">Anti</th>
                                    <th class="text-center align-middle" rowspan="2" v-if="!showClassic">Ships</th>
                                    <th class="text-center align-middle" rowspan="2" v-if="!showClassic">Stock / Prod</th>
                                    <th class="text-center" colspan="3" v-if="!showClassic">Factories</th>
                                    <th class="text-center align-middle" rowspan="2">Scans</th>
                                    <th class="text-center "v-for="(n, index) in attack.waves">LT {{ attack.land_tick+index }}</th>
                                    <th class="text-center align-middle" rowspan="2">Intel</th>
                                    <th class="text-center" colspan="3" v-if="showGrowth">Growth</th>
                                    <th class="text-center align-middle" rowspan="2" v-if="settings.role == 'Admin' || settings.role == 'BC'"></th>
                                </tr>
                                <tr>
                                    <th class="text-center anti" v-if="!showClassic"><span>Fi</span></th>
                                    <th class="text-center anti" v-if="!showClassic"><span>Co</span></th>
                                    <th class="text-center anti" v-if="!showClassic"><span>Fr</span></th>
                                    <th class="text-center anti" v-if="!showClassic"><span>De</span></th>
                                    <th class="text-center anti" v-if="!showClassic"><span>Cr</span></th>
                                    <th class="text-center anti" v-if="!showClassic"><span>Bs</span></th>
                                    <th class="text-center anti" v-if="!showClassic"><span>L</span></th>
                                    <th class="text-center anti" v-if="!showClassic"><span>M</span></th>
                                    <th class="text-center anti" v-if="!showClassic"><span>H</span></th>
                                    <th class="text-center anti" v-for="(n, index) in attack.waves">ETA {{ (attack.land_tick+index)-attack.current_tick }}</th>
                                    <th class="text-center anti" v-if="showGrowth">Size</th>
                                    <th class="text-center anti" v-if="showGrowth">Value</th>
                                    <th class="text-center anti" v-if="showGrowth">Score</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr v-for="(target, number) in attack.targets">
                                    <td class="text-center align-middle sticky-col"><router-link :to="{ name: 'galaxy', params: { id: target.planet.galaxy_id }}"><a>{{ target.planet.x }}:{{ target.planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: target.planet.id }}"><a>{{ target.planet.z }}</a></router-link></td>
                                    <td class="text-center align-middle"><span class="race" v-bind:class="target.planet.race">{{ target.planet.race }}</span></td>
                                    <td class="text-center align-middle">{{ target.planet.size | shorten }}</td>
                                    <td class="text-center align-middle" v-bind:class="{ basher: target.planet.basher }">{{ target.planet.value | shorten }}</td>
                                    <td class="text-center align-middle" v-bind:class="{ basher: target.planet.basher }">{{ target.planet.score | shorten }}</td>
                                    <td class="text-center align-middle" v-bind:class="{ yes: target.planet.amps >= settings.user.distorters, no: target.planet.amps < settings.user.distorters }" v-if="!showClassic">{{ target.planet.amps }} / {{ target.planet.waves }}</td>
                                    <td class="text-center align-middle" width="15" v-if="!showClassic"><span v-if="target.anti.Fighter === 'na'"><i class="fa fa-question"></i></span><span v-if="target.anti.Fighter === true"><i class="fa fa-check"></i></span><span v-if="!target.anti.Fighter"><i class="fa fa-times"></i></span></td>
                                    <td class="text-center align-middle" width="15" v-if="!showClassic"><span v-if="target.anti.Corvette === 'na'"><i class="fa fa-question"></i></span><span v-if="target.anti.Corvette === true"><i class="fa fa-check"></i></span><span v-if="!target.anti.Corvette"><i class="fa fa-times"></i></span></td>
                                    <td class="text-center align-middle" width="15" v-if="!showClassic"><span v-if="target.anti.Frigate === 'na'"><i class="fa fa-question"></i></span><span v-if="target.anti.Frigate === true"><i class="fa fa-check"></i></span><span v-if="!target.anti.Frigate"><i class="fa fa-times"></i></span></td>
                                    <td class="text-center align-middle" width="15" v-if="!showClassic"><span v-if="target.anti.Destroyer === 'na'"><i class="fa fa-question"></i></span><span v-if="target.anti.Destroyer === true"><i class="fa fa-check"></i></span><span v-if="!target.anti.Destroyer"><i class="fa fa-times"></i></span></td>
                                    <td class="text-center align-middle" width="15" v-if="!showClassic"><span v-if="target.anti.Cruiser === 'na'"><i class="fa fa-question"></i></span><span v-if="target.anti.Cruiser === true"><i class="fa fa-check"></i></span><span v-if="!target.anti.Cruiser"><i class="fa fa-times"></i></span></td>
                                    <td class="text-center align-middle" width="15" v-if="!showClassic"><span v-if="target.anti.Battleship === 'na'"><i class="fa fa-question"></i></span><span v-if="target.anti.Battleship === true"><i class="fa fa-check"></i></span><span v-if="!target.anti.Battleship"><i class="fa fa-times"></i></span></td>
                                    <td class="ships-td align-middle" v-if="!showClassic">
                                        <span v-if="target.planet.latest_a && target.planet.latest_a.tick >= (tick-24)" v-show="target.showShips">
                                            <span v-for="(ship, index) in target.planet.latest_a.au" class="ships" v-bind:class="{ col1: index % 2 == 0 }">
                                                <span class="name">{{ ship.ship.name }}</span><span class="amount">{{ ship.amount }}</span><br v-if="index % 2 == 1"/>
                                            </span>
                                        </span>
                                        <span v-if="(!target.planet.latest_a || target.planet.latest_a.tick < (tick-24)) && (target.planet.latest_u && target.planet.latest_u.tick >= (tick-24))" v-show="target.showShips">
                                            <span v-for="(ship, index) in target.planet.latest_u.u" class="ships">
                                                <span class="name">{{ ship.ship.name }}</span><span class="amount">{{ ship.amount }}</span><br v-if="index % 2 == 1"/>
                                            </span>
                                        </span>
                                        <a class="text-center" @click="target.showShips = !target.showShips" v-if="(target.planet.latest_a && target.planet.latest_a.tick >= (tick-24)) || (target.planet.latest_u && target.planet.latest_u.tick >= (tick-24))"><span v-show="!target.showShips">show</span><span v-show="target.showShips">[hide]</span></a>
                                        <p class="text-center" v-else><i class="fa fa-question"></i></p>
                                    </td>
                                    <td class="text-center align-middle" v-if="!showClassic">
                                      <span v-if="target.planet.latest_p && target.planet.latest_p.scan.tick >= (tick-24)">{{ target.planet.latest_p.stock }} / {{ target.planet.latest_p.prod_res }}</span>
                                      <span v-else><i class="fa fa-question"></i> / <i class="fa fa-question"></i></span>
                                    </td>
                                    <td class="text-center align-middle" v-if="!showClassic">
                                      <span v-if="target.planet.latest_p && target.planet.latest_p.scan.tick >= (tick-24) && target.planet.latest_p.factory_usage_light != 'N'">{{ (target.planet.latest_p.factory_usage_light == 'N') ? '' : target.planet.latest_p.factory_usage_light }}</span>
                                      <span v-else>
                                        <i class="fa fa-question" v-if="!target.planet.latest_p || (target.planet.latest_p && target.planet.latest_p.scan.tick < (tick-24)) && (target.planet.latest_d && target.planet.latest_d.scan.tick < (tick-24))"></i>
                                        <i class="fa fa-check" v-if="(target.planet.latest_d && target.planet.latest_d.scan.tick >= (tick-24)) && target.planet.latest_d.hulls >= 1"></i>
                                        <i class="fa fa-times" v-if="(target.planet.latest_d && target.planet.latest_d.scan.tick >= (tick-24)) && target.planet.latest_d.hulls < 1"></i>
                                      </span>
                                    </td>
                                    <td class="text-center align-middle" v-if="!showClassic">
                                      <span v-if="target.planet.latest_p && target.planet.latest_p.scan.tick >= (tick-24) && target.planet.latest_p.factory_usage_medium != 'N'">{{ (target.planet.latest_p.factory_usage_medium == 'N') ? '' : target.planet.latest_p.factory_usage_medium }}</span>
                                      <span v-else>
                                        <i class="fa fa-question" v-if="!target.planet.latest_p || (target.planet.latest_p && target.planet.latest_p.scan.tick < (tick-24)) && (target.planet.latest_d && target.planet.latest_d.scan.tick < (tick-24))"></i>
                                        <i class="fa fa-check" v-if="(target.planet.latest_d && target.planet.latest_d.scan.tick >= (tick-24)) && target.planet.latest_d.hulls >= 2"></i>
                                        <i class="fa fa-times" v-if="(target.planet.latest_d && target.planet.latest_d.scan.tick >= (tick-24)) && target.planet.latest_d.hulls < 2"></i>
                                      </span>
                                    </td>
                                    <td class="text-center align-middle" v-if="!showClassic">
                                      <span v-if="target.planet.latest_p && target.planet.latest_p.scan.tick >= (tick-24) && target.planet.latest_p.factory_usage_heavy != 'N'">{{ (target.planet.latest_p.factory_usage_heavy == 'N') ? '' : target.planet.latest_p.factory_usage_heavy }}</span>
                                      <span v-else>
                                        <i class="fa fa-question" v-if="!target.planet.latest_p || (target.planet.latest_p && target.planet.latest_p.scan.tick < (tick-24)) && (target.planet.latest_d && target.planet.latest_d.scan.tick < (tick-24))"></i>
                                        <i class="fa fa-check" v-if="(target.planet.latest_d && target.planet.latest_d.scan.tick >= (tick-24)) && target.planet.latest_d.hulls == 3"></i>
                                        <i class="fa fa-times" v-if="(target.planet.latest_d && target.planet.latest_d.scan.tick >= (tick-24)) && target.planet.latest_d.hulls < 3"></i>
                                      </span>
                                    </td>
                                    <td class="scans text-center align-middle">
                                        <template v-if="target.planet.latest_p">
                                          <a v-if="target.planet.latest_p.scan.tick >= (tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+target.planet.latest_p.scan.pa_id" target="_blank" v-bind:class="{ new: target.planet.latest_p.scan.tick >= (tick-1), mid: (target.planet.latest_p.scan.tick >= (tick-12) && target.planet.latest_p.scan.tick < (tick-1)), old: target.planet.latest_p.scan.tick >= (tick-24)  && target.planet.latest_p.scan.tick < (tick-12) }">P</a>
                                        </template>
                                        <template v-if="target.planet.latest_d">
                                          <a v-if="target.planet.latest_d.scan.tick >= (tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+target.planet.latest_d.scan.pa_id" target="_blank" v-bind:class="{ new: target.planet.latest_d.scan.tick >= (tick-1), mid: (target.planet.latest_d.scan.tick >= (tick-12) && target.planet.latest_d.scan.tick < (tick-1)), old: target.planet.latest_d.scan.tick >= (tick-24) && target.planet.latest_d.scan.tick < (tick-12) }">D</a>
                                        </template>
                                        <template v-if="target.planet.latest_a">
                                          <a v-if="target.planet.latest_a.tick >= (tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+target.planet.latest_a.pa_id" target="_blank" v-bind:class="{ new: target.planet.latest_a.tick >= (tick-1), mid: (target.planet.latest_a.tick >= (tick-12) && target.planet.latest_a.tick < (tick-1)), old: target.planet.latest_a.tick >= (tick-24) && target.planet.latest_a.tick < (tick-12) }">A</a>
                                        </template>
                                        <template v-if="(!target.planet.latest_a || target.planet.latest_a.tick < (tick-24)) && target.planet.latest_u">
                                          <a v-if="target.planet.latest_u.tick >= (tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+target.planet.latest_u.pa_id" target="_blank" v-bind:class="{ new: target.planet.latest_u.tick >= (tick-1), mid: (target.planet.latest_u.tick >= (tick-12) && target.planet.latest_u.tick < (tick-1)), old: target.planet.latest_u.tick >= (tick-24) && target.planet.latest_u.tick < (tick-12) }">U</a>
                                        </template>
                                        <template v-if="target.planet.latest_j">
                                          <a v-if="target.planet.latest_j.scan.tick >= (tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+target.planet.latest_j.scan.pa_id" target="_blank" v-bind:class="{ new: target.planet.latest_j.scan.tick >= (tick-1), mid: (target.planet.latest_j.scan.tick >= (tick-12) && target.planet.latest_j.scan.tick < (tick-1)), old: target.planet.latest_j.scan.tick >= (tick-24) && target.planet.latest_j.scan.tick < (tick-12) }">J</a>
                                        </template>
                                        <a class="calc" target="_blank" v-bind:href="target.calc">C</a>
                                    </td>
                                    <td class="text-center align-middle book" v-for="booking in target.bookings">
                                        <span v-if="!booking.user_id">
                                            <a v-on:click="book(number, target.id, booking.id)" class="btn-link btn-sm"><i class="fas fa-book"></i></a>
                                        </span>
                                        <span v-if="booking.user_id">{{ booking.user.name }}</span>
                                    </td>
                                    <td class="text-center align-middle">{{ target.planet.nick }} <span v-if="target.planet.nick && target.planet.alliance">/ </span><router-link v-if="target.planet.alliance" :to="{ name: 'alliance', params: { id: target.planet.alliance.id }}"><a>{{ target.planet.alliance.name }}</a></router-link></td>
                                    <td class="text-right" v-if="showGrowth" v-bind:class="{ yellow: target.planet.growth_percentage_size == 0.0, green: target.planet.growth_percentage_size > 0.0, red: target.planet.growth_percentage_size < 0.0 }">{{ target.planet.growth_percentage_size | shorten }}%</td>
                                    <td class="text-right" v-if="showGrowth" v-bind:class="{ yellow: target.planet.growth_percentage_value == 0.0, green: target.planet.growth_percentage_value > 0.0, red: target.planet.growth_percentage_value < 0.0 }">{{ target.planet.growth_percentage_value | shorten }}%</td>
                                    <td class="text-right" v-if="showGrowth" v-bind:class="{ yellow: target.planet.growth_percentage_score == 0.0, green: target.planet.growth_percentage_score > 0.0, red: target.planet.growth_percentage_score < 0.0 }">{{ target.planet.growth_percentage_score | shorten }}%</td>
                                    <td class="text-right align-middle" v-if="settings.role == 'Admin' || settings.role == 'BC'"><button v-on:click="deleteTarget(target.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
              </div>

            <div class="col-md-6">

                <div class="card card-default mb-3" v-if="settings.role == 'Admin' || settings.role == 'BC'">
                    <div class="card-header">
                      Admin
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <label>Land tick</label><br/>
                            <input type="text" v-model="newLt" />
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes</label><br>
                            <textarea v-model="newNotes"></textarea><br/>
                        </div>

                        <div class="form-group">
                            <label for="targets">Add targets (eg x:y:z x:y:* x:y:z)</label><br>
                            <textarea v-model="newTargets"></textarea><br/>
                        </div>

                        <button v-on:click="addWave()" class="btn btn-success">+ wave</button>
                        <button v-on:click="removeWave()" class="btn btn-danger">- wave</button>
                        <button v-on:click="updateAttack()" class="btn btn-primary">save</button>


                    </div>
                </div>

            </div>
            <div class="col-md-6">
              <div class="card card-default mb-3" v-if="settings.role == 'Admin' || settings.role == 'BC'">
                  <div class="card-header">
                        Targets
                        <div class="float-right">
                            <button v-if="!loading" v-on:click="copyCoords()" class="btn btn-sm btn-link"><i class="fa fa-files-o" v-tooltip:top="'copy'"></i></button>
                        </div>
                  </div>

                  <div class="card-body">
                      <span v-for="target in attack.targets">{{ target.planet.x }}:{{ target.planet.y }}:{{ target.planet.z }} </span>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card card-default mb-3 has-table" v-if="settings.role == 'Admin' || settings.role == 'BC'">
                  <div class="card-header">
                      Alliances
                  </div>

                    <div class="card-body">
                        <table class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                  <th>Name</th>
                                  <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="alliance in attack.alliances">
                                    <td>{{ alliance.name }}</td>
                                    <td>{{ alliance.count }}</td>
                                </tr>
                            </tbody>
                        </table>
                  </div>
              </div>
            </div>

            <div class="col-md-6">
                <div class="card card-default mb-3 has-table" v-if="settings.role == 'Admin' || settings.role == 'BC'">
                    <div class="card-header">
                        Bookings
                    </div>

                    <div class="card-body">
                        <table class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                  <th>Nick</th>
                                  <th>Bookings</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="booking in attack.bookings">
                                    <td>{{ booking.nick }}</td>
                                    <td>{{ booking.count }}</td>
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
                'attack': {},
                'bookings': {},
                'tick': "",
                'doAdmin': false,
                'newLt': "",
                'newNotes': "",
                'newTargets': "",
                'sort': "",
                'loading': true,
                'showGrowth': false,
                'showClassic': false,
                allianceChartOptions: {
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
                    pieSliceBorderColor: '#424242'
                }
            };
        },
        methods: {
            book: function(key, targetId, id) {
                axios.get('api/v1/attacks/'+this.$route.params.id+'/book/'+id)
                .then((response) => {
                    this.loadTargetBookings(key, targetId, id);
                    this.loadBookings();
                    this.$notify({
                        group: 'foo',
                        title: 'Success',
                        text: 'Target booked',
                        type: 'success'
                    });
                }, (error) => {
                    this.loadTargets();
                });
            },
            loadTargetBookings: function(key, targetId, bookingId) {
                //console.log(key, targetId, bookingId);
                axios.get('api/v1/attacks/'+this.attack.id+'/targets/'+targetId+'/bookings')
                .then((response) => {
                    this.attack.targets[key].bookings = response.data;
                });
            },
            loadTargets: function() {
                axios.get('api/v1/attacks/' + this.$route.params.id, {
                    params: {
                        sort: this.sort
                    }
                })
                .then((response) => {
                    this.attack = response.data;
                    this.newLt = response.data.land_tick;
                    this.newNotes = response.data.notes;
                    this.tick = response.data.current_tick;
                    this.loading = false;
                });
            },
            loadBookings: function() {
                axios.get('api/v1/attacks/bookings', {
                    params: {
                        attack_id: this.$route.params.id
                    }
                })
                .then((response) => {
                    this.bookings = response.data;
                });
            },
            deleteTarget: function(id) {
                if(confirm("Are you sure you want to remove this target?")) {
                    axios.get('api/v1/attacks/' + this.$route.params.id + '/delete/' + id)
                    .then((response) => {
                        this.loadTargets();
                        this.loadBookings();
                        this.$notify({
                          group: 'foo',
                          title: 'Success',
                          text: 'Target deleted',
                          type: 'success'
                        });
                    });
                }
            },
            updateAttack: function() {
                var attack = this.attack;
                attack.land_tick = this.newLt;
                attack.notes = this.newNotes;
                attack.newTargets = this.newTargets;
                axios.put('api/v1/attacks/' + this.$route.params.id, attack)
                .then((response) => {
                    this.loadTargets();
                    this.loadBookings();
                    this.newTargets = "";
                    this.$notify({
                      group: 'foo',
                      title: 'Success',
                      text: 'Attack updated',
                      type: 'success'
                    });
                });
            },
            addWave: function() {
                if(confirm("Are you sure you want to add a new wave?")) {
                    axios.get('api/v1/attacks/' + this.$route.params.id + '/waves/add')
                    .then((response) => {
                        this.loadTargets();
                        this.loadBookings();
                        this.$notify({
                        group: 'foo',
                        title: 'Success',
                        text: 'Wave added',
                        type: 'success'
                        });
                    });
                }
            },
            removeWave: function() {
                if(confirm("Are you sure you want to remove the last wave?")) {
                    axios.get('api/v1/attacks/' + this.$route.params.id + '/waves/remove')
                    .then((response) => {
                        this.loadTargets();
                        this.loadBookings();
                        this.$notify({
                        group: 'foo',
                        title: 'Success',
                        text: 'Wave removed',
                        type: 'success'
                        });
                    });
                }
            },
            copyCoords: function() {
                var str = "";
                this.attack.targets.forEach(function(item, index) {
                    str = str + item.planet.coords + " ";
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
            this.loadBookings();
        },
        watch: {
           'sort': 'loadTargets'
        }
    }
</script>
