<template>
    <div class="card card-default has-table mb-3">
        <div class="card-header">
            History
            <router-link :to="{ name: 'planetHistory', params: { id: id }}" class="float-right" v-if="pager.total > pager.per_page && !loading"><button class="btn btn-sm btn-link">show all</button></router-link>
        </div>

        <div class="card-body">
            <preloader :loading.sync="loading"></preloader>
            <table id="planets" class="table table-striped table-bordered" style="width:100%;table-layout: auto;" v-if="!loading">
              <thead>
                  <tr>
                      <th class="text-right align-middle">Tick</th>
                      <th class="text-right">Rank</th>
                      <th class="text-center" colspan="2">Size</th>
                      <th class="text-center" colspan="2">Value</th>
                      <th class="text-center" colspan="2">Score</th>
                      <th class="text-center" colspan="2">Xp</th>
                      <th class="text-right">Date / Time</th>
                  </tr>
              </thead>
              <tbody>
                  <tr v-for="item in data">
                      <td class="text-right">{{ item.tick.tick }}</td>
                      <td class="text-right">{{ item.rank_score }}</td>
                      <td class="text-right" style="width: 20%">{{ item.size.toLocaleString() }}</td>
                      <td class="text-right" v-bind:class="{ positive: Math.sign(item.change_size) == 1, negative: Math.sign(item.change_size) == -1 }">
                          <span v-if="item.change_size != 0">{{ item.change_size.toLocaleString() }}</span>
                      </td>
                      <td class="text-right" style="width: 20%">{{ item.value.toLocaleString() }}</td>
                      <td class="text-right" v-bind:class="{ positive: Math.sign(item.change_value) == 1, negative: Math.sign(item.change_value) == -1 }">
                          <span v-if="item.change_value != 0">{{ item.change_value.toLocaleString() }}</span>
                      </td>
                      <td class="text-right" style="width: 20%">{{ item.score.toLocaleString() }}</td>
                      <td class="text-right" v-bind:class="{ positive: Math.sign(item.change_score) == 1, negative: Math.sign(item.change_score) == -1 }">
                          <span v-if="item.change_score != 0">{{ item.change_score.toLocaleString() }}</span>
                      </td>
                      <td class="text-right" style="width: 20%">{{ item.xp.toLocaleString() }}</td>
                      <td class="text-right" v-bind:class="{ positive: Math.sign(item.change_xp) == 1, negative: Math.sign(item.change_xp) == -1 }">
                          <span v-if="item.change_xp != 0">{{ item.change_xp.toLocaleString() }}</span>
                      </td>
                      <td class="text-right" style="width: 20%">{{ item.tick.created_at }}</td>
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
                axios.get('api/v1/planets/' + this.id + '/history', {
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
        watch: {
           '$route': 'loadHistory'
        },
        mounted() {
            this.loadHistory();
        }
    }
</script>
