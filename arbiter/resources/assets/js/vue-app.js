import Vue from 'vue'
import VueRouter from 'vue-router';
import { routes } from './routes';
import Notifications from 'vue-notification';
import App from './components/App.vue';
import User from './Mixins/User';
import Tick from './Mixins/Tick';
import { VueEditor } from "vue2-editor";
import { ToggleButton } from 'vue-js-toggle-button'
import VueGoogleCharts from 'vue-google-charts'

Vue.mixin(User);
Vue.mixin(Tick);

Vue.use(VueRouter);

const notify = require('vue-notification');
Vue.use(Notifications, { notify });
Vue.use(VueGoogleCharts)

const moment = require('moment')
Vue.use(require('vue-moment'), { moment });

Vue.component('nav-menu', require('./components/Nav.vue'));
Vue.component('search-component', require('./components/Search.vue'));
Vue.component('bookings-component', require('./components/AttackBookings.vue'));
Vue.component('call-component', require('./components/CallMember.vue'));
Vue.component('sortarrow-component', require('./components/SortArrow.vue'));
Vue.component('outgoing-component', require('./components/Outgoing.vue'));
Vue.component('incoming-component', require('./components/Incoming.vue'));
Vue.component('incoming-summary', require('./components/IncomingSummary.vue'));
Vue.component('outgoing-summary', require('./components/OutgoingSummary.vue'));
Vue.component('planet-history', require('./components/PlanetHistory.vue'));
Vue.component('planet-ships', require('./components/PlanetShips.vue'));
Vue.component('planet-movements', require('./components/PlanetMovements.vue'));
Vue.component('planet-list', require('./components/PlanetList.vue'));
Vue.component('galaxy-history', require('./components/GalaxyHistory.vue'));
Vue.component('alliance-history', require('./components/AllianceHistory.vue'));
Vue.component('preloader', require('./components/Preloader.vue'));
Vue.component('pager', require('./components/Pager.vue'));
Vue.component('change-role', require('./components/ChangeRole.vue'));
Vue.component('scan-queue', require('./components/ScanQueue.vue'));
Vue.component('sort-heading', require('./components/SortHeading.vue'));
Vue.component('ToggleButton', ToggleButton);
Vue.component('widget-planet-roid-gain', require('./components/widgets/PlanetRoidGainWidget.vue'));
Vue.component('widget-planet-value-gain', require('./components/widgets/PlanetValueGainWidget.vue'));
Vue.component('widget-planet-score-gain', require('./components/widgets/PlanetScoreGainWidget.vue'));
Vue.component('widget-planet-roid-loss', require('./components/widgets/PlanetRoidLossWidget.vue'));
Vue.component('widget-planet-value-loss', require('./components/widgets/PlanetValueLossWidget.vue'));
Vue.component('widget-planet-score-loss', require('./components/widgets/PlanetScoreLossWidget.vue'));
Vue.component('widget-galaxy-roid-gain', require('./components/widgets/GalaxyRoidGainWidget.vue'));
Vue.component('widget-galaxy-value-gain', require('./components/widgets/GalaxyValueGainWidget.vue'));
Vue.component('widget-galaxy-score-gain', require('./components/widgets/GalaxyScoreGainWidget.vue'));
Vue.component('widget-galaxy-roid-loss', require('./components/widgets/GalaxyRoidLossWidget.vue'));
Vue.component('widget-galaxy-value-loss', require('./components/widgets/GalaxyValueLossWidget.vue'));
Vue.component('widget-galaxy-score-loss', require('./components/widgets/GalaxyScoreLossWidget.vue'));
Vue.component('widget-alliance-roid-gain', require('./components/widgets/AllianceRoidGainWidget.vue'));
Vue.component('widget-alliance-value-gain', require('./components/widgets/AllianceValueGainWidget.vue'));
Vue.component('widget-alliance-score-gain', require('./components/widgets/AllianceScoreGainWidget.vue'));
Vue.component('widget-alliance-roid-loss', require('./components/widgets/AllianceRoidLossWidget.vue'));
Vue.component('widget-alliance-value-loss', require('./components/widgets/AllianceValueLossWidget.vue'));
Vue.component('widget-alliance-score-loss', require('./components/widgets/AllianceScoreLossWidget.vue'));

const router = new VueRouter({
    routes
});

Vue.directive('tooltip', function(el, binding){
    $(el).tooltip({
        title: binding.value,
        placement: binding.arg,
        trigger: 'hover'
    })
})

Vue.filter('ellipsis', function(value, length) {
    if(value.length > length) {
        value = value.slice(0, length-2) + '..';
    }

    return value;
});

Vue.filter('shorten', function (value) {
    if (!value || value == 0) return value;
    var isNegative = false;
    if(Math.sign(value) == '-1') isNegative = true;
    var number = Number(Math.abs(value));
    var maxPlaces = 1;
    var abbr;
    if(number >= 1e12) {
        abbr = 't';
    }
    else if(number >= 1e9) {
        abbr = 'b';
    }
    else if(number >= 1e6) {
        abbr = 'm';
    }
    else if(number >= 1e3) {
        abbr = 'k';
    }
    else {
        abbr = '';
    }

    // set places to false to not round
    var rounded = 0;
    switch(abbr) {
        case 't':
        rounded = number / 1e12;
        break;
        case 'b':
        rounded = number / 1e9;
        break;
        case 'm':
        rounded = number / 1e6;
        break;
        case 'k':
        rounded = number / 1e3;
        break;
        case '':
        rounded = number;
        break;
    }
    var test = new RegExp('\\.\\d{' + (maxPlaces + 1) + ',}$');
    if(test.test(('' + rounded))) {
        rounded = rounded.toFixed(maxPlaces);
    }
    var sign = isNegative ? '-' : '';
    return sign + rounded + abbr;
})

new Vue({
    el: '#app',
    router,
    template: '<App/>',
    components: { App }
});
