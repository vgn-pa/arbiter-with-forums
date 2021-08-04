<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
              <div class="card card-default has-table mb-3" v-if="settings.role == 'Admin' && members.new.length">
                  <div class="card-header">New</div>

                  <div class="card-body">
                      <table id="members" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                              <th>#</th>
                              <th>Nick</th>
                              <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(member, index) in members.new" v-bind:class="{ 'text-danger': !member.is_enabled }">
                                <td>{{ index+1 }}</td>
                                <td>{{ member.name }}</td>
                                <td class="options">
                                    <span v-if="member.id != 1">
                                        <span v-if="!member.is_enabled"><button v-on:click="enable(member.id)" class="btn btn-sm btn-success">enable</button></span>
                                        <span><button v-on:click="remove(member.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></span>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                      </table>
                  </div>
              </div>
                <div class="card card-default has-table mb-3">     
                    <div class="card-header">Members <span class="float-right" v-if="!loadingMembers"><span class="toggle-wrapper" style="margin-left: 0"><span class="toggle-label">Show ships</span><toggle-button :width="40" v-model="showShips" class="toggle-button" /></span></span></div>

                    <div class="card-body">
                        <preloader :loading.sync="loadingMembers"></preloader>
                        <table id="members" class="table table-striped table-bordered" style="width:100%" v-if="!loadingMembers">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nick</th>
                                    <th>Role</th>
                                    <th>Co-ords</th>
                                    <th v-if="showShips">Ships</th>
                                    <th>Phone</th>
                                    <th v-if="settings.telegram">TG user</th>
                                    <th>Local Time</th>
                                    <th>Notes</th>
                                    <th v-if="settings.role == 'Admin'">Last login</th>
                                    <th v-if="settings.role == 'Admin'">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(member, index) in members.enabled" v-bind:class="{ 'text-danger': !member.is_enabled }">
                                    <td>{{ index+1 }}</td>
                                    <td>{{ member.name }}</td>
                                    <td>
                                        <change-role :settings='settings' :roles='roles' :member='member'></change-role>
                                    </td>
                                    <td><span v-if="member.planet"><router-link :to="{ name: 'galaxy', params: { id: member.planet.galaxy_id }}"><a>{{ member.planet.x }}:{{ member.planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: member.planet.id }}"><a>{{ member.planet.z }}</a></router-link></span></td>
                                    <td v-if="showShips">
                                        <table v-if="member.ships.length">
                                            <tr v-for="(ship) in member.ships">
                                                <td>{{ ship.name }}</td>
                                                <td>{{ ship.amount }}</td>
                                                <td>{{ ship.percentage }}%</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td><span v-if="member.phone"><call-component :member.sync="member" v-if="settings.twilio && member.allow_calls"></call-component> {{ member.phone }}</span></td>
                                    <td><span v-if="member.tg_user">{{ member.tg_user.first_name }}<span v-if="member.tg_user.last_name"> {{ member.tg_user.last_name }}</span><span v-if="member.tg_user.username"> (<a target="_BLANK" v-bind:href="'https://t.me/' + member.tg_user.username">{{ member.tg_user.username }}</a>)</span></span></td>
                                    <td>{{ member.timezone }}</td>
                                    <td>{{ member.notes }}</td>
                                    <td v-if="settings.role == 'Admin'">{{ member.last_login }}</td>
                                    <td v-if="settings.role == 'Admin'" class="options">
                                        <span v-if="member.id != 1">
                                            <router-link :to="{ name: 'member', params: { id: member.id }}"><a><button class="btn btn-sm btn-primary"><i class="fa fa-cog"></i></button></a></router-link>
                                            <span v-if="member.is_enabled"><button v-on:click="disable(member.id)" class="btn btn-sm btn-danger">disable</button></span>
                                            <span v-if="!member.is_enabled"><button v-on:click="enable(member.id)" class="btn btn-sm btn-success">enable</button></span>
                                            <span><button v-on:click="remove(member.id)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></span>
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card card-default has-table" v-if="settings.role == 'Admin' && members.disabled.length">
                    <div class="card-header">Disabled</div>

                    <div class="card-body">
                        <table id="members" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                              <tr>
                                <th>#</th>
                                <th>Nick</th>
                                <th>Co-ords</th>
                                <th>Phone</th>
                                <th>Local Time</th>
                                <th>Notes</th>
                                <th>Options</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="(member, index) in members.disabled" v-bind:class="{ 'text-danger': !member.is_enabled }">
                                  <td>{{ index+1 }}</td>
                                  <td>{{ member.name }}</td>
                                  <td><span v-if="member.planet"><router-link :to="{ name: 'galaxy', params: { id: member.planet.galaxy_id }}"><a>{{ member.planet.x }}:{{ member.planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: member.planet.id }}"><a>{{ member.planet.z }}</a></router-link></span></td>
                                  <td>{{ member.phone }}</td>
                                  <td>{{ member.timezone }}</td>
                                  <td>{{ member.notes }}</td>
                                  <td v-if="settings.role == 'Admin'" class="options">
                                      <span v-if="member.id != 1">
                                          <span v-if="!member.is_enabled"><button v-on:click="enable(member.id)" class="btn btn-sm btn-success">enable</button></span>
                                          <span><button v-on:click="remove(member.id)" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></span>
                                      </span>
                                  </td>
                              </tr>
                          </tbody>
                        </table>
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
                'members': {
                    'new': {},
                    'disabled': {},
                    'enabled': {},
                    'admins': {}
                },
                'roles': {},
                'user': {},
                'showShips': false,
                loadingMembers: true
            };
        },
        methods: {
            call: function(id) {
                axios.get('api/v1/members/'+id+'/call')
                .then((response) => {});
            },
            disable: function(id) {
                axios.get('api/v1/members/'+id+'/disable')
                .then((response) => {
                    this.members = response.data;
                    this.$notify({
                      group: 'foo',
                      title: 'Success',
                      text: 'User disabled',
                      type: 'success'
                    });
                });
            },
            enable: function(id) {
                axios.get('api/v1/members/'+id+'/enable')
                .then((response) => {
                    this.members = response.data;
                    this.$notify({
                      group: 'foo',
                      title: 'Success',
                      text: 'User enabled',
                      type: 'success'
                    });
                });
            },
            remove: function(id) {
                if(confirm("Are you sure?")) {
                    axios.get('api/v1/members/'+id+'/delete')
                    .then((response) => {
                        this.members = response.data;
                        this.$notify({
                          group: 'foo',
                          title: 'Success',
                          text: 'User deleted',
                          type: 'success'
                        });
                    });
                }

            },
            admin: function(id) {
                axios.get('api/v1/members/'+id+'/admin')
                .then((response) => {
                    this.members = response.data;
                    this.$notify({
                      group: 'foo',
                      title: 'Success',
                      text: 'User made admin',
                      type: 'success'
                    });
                });
            },
            loadMembers: function() {
                axios.get('api/v1/members')
                .then((response) => {
                    this.members = response.data;
                    this.loadingMembers = false;
                });
            },
            loadRoles: function() {
                axios.get('api/v1/roles')
                .then((response) => {
                    this.roles = response.data;
                });
            }
        },
        mounted() {
            this.loadMembers();
            this.loadRoles();
            this.user = this.$currentUser;
            this.$parent.links = [];
        },
    }
</script>
