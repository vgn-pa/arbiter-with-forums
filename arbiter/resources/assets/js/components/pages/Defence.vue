<template>
    <main class="my-3 defence">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        Defence<br/>
                        <small>These are the fleet movements currently tracked heading towards our alliance</small>
                    </div>

                    <div class="card-body">
                        <preloader :loading.sync="loading"></preloader>
                        <div v-if="!loading">
                            <div v-for="planets in defence" class="eta-group">

                                <div class="eta">ETA {{ planets.eta }}</span></div>

                                <div class="planet" v-for="planet in planets.planets">

                                    <div class="planet-info">{{ planet.x }}:{{ planet.y }}:{{ planet.z }} <span v-if="planet.user">{{ planet.user.name }}</span> <span><a class="calc btn-link btn-sm" target="_blank" v-bind:href="planet.calc"><i class="fas fa-calculator"></i></a></span></div>

                                    <div class="planet-fleets">
                                    
                                        <div class="fleet" v-for="fleet in planet.fleets">
                                            
                                            <div class="coords">{{ fleet.planet_from.x }}:{{ fleet.planet_from.y }}:{{ fleet.planet_from.z }}</div> 
                                            <div class="fleet-name">{{ fleet.fleet_name }}</div>
                                            <div class="scans">
                                                {{ tick }}
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
                                            </div>
                                            <div class="ship-count">{{ fleet.ship_count }}</div>

                                        </div>

                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <p v-if="!loading && !defence.length" class="no-data">No incomings fleets to show</p>
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
                defence: {},
                loading: true
            };
        },
        methods: {
            // loadIncoming: function() {
            //     console.log(this.settings.alliance);
            // }
        },
        mounted() {
            axios.get('api/v1/defence')
            .then((response) => {
                this.defence = response.data;
                this.loading = false;
            });
        },
        watch: {
           //'settings': 'loadIncoming'
        }
    }
</script>
