<template>
    <div class="card card-default mb-3 has-table" v-if="bookings.length">
        <div class="card-header">My Bookings</div>

        <div class="card-body">
            <table id="bookings" class="table table-striped table-bordered attack" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center align-middle sticky-col">x:y z</th>
                        <th class="text-center align-middle">Attack</th>
                        <th class="text-center align-middle">Land tick</th>
                        <th class="text-center align-middle">Race</th>
                        <th class="text-center align-middle">Size</th>
                        <th class="text-center align-middle">Value / Score</th>
                        <th class="text-center align-middle">Amps / Tech</th>
                        <th class="text-center align-middle">Scans</th>
                        <th class="text-center align-middle">Intel</th>
                        <th class="text-center align-middle">Notes</th>
                        <th class="text-center align-middle">Calc</th>
                        <th class="text-center align-middle">Shared w/</th>
                        <th class="text-center align-middle"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="booking in bookings">
                        <td class="text-center align-middle sticky-col"><router-link :to="{ name: 'galaxy', params: { id: booking.target.planet.galaxy_id }}"><a>{{ booking.target.planet.x }}:{{ booking.target.planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: booking.target.planet.id }}"><a>{{ booking.target.planet.z }}</a></router-link></td>
                        <td class="text-center align-middle">
                            <router-link :to="{ name: 'attack', params: { id: booking.target.attack.attack_id }}"><a><button class="btn btn-primary btn-sm">#{{ booking.target.attack.id }}</button></a></router-link>
                        </td>
                        <td class="text-center align-middle">{{ booking.land_tick }} (eta {{ (booking.land_tick)-settings.tick }})</td>
                        <td class="text-center align-middle"><span class="race" v-bind:class="booking.target.planet.race">{{ booking.target.planet.race }}</span></td>
                        <td class="text-center align-middle">{{ booking.target.planet.size }}</td>
                        <td class="text-center align-middle" v-bind:class="{ basher: booking.target.planet.basher }">{{ booking.target.planet.value | shorten }} / {{ booking.target.planet.score | shorten }}</td>
                        <td class="text-center align-middle" v-bind:class="{ yes: booking.target.planet.amps >= settings.user.distorters, no: booking.target.planet.amps < settings.user.distorters }">{{ booking.target.planet.amps }} / {{ booking.target.planet.waves }}</td>
                        <td class="scans text-center align-middle">
                            <template v-if="booking.target.planet.latest_p">
                                <a v-if="booking.target.planet.latest_p.scan.tick >= ($parent.tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+booking.target.planet.latest_p.scan.pa_id" target="_blank" v-bind:class="{ new: booking.target.planet.latest_p.scan.tick >= ($parent.tick-1), mid: (booking.target.planet.latest_p.scan.tick >= ($parent.tick-12) && booking.target.planet.latest_p.scan.tick < ($parent.tick-1)), old: booking.target.planet.latest_p.scan.tick >= ($parent.tick-24)  && booking.target.planet.latest_p.scan.tick < ($parent.tick-12) }">P</a>
                            </template>
                            <template v-if="booking.target.planet.latest_d">
                                <a v-if="booking.target.planet.latest_d.scan.tick >= ($parent.tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+booking.target.planet.latest_d.scan.pa_id" target="_blank" v-bind:class="{ new: booking.target.planet.latest_d.scan.tick >= ($parent.tick-1), mid: (booking.target.planet.latest_d.scan.tick >= ($parent.tick-12) && booking.target.planet.latest_d.scan.tick < ($parent.tick-1)), old: booking.target.planet.latest_d.scan.tick >= ($parent.tick-24) && booking.target.planet.latest_d.scan.tick < ($parent.tick-12) }">D</a>
                            </template>
                            <template v-if="booking.target.planet.latest_a">
                                <a v-if="booking.target.planet.latest_a.tick >= ($parent.tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+booking.target.planet.latest_a.pa_id" target="_blank" v-bind:class="{ new: booking.target.planet.latest_a.tick >= ($parent.tick-1), mid: (booking.target.planet.latest_a.tick >= ($parent.tick-12) && booking.target.planet.latest_a.tick < ($parent.tick-1)), old: booking.target.planet.latest_a.tick >= ($parent.tick-24) && booking.target.planet.latest_a.tick < ($parent.tick-12) }">A</a>
                            </template>
                            <template v-if="(!booking.target.planet.latest_a || booking.target.planet.latest_a.tick < ($parent.tick-24)) && booking.target.planet.latest_u">
                                <a v-if="booking.target.planet.latest_u.tick >= ($parent.tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+booking.target.planet.latest_u.pa_id" target="_blank" v-bind:class="{ new: booking.target.planet.latest_u.tick >= ($parent.tick-1), mid: (booking.target.planet.latest_u.tick >= ($parent.tick-12) && booking.target.planet.latest_u.tick < ($parent.tick-1)), old: booking.target.planet.latest_u.tick >= ($parent.tick-24) && booking.target.planet.latest_u.tick < ($parent.tick-12) }">U</a>
                            </template>
                            <template v-if="booking.target.planet.latest_j">
                                <a v-if="booking.target.planet.latest_j.scan.tick >= ($parent.tick-24)" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+booking.target.planet.latest_j.scan.pa_id" target="_blank" v-bind:class="{ new: booking.target.planet.latest_j.scan.tick >= ($parent.tick-1), mid: (booking.target.planet.latest_j.scan.tick >= ($parent.tick-12) && booking.target.planet.latest_j.scan.tick < ($parent.tick-1)), old: booking.target.planet.latest_j.scan.tick >= ($parent.tick-24) && booking.target.planet.latest_j.scan.tick < ($parent.tick-12) }">J</a>
                            </template>
                            <a class="calc" target="_blank" v-bind:href="booking.target.calc">C</a>
                        </td>
                        <td class="text-center align-middle">{{ booking.target.planet.nick }} <span v-if="booking.target.planet.nick && booking.target.planet.alliance">/ </span><router-link v-if="booking.target.planet.alliance" :to="{ name: 'alliance', params: { id: booking.target.planet.alliance.id }}"><a>{{ booking.target.planet.alliance.name }}</a></router-link></td>
                        <td class="text-left align-middle" style="width: 100%;">
                            {{ booking.notes }}
                        </td>
                        <td class="text-left align-middle">
                            <a v-if="booking.battle_calc" target="_BLANK" v-bind:href="booking.battle_calc"><button class="btn btn-sm btn-primary"><i class="fa fa-calculator"></i></button></a>
                        </td><td class="text-left align-middle">
                            {{ booking.user.name }}
                            <div v-if="booking.users.length">
                                <span v-for="user in booking.users">{{ user.name }}<br/></span>
                            </div>
                        </td>                        
                        <td class="text-right align-middle">
                            <router-link :to="{ name: 'attackBooking', params: { id: booking.target.attack.attack_id, booking_id: booking.id }}"><a><button class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></button></a></router-link>
                            <a v-on:click="drop(booking)" class="btn btn-danger btn-sm" title="drop target"><i class="fa fa-arrow-circle-down"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
      props: ['bookings', 'settings'],
      data: function () {
          return {
              
          };
      },
      methods: {
          drop: function(booking) {
              axios.get('api/v1/attacks/bookings/drop/' + booking.id, {
                  params: {
                      attack_id: booking.attack_id
                  }
              })
              .then((response) => {
                  this.$parent.loadBookings();
                  this.$emit('loadTargets');
                  this.$notify({
                      group: 'foo',
                      title: 'Success',
                      text: 'Target dropped',
                      type: 'success'
                  });
              });
          }
      }
    }

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
