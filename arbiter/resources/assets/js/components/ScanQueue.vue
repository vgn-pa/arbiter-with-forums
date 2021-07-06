<template>
    <div class="card card-default mb-3 has-table" v-if="scans.length">
        <div class="card-header">Scans queued: {{ scans.length }}</div>

        <div class="card-body">
            <table id="members" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                      <th class="align-bottom" rowspan="2">#</th>
                      <th>Scan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(scan, index) in scans">
                        <td>{{ index+1 }}</td>
                        <td><a target="_BLANK" v-bind:href="scan.scanUrl">{{ scan.scanUrl }}</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                scans: {}
            }
        },
        methods: {
            loadQueue: function() {
                axios.get('/api/v1/scanqueue').then((response) => {
                  this.scans = response.data;
                });
            }
        },
        mounted() {
            this.loadQueue();
        }
    }
</script>
