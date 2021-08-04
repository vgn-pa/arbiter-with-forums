<template>
    <div class="card card-default mb-3" v-bind:class="{ 'has-table': pager.total }">
        <div class="card-header">
            Outgoing Fleets
            <router-link :to="{ name: type + 'Outgoing', params: { id: id }}" class="float-right" v-if="pager.total > pager.per_page && !loading"><button class="btn btn-sm btn-link">show all</button></router-link>
        </div>

        <div class="card-body">
            <preloader :loading.sync="loading"></preloader>
            <table id="members" class="table table-striped table-bordered" style="width:100%" v-if="!loading && pager.total">
                <thead>
                    <tr>
                      <th>Tick</th>
                      <th v-if="type == 'alliance' || type == 'galaxy'">From</th>
                      <th>Target</th>
                      <th>Fleet</th>
                      <th>Eta</th>
                      <th>Ships</th>
                      <th>Ally To</th>
                      <th>LT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="fleet in data" v-bind:class="{ missionattack: fleet.mission_type == 'Attack', missiondefend: fleet.mission_type == 'Defend' }" v-if="fleet.planet_to && fleet.planet_from">
                        <td>{{ fleet.tick }}</td>
                        <td v-if="type == 'alliance' || type == 'galaxy'"><router-link :to="{ name: 'galaxy', params: { id: fleet.planet_from.galaxy_id }}"><a>{{ fleet.planet_from.x }}:{{ fleet.planet_from.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: fleet.planet_from.id }}"><a>{{ fleet.planet_from.z }}</a></router-link></td>
                        <td><router-link :to="{ name: 'galaxy', params: { id: fleet.planet_to.galaxy_id }}"><a>{{ fleet.planet_to.x }}:{{ fleet.planet_to.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: fleet.planet_to.id }}"><a>{{ fleet.planet_to.z }}</a></router-link></td>
                        <td><span v-tooltip:top='fleet.fleet_name'>{{ ((fleet.mission_type == 'Attack') ? 'A ' : (fleet.mission_type == 'Defend') ? 'D ' : 'U ') + fleet.fleet_name | ellipsis('10') }}</span></td>
                        <td>{{ fleet.eta }}</td>
                        <td>{{ fleet.ship_count }}</td>
                        <td><router-link v-if="fleet.planet_to.alliance" :to="{ name: 'alliance', params: { id: fleet.planet_to.alliance.id }}"><a>{{ fleet.planet_to.alliance.name }}</a></router-link></td>
                        <td>{{ fleet.land_tick }}</td>
                    </tr>
                    <!-- <tr v-if="outgoing.length < total">
                        <td colspan="8"><router-link :to="{ name: type + 'Outgoing', params: { id: id }}" class="view-all">View all</router-link></td>
                    </tr> -->
                </tbody>
                <tfoot v-if="pager.total > pager.per_page"><tr><td colspan="100"><pager :pager.sync="pager" v-if="!loading"></pager></td></tr></tfoot>
            </table>
            <p v-if="!pager.total && !loading" class="no-data">No outgoing fleets tracked</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['type', 'movement', 'id'],
        data() {
            return {
                loading: true,
                pager: {},
                data: {}
            };
        },
        methods: {
            loadData: function() {
                this.loading = true;
                axios.get('api/v1/fleets', {
                    params: {
                        page: this.page,
                        type: this.type,
                        movement: 'outgoing',
                        id: this.id,
                        limit: 10
                    }
                }).then((response) => {
                    this.data = response.data.data;
                    this.pager = response.data;
                    this.loading = false;
                });
            }
        },
        watch: {
           '$route': 'loadData'
        },
        mounted() {
            this.loadData();
        }
    }
</script>
