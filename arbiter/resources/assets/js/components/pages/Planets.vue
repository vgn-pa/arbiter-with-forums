<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-4 my-2">
                <widget-planet-roid-gain></widget-planet-roid-gain>
            </div>
            <div class="col-md-12 col-lg-4 my-2">
                <widget-planet-value-gain></widget-planet-value-gain>
            </div>
            <div class="col-md-12 col-lg-4 my-2">
                <widget-planet-score-gain></widget-planet-score-gain>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-4 my-2">
                <widget-planet-roid-loss></widget-planet-roid-loss>
            </div>
            <div class="col-md-12 col-lg-4 my-2">
                <widget-planet-value-loss></widget-planet-value-loss>
            </div>
            <div class="col-md-12 col-lg-4 my-2">
                <widget-planet-score-loss></widget-planet-score-loss>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <planet-list :perPage="50" :scans="false"></planet-list>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <planet-movements></planet-movements>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
    }
</script>
