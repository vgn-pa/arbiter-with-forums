<template>
    <div class="card card-default mb-3" v-bind:class="{ 'has-table': ships.ships.length }">
        <div class="card-header">Ships <small>({{ ships.age }} ticks old)</small></div>

        <div class="card-body">
            <preloader :loading.sync="loading"></preloader>
            <table class="table table-striped table-bordered" style="width:100%;table-layout: auto;" v-if="ships.ships.length && !loading">
                <thead>
                    <tr>
                        <th>Ship</th>
                        <th>Total</th>
                        <th>%</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ship in ships.ships">
                        <td>{{ ship.name }}</td>
                        <td>{{ ship.amount }}</td>
                        <td>{{ ship.percentage }}%</td>
                    </tr>
                </tbody>
            </table>
            <p v-if="!ships.ships.length && !loading" class="no-data">No ships to show, parse a fresh AU of this planet to see ships</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
        data() {
            return {
                loading: true,
                ships: {
                  ships: {}
                }
            };
        },
        methods: {
            loadShips: function() {
                this.loading = true;
                axios.get('api/v1/planets/' + this.id + '/ships')
                .then((response) => {
                    this.ships = response.data;
                    this.loading = false;
                });
            }
        },
        watch: {
           '$route': 'loadShips'
        },
        mounted() {
            this.loadShips();
        }
    }
</script>
