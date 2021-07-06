<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default mb-3" v-bind:class="{ 'has-table': pager.total }">
                    <div class="card-header text-center">Activities log - {{ this.date }}<button class="float-left btn btn-sm btn-link" v-on:click="addDays(-1)"><i class="fas fa-arrow-left"></i></button><button class="float-right btn btn-sm btn-link" v-if="this.days < 0" v-on:click="addDays(1)"><i class="fas fa-arrow-right"></i></button></div>

                    <div class="card-body">
                        <preloader :loading.sync="loading"></preloader>
                        <p v-if="!loading && !pager.total" class="no-data">No activities to show</p>
                        <table id="members" class="table table-striped table-bordered" style="width:100%" v-if="!loading && pager.total">
                            <thead>
                                <tr>
                                <th>Name</th>
                                <th>Url</th>
                                <th>Method</th>
                                <th>Agent</th>
                                <th>Ip</th>
                                <th>Location</th>
                                <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="activity in pager.data">

                                    <td><router-link :to="{ name: 'member', params: { id: activity.user.id }}"><a>{{ activity.user.name }}</a></router-link></td>
                                    <td>{{ activity.url }}</td>
                                    <td>{{ activity.method }}</td>
                                    <td>{{ activity.user_agent }}</td>
                                    <td>{{ activity.ip_address }}</td>
                                    <td>{{ activity.location }}</td>
                                    <td>{{ activity.created_at }}</td>
                                </tr>
                            </tbody>
                            <tfoot v-if="pager.total > this.perPage"><tr><td colspan="100"><pager :pager.sync="pager" v-if="!loading"></pager></td></tr></tfoot>
                        </table>
                    </div>
                </div>
              </div>
            </div>
        </main>
</template>

<script>
    export default {
        data() {
            return {
                'days': 0,
                'date': '',
                'user': '',
                pager: {},
                loading: true,
                page: 1,
                perPage: 50
            };
        },
        methods: {
            loadActivities: function() {
                this.loading = true;
                axios.get('/api/v1/activities', {
                  params: {
                    days: this.days,
                    page: this.page,
                    perPage: this.perPage
                  }
                })
                .then((response) => {
                    this.pager = response.data;
                    this.loading = false;
                });
            },
            addDays: function(days) {
                this.days = this.days+days;
                this.setDate();
            },
            setDate: function() {
                this.date = this.$moment().add(this.days, 'days').format('MMMM Do YYYY');
            }
        },
        watch: {
           'days': 'loadActivities'
        },
        mounted() {
            this.loadActivities();
            this.setDate();
        },
    }
</script>
