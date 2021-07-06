<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card card-default mb-3 has-table" v-if="battlegroups">
                    <div class="card-header">
                        Battlegroups<br/>
                        <small>Scores are accumulated by planets while they are in the BG only. Scores are shown by average so smaller BGs are not at a disadvantage.</small>
                    </div>

                    <div class="card-body">
                        <preloader :loading.sync="loading"></preloader>
                        <table id="alliances" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
                            <thead>
                                <tr>
                                    <th rowspan="2">#</th>
                                    <th rowspan="2">Name</th>
                                    <th rowspan="2">Members</th>
                                    <th rowspan="2">Owner</th>
                                    <th colspan="4" class="text-center">Averages</th>
                                    <th colspan="4" class="text-center">Growth</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th><sort-heading field="avg_size" text="Size" :sort.sync="sort"></sort-heading></th>
                                    <th><sort-heading field="avg_value" text="Value" :sort.sync="sort"></sort-heading></th>
                                    <th><sort-heading field="avg_score" text="Score" :sort.sync="sort"></sort-heading></th>
                                    <th><sort-heading field="avg_xp" text="Xp" :sort.sync="sort"></sort-heading></th>
                                    <th>Size</th>
                                    <th>Value</th>
                                    <th>Score</th>
                                    <th>Xp</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(battlegroup, index) in battlegroups" v-bind:class="{ is_alliance: battlegroup.is_bg }">
                                    <td>{{ parseInt(index)+1 }}</td>
                                    <td style="width: 100%"><router-link :to="{ name: 'battlegroup', params: { id: battlegroup.id }}">{{ battlegroup.name }}</router-link></td>
                                    <td>{{ battlegroup.member_count }}</td>
                                    <td>{{ battlegroup.owner.name }}</td>
                                    <td>{{ battlegroup.avg_size | shorten }}</td>
                                    <td>{{ battlegroup.avg_value | shorten }}</td>
                                    <td>{{ battlegroup.avg_score | shorten }}</td>
                                    <td>{{ battlegroup.avg_xp | shorten }}</td>
                                    <td class="text-right" v-bind:class="{ yellow: battlegroup.growth_percentage_size == 0.0, green: battlegroup.growth_percentage_size > 0.0, red: battlegroup.growth_percentage_size < 0.0 }"><span v-tooltip:top="battlegroup.growth_size.toLocaleString()">{{ battlegroup.growth_percentage_size | shorten }}%</span></td>
                                    <td class="text-right" v-bind:class="{ yellow: battlegroup.growth_percentage_value == 0.0, green: battlegroup.growth_percentage_value > 0.0, red: battlegroup.growth_percentage_value < 0.0 }"><span v-tooltip:top="battlegroup.growth_value.toLocaleString()">{{ battlegroup.growth_percentage_value | shorten }}%</span></td>
                                    <td class="text-right" v-bind:class="{ yellow: battlegroup.growth_percentage_score == 0.0, green: battlegroup.growth_percentage_score > 0.0, red: battlegroup.growth_percentage_score < 0.0 }"><span v-tooltip:top="battlegroup.growth_score.toLocaleString()">{{ battlegroup.growth_percentage_score | shorten }}%</span></td>
                                    <td class="text-right" v-bind:class="{ yellow: battlegroup.growth_percentage_xp == 0.0, green: battlegroup.growth_percentage_xp > 0.0, red: battlegroup.growth_percentage_xp < 0.0 }"><span v-tooltip:top="battlegroup.growth_xp.toLocaleString()">{{ battlegroup.growth_percentage_xp | shorten }}%</span></td>
                                    <td>
                                        <span v-if="!battlegroup.is_bg"><button v-on:click="join(battlegroup.id)" :disabled="battlegroup.is_pending || !settings.user.planet_id" class="btn btn-sm btn-success" v-tooltip:top="'Request to join'"><i class="fa fa-plus"></i></button></span>
                                        <span v-if="settings.role == 'Admin'"><button v-on:click="remove(battlegroup.id)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card card-default mb-3" v-if="settings.role == 'Admin' || settings.role == 'BC'">
                    <div class="card-header">Create battlegroup</div>

                    <div class="card-body">
                        <form @submit.prevent="handleSubmit">
                            <div class="form-group">
                                <label for="name">Name</label><br>
                                <input type="text" v-model="name"><br/>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
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
                'sort': '-avg_score',
                'battlegroups': {},
                'name': '',
                'loading': true
            };
        },
        methods: {
            loadBattlegroups: function() {
                this.loading = true;
                axios.get('/api/v1/battlegroup', {
                    params: {
                        sort: this.sort
                    }
                })
                .then((response) => {
                    this.battlegroups = response.data;
                    this.loading = false;
                });
            },
            handleSubmit() {
                axios.post('/api/v1/battlegroup', {
                    name: this.name
                }).then((response) => {
                    this.loadBattlegroups();
                    this.$notify({
                        group: 'foo',
                        title: 'Success',
                        text: 'Battlegroup added',
                        type: 'success'
                    });
                    this.name = '';
                });
            },
            remove: function(id) {
                if(confirm("Are you sure?")) {
                    axios.delete('/api/v1/battlegroup/'+id)
                    .then((response) => {
                        this.battlegroups = response.data;
                        this.$notify({
                          group: 'foo',
                          title: 'Success',
                          text: 'Battlegroup deleted',
                          type: 'success'
                        });
                    });
                }

            },
            join: function(id) {
                axios.get('/api/v1/battlegroup/'+id+'/join')
                .then((response) => {
                    this.$notify({
                      group: 'foo',
                      title: 'Success',
                      text: 'Requested to join BG',
                      type: 'success'
                    });
                });
            }
        },
        watch: {
           'sort': 'loadBattlegroups',
        },
        mounted() {
            this.loadBattlegroups();
        },
    }
</script>
