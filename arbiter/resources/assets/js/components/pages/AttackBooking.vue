<template>
    <main class="my-3">
        <div class="row justify-content-center attack">
            <div class="col-md-12">

                <bookings-component :bookings.sync="bookingArr" :settings='settings'></bookings-component>

            </div>
        </div>

        <div class="row justify-content-center" v-if="fleets.fleets.length">
        
            <div class="col-md-12">

                <div class="card card-default mb-3 has-table">
                    <div class="card-header">
                        Fleets
                        <div class="float-right">
                            <a class="calc btn-link btn-sm" target="_blank" v-bind:href="fleets.calc"><i class="fas fa-calculator"></i></a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table id="planets" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Origin</th>
                                    <th>Mission</th>
                                    <th>Fleet</th>
                                    <th>Race</th>
                                    <th>Ships</th>
                                    <th>Scans</th>
                                    <th>Eta</th>
                                    <th>Ally</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="fleet in fleets.fleets" v-bind:class="{ missionattack: fleet.mission_type == 'Attack', missiondefend: fleet.mission_type == 'Defend' }">
                                    <td><router-link :to="{ name: 'galaxy', params: { id: fleet.planet_from.galaxy_id }}"><a>{{ fleet.planet_from.x }}:{{ fleet.planet_from.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: fleet.planet_from.id }}"><a>{{ fleet.planet_from.z }}</a></router-link></td>
                                    <td>{{ fleet.mission_type }}</td>
                                    <td>{{ fleet.fleet_name }}</td>
                                    <td><span class="race" v-bind:class="fleet.planet_from.race">{{ fleet.planet_from.race }}</span></td>
                                    <td>{{ fleet.ship_count }}</td>
                                    <td class="scans">
                                        <template v-if="fleet.planet_from.latest_p">
                                            <a v-if="fleet.planet_from.latest_p.scan.tick >= (settings.tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+fleet.planet_from.latest_p.scan.pa_id" target="_blank" v-bind:class="{ new: fleet.planet_from.latest_p.scan.tick >= (settings.tick-1), mid: (fleet.planet_from.latest_p.scan.tick >= (settings.tick-12) && fleet.planet_from.latest_p.scan.tick < (settings.tick-1)), old: fleet.planet_from.latest_p.scan.tick >= (settings.tick-24)  && fleet.planet_from.latest_p.scan.tick < (settings.tick-12) }">P</a>
                                        </template>
                                        <template v-if="fleet.planet_from.latest_d">
                                            <a v-if="fleet.planet_from.latest_d.scan.tick >= (settings.tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+fleet.planet_from.latest_d.scan.pa_id" target="_blank" v-bind:class="{ new: fleet.planet_from.latest_d.scan.tick >= (settings.tick-1), mid: (fleet.planet_from.latest_d.scan.tick >= (settings.tick-12) && fleet.planet_from.latest_d.scan.tick < (settings.tick-1)), old: fleet.planet_from.latest_d.scan.tick >= (settings.tick-24) && fleet.planet_from.latest_d.scan.tick < (settings.tick-12) }">D</a>
                                        </template>
                                        <template v-if="fleet.planet_from.latest_a">
                                            <a v-if="fleet.planet_from.latest_a.tick >= (settings.tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+fleet.planet_from.latest_a.pa_id" target="_blank" v-bind:class="{ new: fleet.planet_from.latest_a.tick >= (settings.tick-1), mid: (fleet.planet_from.latest_a.tick >= (settings.tick-12) && fleet.planet_from.latest_a.tick < (settings.tick-1)), old: fleet.planet_from.latest_a.tick >= (settings.tick-24) && fleet.planet_from.latest_a.tick < (settings.tick-12) }">A</a>
                                        </template>
                                        <template v-if="(!fleet.planet_from.latest_a || fleet.planet_from.latest_a.tick < (settings.tick-24)) && fleet.planet_from.latest_u">
                                            <a v-if="fleet.planet_from.latest_u.tick >= (settings.tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+fleet.planet_from.latest_u.pa_id" target="_blank" v-bind:class="{ new: fleet.planet_from.latest_u.tick >= (settings.tick-1), mid: (fleet.planet_from.latest_u.tick >= (settings.tick-12) && fleet.planet_from.latest_u.tick < (settings.tick-1)), old: fleet.planet_from.latest_u.tick >= (settings.tick-24) && fleet.planet_from.latest_u.tick < (settings.tick-12) }">U</a>
                                        </template>
                                        <template v-if="fleet.planet_from.latest_j">
                                            <a v-if="fleet.planet_from.latest_j.scan.tick >= (settings.tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+fleet.planet_from.latest_j.scan.pa_id" target="_blank" v-bind:class="{ new: fleet.planet_from.latest_j.scan.tick >= (settings.tick-1), mid: (fleet.planet_from.latest_j.scan.tick >= (settings.tick-12) && fleet.planet_from.latest_j.scan.tick < (settings.tick-1)), old: fleet.planet_from.latest_j.scan.tick >= (settings.tick-24) && fleet.planet_from.latest_j.scan.tick < (settings.tick-12) }">J</a>
                                        </template>
                                    </td>
                                    <td>{{ fleet.land_tick-settings.tick }}</td>
                                    <td><router-link v-if="fleet.planet_from.alliance" :to="{ name: 'alliance', params: { id: fleet.planet_from.alliance.id }}"><a>{{ fleet.planet_from.alliance.name }}</a></router-link></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">

            <div class="col-md-4">
                <div class="card card-default mb-3 has-table">
                    <div class="card-header">
                        Booking users
                    </div>

                    <div class="card-body">
                        <table id="alliances" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th v-if="booking.user_id == settings.user.id">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users">
                                    <td>{{ user.name }}</td>
                                    <td v-if="booking.user_id == settings.user.id" class="text-right">
                                        <button  v-tooltip:top="'Make this user the owner'" v-if="(user.id != booking.user_id) && booking.user_id == settings.user.id" v-on:click="makeOwner(user.id)" class="btn btn-success btn-sm"><i class="fa fa-level-up"></i></button>
                                        <button v-if="user.id != booking.user_id && booking.user_id == settings.user.id" v-on:click="removeUser(user.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-default mb-3">
                    <div class="card-header">
                        Add users
                    </div>

                    <div class="card-body">
                        <div v-if="!nonusers.length && booking.user_id != settings.user.id">
                            Only the owner can add users.
                        </div>
                        <div v-if="!nonusers.length && booking.user_id == settings.user.id">All users are added.</div>
                        <form @submit.prevent="addUser" v-if="nonusers.length && booking.user_id == settings.user.id">
                            <select v-model="user">
                                <option v-for="user in nonusers" v-bind:value="user.id">
                                  {{ user.name }}
                                </option>
                            </select>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-default mb-3">
                    <div class="card-header">
                        Details
                    </div>

                    <div class="card-body">
                        <form @submit.prevent="saveDetails">
                            <div class="form-group">
                                <label>Notes</label><br/>
                                <textarea v-model="notes" style="width: 100%;"></textarea><br/>
                            </div>

                            <div class="form-group">
                                <label>Calc</label></br>
                                <input type="url" v-model="calc" style="width: 100%;" /><br/>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
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
                booking: {},
                bookingArr: [],
                nonusers: {},
                users: {},
                loading: false,
                user: '',
                notes: '',
                calc: '',
                fleets: {
                    fleets: {},
                    calc: ""
                }
            };
        },
        methods: {
            saveDetails() {
                axios.put('/api/v1/attacks/bookings/' + this.$route.params.booking_id, {
                    notes: this.notes,
                    battle_calc: this.calc
                }).then((response) => {
                    this.loadBooking();
                    this.$notify({
                      group: 'foo',
                      title: 'Success',
                      text: 'Details saved',
                      type: 'success'
                    });
                });
            },
            loadBooking: function() {
                axios.get('/api/v1/attacks/bookings/' + this.$route.params.booking_id)
                .then((response) => {
                    this.booking = response.data;
                    this.notes = this.booking.notes;
                    this.calc = this.booking.battle_calc;
                    this.bookingArr = [response.data];
                });
            },
            loadNonusers: function() {
                axios.get('/api/v1/attacks/bookings/' + this.$route.params.booking_id + '/nonusers')
                .then((response) => {
                    this.nonusers = response.data;
                });
            },
            loadUsers: function() {
                axios.get('/api/v1/attacks/bookings/' + this.$route.params.booking_id + '/users')
                .then((response) => {
                    this.users = response.data;
                });
            },
            addUser: function() {
                axios.post('/api/v1/attacks/bookings/' + this.$route.params.booking_id + '/users/add', {
                    user_id: this.user
                })
                .then((response) => {
                    this.loadUsers();
                    this.loadNonusers();
                    this.$notify({
                        group: 'foo',
                        title: 'Success',
                        text: 'User added',
                        type: 'success'
                    });
                });
            },
            removeUser: function(user) {
                axios.post('/api/v1/attacks/bookings/' + this.$route.params.booking_id + '/users/delete', {
                    user_id: user
                })
                .then((response) => {
                    this.loadUsers();
                    this.loadNonusers();
                    this.$notify({
                        group: 'foo',
                        title: 'Success',
                        text: 'User removed',
                        type: 'success'
                    });
                });
            },
            makeOwner: function(user) {
                if(confirm("Are you sure you want to set this user as the owner?")) {
                    axios.post('/api/v1/attacks/bookings/' + this.$route.params.booking_id + '/users/owner', {
                        user_id: user
                    })
                    .then((response) => {
                        this.loadUsers();
                        this.loadNonusers();
                        this.loadBooking();
                        this.$notify({
                            group: 'foo',
                            title: 'Success',
                            text: 'User promoted',
                            type: 'success'
                        });
                    });
                }
            },
            loadFleets: function() {
                axios.get('/api/v1/attacks/bookings/' + this.$route.params.booking_id + '/fleets')
                .then((response) => {
                    this.fleets = response.data;
                });
            }
        },
        mounted() {
            this.loadBooking();
            this.loadNonusers();
            this.loadUsers();
            this.loadFleets();
        },
        watch: {
           'sort': 'loadTargets'
        }
    }
</script>
