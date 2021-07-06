<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card card-default mb-3 has-table" v-if="battlegroup">
                    <div class="card-header">
                        Battlegroup: {{ battlegroup.name }}
                    </div>

                    <div class="card-body">

                    </div>
                </div>

                <div class="card card-default mb-3 has-table" v-if="battlegroup.members_pending.length">
                    <div class="card-header">
                        Pending members<br/>
                        <small>Only the owner or admins can accept or decline pending members.</small>
                    </div>

                    <div class="card-body">
                        <table id="members" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                              <tr>
                                  <th>Nick</th>
                                  <th class="align-bottom text-center" rowspan="2">Race</th>
                                  <th class="align-bottom text-center" rowspan="2">Size</th>
                                  <th class="align-bottom text-center" rowspan="2">Value</th>
                                  <th class="align-bottom text-center" rowspan="2">Score</th>
                                  <th class="align-bottom text-center" rowspan="2">Xp</th>
                                  <th class="text-center" colspan="3">Growth</th>
                                  <th>Options</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="(member, index) in battlegroup.members_pending">
                                  <td style="width: 100%">{{ member.name }}</td>
                                  <td><span class="race" v-bind:class="member.planet.race">{{ member.planet.race }}</span></td>
                                  <td class="text-right"><span v-tooltip:top="member.planet.size.toLocaleString()">{{ member.planet.size | shorten }}</span></td>
                                  <td class="text-right" v-bind:class="{ basher: member.planet.basher }"><span v-tooltip:top="member.planet.value.toLocaleString()">{{ member.planet.value | shorten }}</span></td>
                                  <td class="text-right" v-bind:class="{ basher: member.planet.basher }"><span v-tooltip:top="member.planet.score.toLocaleString()">{{ member.planet.score | shorten }}</span></td>
                                  <td class="text-right"><span v-tooltip:top="member.planet.xp.toLocaleString()">{{ member.planet.xp | shorten }}</span></td>
                                  <td class="options">
                                      <span v-if="battlegroup.is_owner || settings.role == 'Admin'">
                                          <span><button v-on:click="accept(member.id)" class="btn btn-sm btn-success">accept</button></span>
                                          <span><button v-on:click="decline(member.id)" class="btn btn-sm btn-danger">decline</button></span>
                                      </span>
                                  </td>
                              </tr>
                          </tbody>
                        </table>
                    </div>
                </div>

                <div class="card card-default mb-3 has-table" v-if="battlegroup.members">
                    <div class="card-header">
                        Members<br/>
                        <small>Only the owner or admins can remove members.</small>
                    </div>

                    <div class="card-body">
                        <table id="members" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                              <tr>
                                <th rowspan="2">Nick</th>
                                <th class="align-bottom text-center" rowspan="2">Race</th>
                                <th class="align-bottom text-center" rowspan="2">Size</th>
                                <th class="align-bottom text-center" rowspan="2">Value</th>
                                <th class="align-bottom text-center" rowspan="2">Score</th>
                                <th class="align-bottom text-center" rowspan="2">Xp</th>
                                <th class="text-center" colspan="3">Growth</th>
                                <th rowspan="2">Options</th>
                              </tr>
                              <tr>
                                  <th class="text-center">Size</th>
                                  <th class="text-center">Value</th>
                                  <th class="text-center">Score</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="(member, index) in battlegroup.members">
                                  <td style="width: 100%">{{ member.name }}</td>
                                  <td><span class="race" v-bind:class="member.planet.race">{{ member.planet.race }}</span></td>
                                  <td class="text-right"><span v-tooltip:top="member.planet.size.toLocaleString()">{{ member.planet.size | shorten }}</span></td>
                                  <td class="text-right" v-bind:class="{ basher: member.planet.basher }"><span v-tooltip:top="member.planet.value.toLocaleString()">{{ member.planet.value | shorten }}</span></td>
                                  <td class="text-right" v-bind:class="{ basher: member.planet.basher }"><span v-tooltip:top="member.planet.score.toLocaleString()">{{ member.planet.score | shorten }}</span></td>
                                  <td class="text-right"><span v-tooltip:top="member.planet.xp.toLocaleString()">{{ member.planet.xp | shorten }}</span></td>
                                  <td class="text-right" v-bind:class="{ yellow: member.planet.growth_percentage_size == 0.0, green: member.planet.growth_percentage_size > 0.0, red: member.planet.growth_percentage_size < 0.0 }"><span v-tooltip:top="member.planet.growth_size.toLocaleString()">{{ member.planet.growth_percentage_size | shorten }}%</span></td>
                                  <td class="text-right" v-bind:class="{ yellow: member.planet.growth_percentage_value == 0.0, green: member.planet.growth_percentage_value > 0.0, red: member.planet.growth_percentage_value < 0.0 }"><span v-tooltip:top="member.planet.growth_value.toLocaleString()">{{ member.planet.growth_percentage_value | shorten }}%</span></td>
                                  <td class="text-right" v-bind:class="{ yellow: member.planet.growth_percentage_score == 0.0, green: member.planet.growth_percentage_score > 0.0, red: member.planet.growth_percentage_score < 0.0 }"><span v-tooltip:top="member.planet.growth_score.toLocaleString()">{{ member.planet.growth_percentage_score | shorten }}%</span></td>
                                  <td class="options">
                                      <span v-if="battlegroup.is_owner || settings.role == 'Admin' || settings.role == 'BC'">
                                          <span><button v-on:click="remove(member.id)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></span>
                                      </span>
                                  </td>
                              </tr>
                          </tbody>
                        </table>
                    </div>
                </div>

                <div class="card card-default mb-3" v-if="battlegroup.non_members.length && (battlegroup.is_owner || settings.role == 'Admin' || settings.role == 'BC')">
                    <div class="card-header">
                        Add members<br/>
                        <small>You can only add members who have set their planet.</small>
                    </div>

                    <div class="card-body">
                        <form @submit.prevent="addMember">
                            <select v-model="user">
                                <option v-for="member in battlegroup.non_members" v-bind:value="member.id">
                                  {{ member.name }}
                                </option>
                            </select>
                            <button type="submit" class="btn btn-primary">Add</button>
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
                'battlegroup': {
                    'members': {},
                    'members_pending': {},
                    'non_members': {}
                },
                'user': ''
            };
        },
        methods: {
            loadBattlegroup: function() {
                axios.get('/api/v1/battlegroup/' + this.$route.params.id)
                .then((response) => {
                    this.battlegroup = response.data;
                });
            },
            accept: function(id) {
                axios.get('/api/v1/battlegroup/' + this.$route.params.id + '/user/' + id + '/accept')
                .then((response) => {
                    this.battlegroup = response.data;
                });
            },
            decline: function(id) {
                axios.get('/api/v1/battlegroup/' + this.$route.params.id + '/user/' + id + '/decline')
                .then((response) => {
                    this.battlegroup = response.data;
                });
            },
            remove: function(id) {
                axios.delete('/api/v1/battlegroup/' + this.$route.params.id + '/user/' + id)
                .then((response) => {
                    this.battlegroup = response.data;
                });
            },
            addMember() {
                axios.post('/api/v1/battlegroup/' + this.$route.params.id + '/user', {
                    user: this.user
                }).then((response) => {
                    this.battlegroup = response.data;
                    this.$notify({
                        group: 'foo',
                        title: 'Success',
                        text: 'User added',
                        type: 'success'
                    });
                });
            },
        },
        mounted() {
            this.loadBattlegroup();
        },
    }
</script>
