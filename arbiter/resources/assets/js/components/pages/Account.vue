<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Account</div>

                    <div class="card-body">
                        <preloader :loading.sync="userLoading"></preloader>
                        <form @submit.prevent="handleSubmit" v-if="!userLoading">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">PA Nick</label><br>
                                        <input type="text" v-model="name"><br/>
                                        <small class="help">The nickname you are known by ingame</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="timezone">Timezone (relative to UTC)</label><br>
                                        <select v-model="timezone">
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
                                        </select><br/>
                                        <small class="help">This is used to display your current time on the members page and calculate the tick time on telegram</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone</label><br>
                                        <input type="text" v-model="phone"><br/>
                                        <small class="help">Displayed on the members page</small>
                                    </div>
                                    <div class="form-group" v-if="settings.twilio">
                                        <span class="toggle-wrapper" style="margin-left: 0"><span class="toggle-label">Allow Calls</span><toggle-button :width="40" v-model="allow_calls" class="toggle-button" /></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="notes">Notes</label><br>
                                        <textarea v-model="notes"></textarea><br/>
                                        <small class="help">Any information about online times, when to call, etc</small>
                                    </div>
                                    <div class="form-group" v-if="settings.telegram">
                                        <label for="tg_username">Telegram ID</label><br>
                                        <input type="number" v-model="tg_username" disabled><br/>
                                        <small class="help">Use !setnick &lt;PA Nick&gt; in the telegram channel to link your TG account to your webby account</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="coords">Your Co-ords</label><br>
                                        <input type="number" id="coord_x" placeholder="x" size="2" v-model.number="x" style="width: 40px">:
                                        <input type="number" id="coord_y" placeholder="y" size="2" v-model.number="y" style="width: 40px">:
                                        <input type="number" id="coord_z" placeholder="z" size="2" v-model.number="z" style="width: 40px">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="distorters">Distorters</label><br>
                                        <input type="number" v-model="distorters"><br/>
                                        <small class="help">Used to display whether or not you can be scanned by targets on attack page</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="stealth">Stealth</label><br>
                                        <input type="number" v-model="stealth"><br/>
                                        <small class="help">Used on the covop page to calculate chance of success</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center" v-if="notification_email">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="notification_email">Notifications Email</label><br>
                                        <input type="text" v-model="notification_email" disabled><br/>
                                        <small class="help">Add this as your notifications email ingame to have your incoming automatically tracked, scans requested and calcs generated</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                </div>
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
                'account': {},
                'name': '',
                'x': '',
                'y': '',
                'z': '',
                'timezone': '',
                'notes': '',
                'phone': '',
                'distorters': '',
                'military_centres': '',
                'tg_username': '',
                'notification_email': '',
                stealth: '',
                userLoading: true
            };
        },
        methods: {
            handleSubmit() {
                axios.post('/api/v1/account', {
                    x: this.x,
                    y: this.y,
                    z: this.z,
                    name: this.name,
                    timezone: this.timezone,
                    notes: this.notes,
                    phone: this.phone,
                    distorters: this.distorters,
                    military_centres: this.military_centres,
                    stealth: this.stealth,
                    allow_calls: this.allow_calls
                }).then((response) => {
                    this.$parent.loadSettings();
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
            axios.get('/api/v1/account')
            .then((response) => {
                this.account            = response.data;
                this.name               = this.account.name;
                this.timezone           = this.account.timezone;
                this.notes              = this.account.notes;
                this.phone              = this.account.phone;
                this.distorters         = this.account.distorters;
                this.military_centres   = this.account.military_centres
                this.tg_username        = this.account.tg_username;
                this.stealth            = this.account.stealth;
                this.notification_email = this.account.notification_email;
                this.allow_calls        = this.account.allow_calls;
                if(this.account.planet) {
                  this.x = this.account.planet.x;
                  this.y = this.account.planet.y;
                  this.z = this.account.planet.z;
                }
                this.userLoading = false;
            });
        },
    }
</script>
