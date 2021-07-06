<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-default mb-3">
                    <div class="card-header">
                        Parser<br/>
                        <small>Paste a scan link into the field below to add scans to the site.</small>
                    </div>

                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="form-group">
                                <label for="name">Group link</label><br>
                                <textarea v-model="url"></textarea><br/>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-default mb-3">
                    <div class="card-header">Request</div>

                    <div class="card-body">
                        <form @submit.prevent="request">
                            <div class="form-group">
                                <label for="coords">Co-ords</label><br>
                                <input type="number" id="coord_x" placeholder="x" size="2" v-model.number="x" style="width: 40px">:
                                <input type="number" id="coord_y" placeholder="y" size="2" v-model.number="y" style="width: 40px">:
                                <input type="number" id="coord_z" placeholder="z" size="2" v-model.number="z" style="width: 40px">
                            </div>
                            <div class="form-group">
                              <label>Type</label></br>
                              <label>P</label>
                              <input type="checkbox" value="p" v-model="requestType">
                              <label>D</label>
                              <input type="checkbox" value="d" v-model="requestType">
                              <label>U</label>
                              <input type="checkbox" value="u" v-model="requestType">
                              <label>N</label>
                              <input type="checkbox" value="n" v-model="requestType">
                              <label>J</label>
                              <input type="checkbox" value="j" v-model="requestType">
                              <label>A</label>
                              <input type="checkbox" value="a" v-model="requestType">
                            </div>
                            <button type="submit" class="btn btn-primary">Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center" v-if="myrequests.length">
            <div class="col-md-12">

                <div class="card card-default mb-3 has-table">
                    <div class="card-header">My requests (Last 24h)</div>

                    <div class="card-body">
                        <table id="members" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                  <th class="align-bottom" rowspan="2">#</th>
                                  <th>Co-ords</th>
                                  <th>Type</th>
                                  <th>Link</th>
                                  <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(request, index) in myrequests">
                                    <td>{{ index+1 }}</td>
                                    <td><router-link :to="{ name: 'galaxy', params: { id: request.planet.galaxy_id }}"><a>{{ request.planet.x }}:{{ request.planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: request.planet.id }}"><a>{{ request.planet.z }}</a></router-link></td>
                                    <td>{{ request.scan_type }}</td>
                                    <td>
                                      <span v-if="!request.scan_id">PENDING</span>
                                      <a v-if="request.scan_id" v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+request.scan.pa_id" target="_blank">VIEW</a>
                                    </td>
                                    <td><button v-on:click="deleteMyRequest(request.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center" v-if="requests.length">
            <div class="col-md-12">

                <div class="card card-default mb-3 has-table">
                    <div class="card-header">Scan requests</div>

                    <div class="card-body">
                        <table id="members" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                  <th class="align-bottom" rowspan="2">#</th>
                                  <th>Co-ords</th>
                                  <th>Type</th>
                                  <th>Request by</th>
                                  <th>Link</th>
                                  <th>Time</th>
                                  <th v-if="settings.role == 'Admin'">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(request, index) in requests">
                                    <td>{{ index+1 }}</td>
                                    <td><router-link :to="{ name: 'galaxy', params: { id: request.planet.galaxy_id }}"><a>{{ request.planet.x }}:{{ request.planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: request.planet.id }}"><a>{{ request.planet.z }}</a></router-link></td>
                                    <td>{{ request.scan_type }}</td>
                                    <td>{{ request.user.name }}</td>
                                    <td><a target="_BLANK" v-bind:href="'https://game.planetarion.com/waves.pl?id='+request.scan_type_id+'&x='+request.planet.x+'&y='+request.planet.y+'&z='+request.planet.z" class="btn btn-primary btn-sm">attempt <i class="fa fa-arrow-right"></i></a></td>
                                    <td>{{ request.created_at }}</td>
                                    <td v-if="settings.role == 'Admin'"><button v-on:click="deleteMyRequest(request.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <scan-queue></scan-queue>
            </div>
        </div>

    </main>
</template>

<script>
    export default {
        props: ['settings'],
        data() {
            return {
                message: "",
                url: "",
                x: "",
                y: "",
                z: "",
                requestType: [],
                requests: {},
                myrequests: {},
                user: {}
            };
        },
        methods: {
            submit() {
                axios.post('/api/v1/parser', {
                    url: this.url
                }).then((response) => {
                  var message = "";
                  response.data.forEach(function(v, k) {
                      message = message + v + '<br/>';
                  });
                  this.url = "";
                  this.$notify({
                    group: 'foo',
                    title: 'Success',
                    text: message,
                    type: 'success'
                  });
                  this.loadRequests();
                  this.loadMyRequests();
                });
            },
            request() {
                axios.post('/api/v1/scanrequest', {
                    x: this.x,
                    y: this.y,
                    z: this.z,
                    scan_type: this.requestType
                }).then((response) => {
                  this.x = "";
                  this.y = "";
                  this.z = "";
                  this.requestType = [];
                  this.$notify({
                    group: 'foo',
                    title: 'Success',
                    text: 'Scan requested',
                    type: 'success'
                  });
                  this.loadRequests();
                  this.loadMyRequests();
                });
            },
            deleteMyRequest(id) {
                if(confirm("Are you sure?")) {
                    axios.delete('/api/v1/scanrequest/'+id)
                    .then((response) => {
                        this.$notify({
                          group: 'foo',
                          title: 'Success',
                          text: 'Request deleted',
                          type: 'success'
                        });
                        this.loadMyRequests();
                        this.loadRequests();
                    });
                }
            },
            loadRequests() {
                axios.get('/api/v1/scanrequest').then((response) => {
                    this.requests = response.data;
                });
            },
            loadMyRequests() {
                axios.get('/api/v1/scanrequest/my').then((response) => {
                    this.myrequests = response.data;
                });
            }
        },
        mounted() {
            this.loadRequests();
            this.loadMyRequests();
            this.user = this.$currentUser;
            this.tick = this.$tick;
        }
    }
</script>
