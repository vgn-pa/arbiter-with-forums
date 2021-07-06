<template>
    <div class="card card-default mb-3 has-table">
        <div class="card-header">
            History
            <router-link :to="{ name: 'allianceHistory', params: { id: id }}" class="float-right" v-if="pager.total > pager.per_page && !loading"><button class="btn btn-sm btn-link">show all</button></router-link>
        </div>

        <div class="card-body">
            <preloader :loading.sync="loading"></preloader>
            <table id="alliances" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
                <thead>
                    <tr>
                        <th>Tick</th>
                        <th class="text-right">Rank</th>
                        <th class="text-right">Members</th>
                        <th class="text-center" colspan="2">Size</th>
                        <th class="text-center" colspan="2">Value</th>
                        <th class="text-center" colspan="2">Score</th>
                        <th class="text-right">Date / Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="alliance in data">
                        <td class="text-right">{{ alliance.tick.tick }}</td>
                        <td class="text-right">{{ alliance.rank_score }}
                        <td class="text-right">{{ alliance.members }}  <span v-if="alliance.change_members != 0">(<span v-bind:class="{ positive: Math.sign(alliance.change_members) == 1, negative: Math.sign(alliance.change_members) == -1 }">{{ alliance.change_members }}</span>)</span></td>
                        <td class="text-right" style="width: 25%">{{ alliance.size }}</td>
                        <td class="text-right" v-bind:class="{ positive: Math.sign(alliance.change_size) == 1, negative: Math.sign(alliance.change_size) == -1 }">
                            <span v-if="alliance.change_size != 0">{{ alliance.change_size.toLocaleString() }}</span>
                        </td>
                        <td class="text-right" style="width: 25%">{{ alliance.total_value }}</td>
                        <td class="text-right" v-bind:class="{ positive: Math.sign(alliance.change_value) == 1, negative: Math.sign(alliance.change_value) == -1 }">
                            <span v-if="alliance.change_value != 0">{{ alliance.change_value.toLocaleString() }}</span>
                        </td>
                        <td class="text-right" style="width: 25%">{{ alliance.counted_score }}</td>
                        <td class="text-right" v-bind:class="{ positive: Math.sign(alliance.change_score) == 1, negative: Math.sign(alliance.change_score) == -1 }">
                            <span v-if="alliance.change_score != 0">{{ alliance.change_score.toLocaleString() }}</span>
                        </td>
                        <td class="text-right" style="width: 25%">{{ alliance.tick.created_at }}</td>
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
                axios.get('/api/v1/alliances/' + this.id + '/history', {
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
