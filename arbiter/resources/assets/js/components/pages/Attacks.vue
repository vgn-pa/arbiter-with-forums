<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card card-default mb-3" v-bind:class="{ 'has-table': attacks.length }">
                    <div class="card-header">Open Attacks</div>

                    <div class="card-body">
                        <preloader :loading.sync="attackLoading"></preloader>
                        <table id="alliances" class="table table-striped table-bordered" style="width:100%" v-if="!attackLoading && attacks.length">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Land tick</th>
                                    <th>Notes</th>
                                    <th>Claimed</th>
                                    <th v-if="settings.role == 'Admin' || settings.role == 'BC'"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="attack in attacks">
                                    <td><router-link :to="{ name: 'attack', params: { id: attack.attack_id }}"><a><button class="btn btn-primary btn-sm">#{{ attack.id }}</button></a></router-link></td>
                                    <td>{{ attack.land_tick }}</td>
                                    <td>{{ attack.notes }}</td>
                                    <td>
                                        <div class="progress" data-toggle="tooltip" data-placement="top" v-tooltip:top="attack.claimed_count + ' of ' + attack.bookings_count + ' booked (' + attack.claimed_percentage + '%)'">
                                            <div class="progress-bar bg-info" role="progressbar" v-bind:style="{ width: attack.claimed_percentage + '%' }" v-bind:aria-valuenow="attack.claimed_percentage" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td v-if="settings.role == 'Admin' || settings.role == 'BC'" class="options"><button v-on:click="close(attack.id)" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                        <p v-if="!attackLoading && !attacks.length" class="no-data">No open attacks</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">

                <bookings-component :bookings.sync="bookings" :settings='settings'></bookings-component>

            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default mb-3 has-table" v-if="(settings.role == 'Admin' || settings.role == 'BC') && scheduled.length">
                    <div class="card-header">Scheduled Attacks</div>

                    <div class="card-body">
                        <table id="alliances" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Schedule hour</th>
                                  <th>Land tick</th>
                                  <th>Notes</th>
                                  <th v-if="settings.role == 'Admin' || settings.role == 'BC'">Options</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="attack in scheduled">
                                  <td><router-link :to="{ name: 'attack', params: { id: attack.attack_id }}"><a><button class="btn btn-primary btn-sm">#{{ attack.id }}</button></a></router-link></td>
                                  <td>{{ attack.scheduled_time }}:00</td>
                                  <td>{{ attack.land_tick }}</td>
                                  <td>{{ attack.notes }}</td>
                                  <td v-if="settings.role == 'Admin' || settings.role == 'BC'"><button v-on:click="close(attack.id)" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button></td>

                              </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div v-bind:class="{ 'col-md-6': settings.attack, 'col-md-12': !settings.attack }">
                <div class="card card-default mb-3" v-if="settings.role == 'Admin' || settings.role == 'BC'">
                    <div class="card-header">Create Attack</div>

                    <div class="card-body">
                        <form @submit.prevent="handleSubmit">
                            <div class="form-group">
                                <label for="targets">Targets</label><br>
                                <textarea v-model="targets"></textarea><br/>
                                <p class="help">List targets to add to the attack eg: x:y:z x:y:*, you can add more targets later</p>
                            </div>
                            <div class="form-group">
                                <label for="time">Scheduled time</label><br/>
                                <select v-model="time">
                                    <option v-bind:value="0">00:00</option>
                                    <option v-bind:value="1">01:00</option>
                                    <option v-bind:value="2">02:00</option>
                                    <option v-bind:value="3">03:00</option>
                                    <option v-bind:value="4">04:00</option>
                                    <option v-bind:value="5">05:00</option>
                                    <option v-bind:value="6">06:00</option>
                                    <option v-bind:value="7">07:00</option>
                                    <option v-bind:value="8">08:00</option>
                                    <option v-bind:value="9">09:00</option>
                                    <option v-bind:value="10">10:00</option>
                                    <option v-bind:value="11">11:00</option>
                                    <option v-bind:value="12">12:00</option>
                                    <option v-bind:value="13">13:00</option>
                                    <option v-bind:value="14">14:00</option>
                                    <option v-bind:value="15">15:00</option>
                                    <option v-bind:value="16">16:00</option>
                                    <option v-bind:value="17">17:00</option>
                                    <option v-bind:value="18">18:00</option>
                                    <option v-bind:value="19">19:00</option>
                                    <option v-bind:value="20">20:00</option>
                                    <option v-bind:value="21">21:00</option>
                                    <option v-bind:value="22">22:00</option>
                                    <option v-bind:value="23">23:00</option>
                                </select>
                                <p class="help">You can schedule attacks to open on the hour, if you do not set a scheduled time the attack will open immediately</p>
                            </div>
                            <div class="form-group">
                                <label for="waves">Waves</label><br>
                                <select v-model="waves">
                                    <option v-bind:value="1">1</option>
                                    <option v-bind:value="2">2</option>
                                    <option v-bind:value="3">3</option>
                                    <option v-bind:value="4">4</option>
                                    <option v-bind:value="5">5</option>
                                    <option v-bind:value="6">6</option>
                                    <option v-bind:value="7">7</option>
                                    <option v-bind:value="8">8</option>
                                    <option v-bind:value="9">9</option>
                                    <option v-bind:value="10">10</option>
                                  </select>
                                <p class="help">The number of waves for all targets, waves can be added and removed later</p>
                            </div>
                            <div class="form-group">
                                <label for="land_tick">Land tick</label><br>
                                <input type="text" v-model="land_tick"><br/>
                            </div>
                            <div class="form-group">
                                <label for="notes">Notes</label><br>
                                <textarea v-model="notes"></textarea><br/>
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6" v-if="settings.attack">
                <div class="card card-default mb-3" v-if="settings.role == 'Admin' || settings.role == 'BC'">
                    <div class="card-header">Instructions</div>

                    <div class="card-body">
                        <div v-html="settings.attack"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card card-default mb-3" v-bind:class="{ 'has-table': closed.length }">
                    <div class="card-header">Closed Attacks</div>

                    <div class="card-body">
                        <preloader :loading.sync="closedLoading"></preloader>
                        <table id="galaxies" class="table table-striped table-bordered" style="width:100%" v-if="!closedLoading && closed.length">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Land tick</th>
                                    <th>Notes</th>
                                    <th>Claimed</th>
                                    <th>Alliances</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="attack in closed">
                                    <td><router-link :to="{ name: 'attack', params: { id: attack.attack_id }}"><a><button class="btn btn-primary btn-sm">#{{ attack.id }}</button></a></router-link></td>
                                    <td>{{ attack.land_tick }}</td>
                                    <td>{{ attack.notes }}</td>
                                    <td>
                                        <div class="progress" data-toggle="tooltip" data-placement="top" v-tooltip:top="attack.claimed_count + ' of ' + attack.bookings_count + ' booked (' + attack.claimed_percentage + '%)'">
                                            <div class="progress-bar bg-info" role="progressbar" v-bind:style="{ width: attack.claimed_percentage + '%' }" v-bind:aria-valuenow="attack.claimed_percentage" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td class="alliances">
                                        <span class="button" v-for="alliance in attack.alliances" v-tooltip:top="alliance.name">{{ alliance.count }} {{ alliance.nickname }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p v-if="!closedLoading && !closed.length" class="no-data">No closed attacks</p>
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
                'attacks': {},
                closed: {},
                'targets': '',
                'land_tick': '',
                'waves': '',
                'notes': '',
                'time': '',
                'bookings': {},
                'scheduled': {},
                attackLoading: true,
                closedLoading: true,
                waves: 1
            };
        },
        methods: {
            close: function(id) {
                if(confirm("Are you sure you want to close this attack?")) {
                    axios.get('api/v1/attacks/'+id+'/close')
                    .then((response) => {
                        this.attacks = response.data;
                        this.loadClosed();
                    });
                }
            },
            handleSubmit() {
                axios.post('api/v1/attacks', {
                    targets: this.targets,
                    land_tick: this.land_tick,
                    notes: this.notes,
                    waves: this.waves,
                    time: this.time
                }).then((response) => {
                    this.attacks = response.data;
                    this.targets = "";
                    this.land_tick = "";
                    this.waves = "";
                    this.notes = "";
                    this.time = "";
                    axios.get('api/v1/attacks/scheduled')
                    .then((response) => {
                        this.scheduled = response.data;
                    });
                });
            },
            loadAttacks: function() {
                axios.get('api/v1/attacks')
                .then((response) => {
                    this.attacks = response.data;
                    this.attackLoading = false;
                });
            },
            loadScheduled: function() {
                if(this.settings.role == 'Admin' || this.settings.role == 'BC') {
                    axios.get('api/v1/attacks/scheduled')
                    .then((response) => {
                        this.scheduled = response.data;
                    });
                }
            },
            loadBookings: function() {
                axios.get('api/v1/attacks/bookings')
                .then((response) => {
                    this.bookings = response.data;
                });
            },
            loadClosed: function() {
                this.closedLoading = true;
                axios.get('api/v1/attacks/closed')
                .then((response) => {
                    this.closed = response.data;
                    this.closedLoading = false;
                });
            }
        },
        mounted() {
            this.loadAttacks();
            this.loadScheduled();
            this.loadBookings();
            this.loadClosed();
        },
    }
</script>
