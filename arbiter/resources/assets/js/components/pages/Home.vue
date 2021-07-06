<template>

    <main class="my-3">
        <div class="row justify-content-center my-2">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Overview</div>

                    <div class="card-body">
                        <div v-if="settings"><div v-html="settings.overview"></div></div>
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
                stats: {}
            };
        },
        mounted() {
            axios.get('/api/v1/stats')
            .then((response) => {
                this.stats = response.data;
            });
            this.$parent.links = [];
        }
    }
</script>
