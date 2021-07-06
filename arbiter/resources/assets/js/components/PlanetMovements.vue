<template>
    <div class="card card-default" v-bind:class="{ 'has-table': pager.total }">
        <div class="card-header">
            Exiles
        </div>

        <div class="card-body">
            <preloader :loading.sync="loading"></preloader>
            <table id="planets" class="table table-striped table-bordered" style="width:100%;table-layout: auto;" v-if="!loading && pager.total">
                <thead>
                    <tr>
                        <th class="text-right">From</th>
                        <th class="text-right">To</th>
                        <th class="text-right">Current</th>
                        <th class="text-center">Ruler</th> 
                        <th class="align-bottom text-center">Race</th>
                        <th class="align-bottom text-center">Size</th>
                        <th class="align-bottom text-center">Value</th>
                        <th class="align-bottom text-center">Score</th>
                        <th class="align-bottom text-center">Xp</th>
                        <th class="align-bottom text-center">Intel</th>
                        <th class="text-right align-middle">Tick</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in data" v-if="item.planet">
                        <td class="text-right" v-bind:class="{ 'planet-new': !item.from_x }">
                            <span v-if="item.from_x">{{ item.from_x + ':' + item.from_y + ':' + item.from_z }}</span>
                            <span v-if="!item.from_x">new</span>
                        </td>
                        <td class="text-right"><router-link :to="{ name: 'galaxy', params: { id: item.planet.galaxy_id }}"><a>{{ item.to_x }}:{{ item.to_y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: item.planet.id }}"><a>{{ item.to_z }}</a></router-link></td>
                        <td class="text-right" v-bind:class="{ 'planet-deleted': item.planet.deleted_at }">
                            <span v-if="!item.planet.deleted_at">{{ item.planet.x }}:{{ item.planet.y }}:{{ item.planet.z }}</span>
                            <span v-if="item.planet.deleted_at">deleted</span>
                        </td>
                        <td width="100%"><i class="fas fa-shield-alt" v-if="item.planet.is_protected" style="margin-right: 5px;" v-tooltip:top="'In protection for ' + (24-item.planet.age) + ' more tick(s)'"></i><span v-tooltip:top="item.planet.ruler_name + ' of ' + item.planet.planet_name">{{ item.planet.ruler_name }}</span></td>
                        <td><span class="race" v-bind:class="item.planet.race" v-if="item.planet">{{ item.planet.race }}</span></td>
                        <td class="text-right"><span v-tooltip:top="item.planet.size.toLocaleString()" v-if="item.planet">{{ item.planet.size | shorten }}</span></td>
                        <td class="text-right">
                            <span v-bind:class="{ basher: item.planet.basher }" v-if="item.planet"><span v-tooltip:top="item.planet.value.toLocaleString()">{{ item.planet.value | shorten }}</span></span>
                        </td>
                        <td class="text-right">
                            <span v-bind:class="{ basher: item.planet.basher }" v-if="item.planet"><span v-tooltip:top="item.planet.score.toLocaleString()">{{ item.planet.score | shorten }}</span></span>
                        </td>
                        <td class="text-right"><span v-tooltip:top="item.planet.xp.toLocaleString()" v-if="item.planet">{{ item.planet.xp | shorten }}</span></td>
                        <td class="text-right"><span v-if="item.planet">{{ item.planet.nick }} <span v-if="item.planet.nick && item.planet.alliance"> </span><router-link v-if="item.planet.alliance" :to="{ name: 'alliance', params: { id: item.planet.alliance.id }}"><a>{{ item.planet.alliance.name }}</a></router-link></span></td>
                        <td class="text-right">{{ item.tick }}</td>
                    </tr>
              </tbody>
              <tfoot v-if="pager.total > pager.per_page"><tr><td colspan="100"><pager :pager.sync="pager" v-if="!loading"></pager></td></tr></tfoot>
            </table>
            <p v-if="!pager.total && !loading" class="no-data">No exiles tracked</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['planetId', 'galaxyId'],
        data() {
            return {
                data: {},
                loading: true,
                page: 1,
                pager: {}
            };
        },
        methods: {
            loadHistory: function() {
                this.loading = true;
                axios.get('/api/v1/planets/movements', {
                    params: {
                        planet_id: this.planetId,
                        galaxy_id: this.galaxyId,
                        page: this.page
                    }
                }).then((response) => {
                    this.data = response.data.data;
                    this.loading = false;
                    this.pager = response.data;
                });
            }
        },
        watch: {
           '$route': 'loadHistory'
        },
        mounted() {
            this.loadHistory();
        }
    }
</script>
