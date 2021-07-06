<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default mb-3">
                    <div class="card-header">Member</div>

                    <div class="card-body">
                        <preloader :loading.sync="loadingMember"></preloader>
                        <div v-if="!loadingMember">
                            <form @submit.prevent="handleSubmit">
                                <div class="form-group">
                                    <label for="name">PA Nick</label><br>
                                    <input type="text" v-model="member.name"><br/>
                                </div>
                                <div class="form-group">
                                    <label for="coords">Your Co-ords</label><br>
                                    <input type="number" id="coord_x" placeholder="x" size="2" v-model.number="member.x" style="width: 40px">:
                                    <input type="number" id="coord_y" placeholder="y" size="2" v-model.number="member.y" style="width: 40px">:
                                    <input type="number" id="coord_z" placeholder="z" size="2" v-model.number="member.z" style="width: 40px">
                                </div>
                                <div class="form-group">
                                    <label for="timezone">Timezone (relative to UTC)</label><br>
                                    <select v-model="member.timezone">
                                        <option v-bind:value="'-12:00'">-12</option>
                                        <option v-bind:value="'-11:00'">-11</option>
                                        <option v-bind:value="'-10:00'">-10</option>
                                        <option v-bind:value="'-09:00'">-9</option>
                                        <option v-bind:value="'-08:00'">-8</option>
                                        <option v-bind:value="'-07:00'">-7</option>
                                        <option v-bind:value="'-06:00'">-6</option>
                                        <option v-bind:value="'-05:00'">-5</option>
                                        <option v-bind:value="'-04:00'">-4</option>
                                        <option v-bind:value="'-03:00'">-3</option>
                                        <option v-bind:value="'-02:00'">-2</option>
                                        <option v-bind:value="'-01:00'">-1</option>
                                        <option v-bind:value="'+00:00'">0</option>
                                        <option v-bind:value="'+01:00'">+1</option>
                                        <option v-bind:value="'+02:00'">+2</option>
                                        <option v-bind:value="'+03:00'">+3</option>
                                        <option v-bind:value="'+04:00'">+4</option>
                                        <option v-bind:value="'+05:00'">+5</option>
                                        <option v-bind:value="'+06:00'">+6</option>
                                        <option v-bind:value="'+07:00'">+7</option>
                                        <option v-bind:value="'+08:00'">+8</option>
                                        <option v-bind:value="'+09:00'">+9</option>
                                        <option v-bind:value="'+10:00'">+10</option>
                                        <option v-bind:value="'+11:00'">+11</option>
                                        <option v-bind:value="'+12:00'">+12</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label><br>
                                    <input type="text" v-model="member.phone"><br/>
                                </div>
                                <div class="form-group">
                                    <label for="notes">Notes</label><br>
                                    <textarea v-model="member.notes"></textarea><br/>
                                </div>
                                <div class="form-group">
                                    <label for="distorters">Distorters</label><br>
                                    <input type="number" v-model="member.distorters"><br/>
                                </div>
                                <!-- <div class="form-group">
                                    <label for="military_centres">Military centres</label><br>
                                    <input type="number" v-model="member.military_centres"><br/>
                                </div> -->
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-3">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Activities</div>

                    <div class="card-body">
                        <p>Below you can view a list of every request made to the api by this user.
                          This does not correspond directly to the url routes but should give you an idea of what this user is accessing.</p>
                        <router-link :to="{ name: 'memberActivity', params: { id: member.id } }"><a><button class="btn btn-primary">Log</button></a></router-link>
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
                'member': {},
                'user': {},
                'message': '',
                loadingMember: true
            };
        },
        methods: {
            handleSubmit() {
                axios.put('/api/v1/members/' + this.$route.params.id, this.member).then((response) => {
                  this.$notify({
                    group: 'foo',
                    title: 'Success',
                    text: 'Details saved',
                    type: 'success'
                  });
                });
            }
        },
        mounted() {
            axios.get('/api/v1/members/' + this.$route.params.id)
            .then((response) => {
                this.member = response.data;
                this.loadingMember = false;
                if(this.member.planet) {
                  this.member.x = this.member.planet.x;
                  this.member.y = this.member.planet.y;
                  this.member.z = this.member.planet.z;
                }
            });
            this.user = this.$currentUser;
        },
    }
</script>
