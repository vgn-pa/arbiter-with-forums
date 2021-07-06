<template>
    <div class="card card-default has-table mb-3">
        <div class="card-header">
            History
            <router-link :to="{ name: 'galaxyHistory', params: { id: id }}" class="float-right" v-if="pager.total > pager.per_page && !loading"><button class="btn btn-sm btn-link">show all</button></router-link>
        </div>

        <div class="card-body">
            <preloader :loading.sync="loading"></preloader>
            <table id="galaxies" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
                <thead>
                    <tr>
                        <th>Tick</th>
                        <th class="text-right">Rank</th>
                        <th class="text-right">Planets</th>
                        <th class="text-center" colspan="2">Size</th>
                        <th class="text-center" colspan="2">Value</th>
                        <th class="text-center" colspan="2">Score</th>
                        <th class="text-center" colspan="2">Xp</th>
                        <th class="text-right">Date / Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="gal in data">
                        <td class="text-right">{{ gal.tick.tick }}</td>
                        <td class="text-right">{{ gal.rank_score }}</td>
                        <td class="text-right">{{ gal.planet_count }} <span v-if="gal.change_planet_count != 0">(<span v-bind:class="{ positive: Math.sign(gal.change_planet_count) == 1, negative: Math.sign(gal.change_planet_count) == -1 }">{{ gal.change_planet_count }}</span>)</span></td>
                        <td class="text-right" style="width: 20%">{{ gal.size }}</td>
                        <td class="text-right" v-bind:class="{ positive: Math.sign(gal.change_size) == 1, negative: Math.sign(gal.change_size) == -1 }">
                            <span v-if="gal.change_size != 0">{{ gal.change_size.toLocaleString() }}</span>
                        </td>
                        <td class="text-right" style="width: 20%">{{ gal.value }}</td>
                        <td class="text-right" v-bind:class="{ positive: Math.sign(gal.change_value) == 1, negative: Math.sign(gal.change_value) == -1 }">
                            <span v-if="gal.change_value != 0">{{ gal.change_value.toLocaleString() }}</span>
                        </td>
                        <td class="text-right" style="width: 20%">{{ gal.score }}</td>
                        <td class="text-right" v-bind:class="{ positive: Math.sign(gal.change_score) == 1, negative: Math.sign(gal.change_score) == -1 }">
                            <span v-if="gal.change_score != 0">{{ gal.change_score.toLocaleString() }}</span>
                        </td>
                        <td class="text-right" style="width: 20%">{{ gal.xp }}</td>
                        <td class="text-right" v-bind:class="{ positive: Math.sign(gal.change_xp) == 1, negative: Math.sign(gal.change_xp) == -1 }">
                            <span v-if="gal.change_xp != 0">{{ gal.change_xp.toLocaleString() }}</span>
                        </td>
                        <td class="text-right" style="width: 20%">{{ gal.tick.created_at }}</td>
                    </tr>
                </tbody>
                <tfoot v-if="pager.total > pager.per_page"><tr><td colspan="100"><pager :pager.sync="pager" v-if="!loading"></pager></td></tr></tfoot>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
        data() {
            return {
                data: {},
                page: 1,
                loading: true,
                pager: {}
            };
        },
        methods: {
            loadHistory: function() {
                this.loading = true;
                axios.get('/api/v1/galaxies/' + this.id + '/history', {
                    params: {
                        page: this.page
                    }
                }).then((response) => {
                    this.data = response.data.data;
                    this.pager = response.data;
                    this.loading = false;
                });
            }
        },
        mounted() {
            this.loadHistory();
        }
    }
</script>
