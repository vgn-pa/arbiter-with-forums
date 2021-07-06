<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default has-table">
                    <div class="card-header">Outgoing fleets - <router-link :to="{ name: 'galaxy', params: { id: planet.galaxy_id }}"><a>{{ planet.x }}:{{ planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: planet.id }}"><a>{{ planet.z }}</a></router-link></div>

                    <div class="card-body">
                        <outgoing-component v-bind:tick="settings.tick" :outgoing.sync="planet.outgoing"></outgoing-component>
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
                'planet': {},
            };
        },
        mounted() {
            axios.get('/api/v1/planets/' + this.$route.params.id + '?all=1')
            .then((response) => {
                this.planet = response.data;
            });
        },
    }
</script>
