<template>
    <section>
        <div class="filters">
            <div class="row">
                <div class="col-md-4">
                  <fieldset>
                      <legend>Mission type</legend>
                      <input type="checkbox" v-model="showAttack" /> Attack<br/>
                      <input type="checkbox" v-model="showDefend" /> Defend<br/>
                      <input type="checkbox" v-model="showUnknown" /> Unknown
                  </fieldset>
                </div>
                <div class="col-md-4">
                    <fieldset>
                        <legend>Time</legend>
                        <input type="checkbox" v-model="show24h" /> 24h<br/>
                        <input type="checkbox" v-model="show72h" /> 72h<br/>
                        <input type="checkbox" v-model="showAll" /> All<br/>
                    </fieldset>
                </div>
                <div class="col-md-4">
                    <fieldset>
                        <legend>In gal</legend>
                        <input type="checkbox" v-model="ingal" /> show<br/>
                    </fieldset>
                    <fieldset>
                        <legend>In ally</legend>
                        <input type="checkbox" v-model="inally" /> show<br/>
                    </fieldset>
                </div>
            </div>
        </div>
        <table id="planets" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                  <th>Tick</th>
                  <th>From</th>
                  <th>Target</th>
                  <th>Mission</th>
                  <th>Fleet</th>
                  <th>Eta</th>
                  <th>Ships</th>
                  <th>Ally to</th>
                  <th>Lands</th>
                  <th v-if="settings.role == 'Admin'">Options</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="fleet in outgoing"
                    v-bind:class="{ missionattack: fleet.mission_type == 'Attack', missiondefend: fleet.mission_type == 'Defend' }"
                    v-if="(fleet.planet_to && fleet.planet_from)
                          && ((fleet.mission_type == 'Attack' && showAttack)
                          || (fleet.mission_type == 'Defend' && showDefend)
                          || (fleet.mission_type == null && showUnknown))
                          && ((fleet.launch_tick > (tick-24) && show24h)
                          || (fleet.launch_tick > (tick-72) && show72h)
                          || showAll)
                          && ((!ingal && fleet.planet_from.galaxy_id != fleet.planet_to.galaxy_id)
                          || ingal)
                          && ((!inally && fleet.planet_from.alliance_id != fleet.planet_to.alliance_id)
                          || inally)">
                    <td>{{ fleet.tick }}</td>
                    <td><router-link :to="{ name: 'galaxy', params: { id: fleet.planet_from.galaxy_id }}"><a>{{ fleet.planet_from.x }}:{{ fleet.planet_from.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: fleet.planet_from.id }}"><a>{{ fleet.planet_from.z }}</a></router-link></td>
                    <td><router-link :to="{ name: 'galaxy', params: { id: fleet.planet_to.galaxy_id }}"><a>{{ fleet.planet_to.x }}:{{ fleet.planet_to.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: fleet.planet_to.id }}"><a>{{ fleet.planet_to.z }}</a></router-link></td>
                    <td>{{ fleet.mission_type }}</td>
                    <td>{{ fleet.fleet_name }}</td>
                    <td>{{ fleet.eta }}</td>
                    <td>{{ fleet.ship_count }}</td>
                    <td><router-link v-if="fleet.planet_to.alliance" :to="{ name: 'alliance', params: { id: fleet.planet_to.alliance.id }}"><a>{{ fleet.planet_to.alliance.name }}</a></router-link></td>
                    <td>{{ fleet.land_tick }}</td>
                    <td v-if="settings.role == 'Admin'"><span v-if="(!fleet.planet_to.alliance || !fleet.planet_from.alliance) && (fleet.planet_to.alliance || fleet.planet_from.alliance) &&  fleet.mission_type == 'Defend' && fleet.planet_from.galaxy_id != fleet.planet_to.galaxy_id && fleet.planet_from.alliance_id != fleet.planet_to.alliance_id"><button v-on:click="addIntel(fleet.id)">Add</button></span></td>
                </tr>
            </tbody>
        </table>
    </section>
</template>

<script>
    export default {
      props: ['outgoing', 'tick'],
      methods: {
          addIntel: function(id) {
              event.target.disabled = true;

              axios.post('api/v1/intel/', { id: id })
              .then((response) => {
                  //location.reload();
                  this.$notify({
                    group: 'foo',
                    title: 'Success',
                    text: 'Intel saved',
                    type: 'success'
                  });
              });
          }
      },
      data: function () {
          return {
              showAttack: true,
              showDefend: true,
              showUnknown: true,
              show24h: true,
              show72h: true,
              showAll: true,
              ingal: true,
              inally: true,
              settings: {
                  user: {}
              }
          };
      },
      mounted() {
          this.settings = this.$parent.settings;
      }

    }
</script>
