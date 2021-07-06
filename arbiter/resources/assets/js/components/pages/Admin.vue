<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Intel parser</div>

                    <div class="card-body">
                        <small>Use the field below to update intel on the site from ingame. Go to Alliance > Intel > Export, you need to see the full xml format, you can do this by viewing the page source. Copy and paste this whole page into the field below.</small><br/><br/>
                        <form @submit.prevent="submitIntel">
                            <div class="form-group">
                                <label for="name">XML</label><br>
                                <textarea v-model="xml"></textarea><br/>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center my-3">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Settings</div>

                    <div class="card-body">
                        <preloader :loading.sync="loadingSettings"></preloader>
                        <div v-if="!loadingSettings">
                            <form @submit.prevent="submitSettings">
                                <div class="form-group">
                                    <label for="name">Alliance</label><br>
                                    <select v-model="settings.alliance">
                                        <option v-for="option in alliances.data" v-bind:value="option.id">
                                        {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="name">Overview</label><br>
                                    <small>This will appear on the home page to all members</small><br/><br/>
                                    <vue-editor v-model="settings.overview"></vue-editor>
                                </div>
                                <div class="form-group">
                                    <label for="attack">Attack instructions</label><br>
                                    <small>This will appear on attack page to anyone with BC access or above</small><br/><br/>
                                    <vue-editor v-model="settings.attack"></vue-editor>
                                </div>
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
                        <p>Below you can view a list of every request made to the api. This does not correspond directly to the url routes but should give you an idea of what people are accessing.</p>
                        <router-link :to="{ name: 'activity' }"><a><button class="btn btn-primary">Logs</button></a></router-link>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center my-3">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Reset</div>

                    <div class="card-body">
                        <p>Press the button below to reset webby for new round. This will delete all current data except members from the webby and it will parse ship stats.</p>
                        <button class="btn btn-primary" v-on:click="reset()">Reset tools</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import { VueEditor } from 'vue2-editor';
    export default {
        components: {
            VueEditor
        },
        data() {
            return {
                xml: "",
                settings: "",
                alliances: {},
                loadingAlliances: true,
                loadingSettings: true
            };
        },
        methods: {
            submitIntel() {
                axios.post('/api/v1/parser/intel', {
                    xml: this.xml
                }).then((response) => {
                    this.xml = "";
                    this.$notify({
                      group: 'foo',
                      title: 'Success',
                      text: 'Intel was parsed',
                      type: 'success'
                    });
                });
            },
            submitSettings() {
                this.loadingSettings = true;
                axios.post('/api/v1/admin', {
                    attack: this.settings.attack,
                    overview: this.settings.overview,
                    alliance: this.settings.alliance
                }).then((response) => {
                    this.settings = response.data;
                    this.loadingSettings = false;
                    this.$notify({
                      group: 'foo',
                      title: 'Success',
                      text: 'Settings saved',
                      type: 'success'
                    });
                });
            },
            reset: function() {
                if(confirm("Are you sure you want to reset tools?")) {
                    axios.get('/api/v1/admin/reset')
                    .then((response) => {
                        this.$notify({
                          group: 'foo',
                          title: 'Success',
                          text: 'Tools were reset',
                          type: 'success'
                        });
                    });
                }

            },
            loadAlliances: function() {
                axios.get('/api/v1/alliances', {
                    params: {
                        sort: "+name",
                        perPage: 999
                    }
                })
                .then((response) => {
                    this.alliances = response.data;
                    this.loadingAlliances = false;
                });
            },
            loadSettings: function() {
                axios.get('/api/v1/admin')
                .then((response) => {
                    this.settings = response.data;
                    this.loadingSettings = false;
                });
            }
        },
        mounted() {
            this.loadSettings();
            this.loadAlliances();
        }
    }
</script>
