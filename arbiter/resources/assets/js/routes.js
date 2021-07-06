import Home from './components/pages/Home.vue';
import Members from './components/pages/Members.vue';
import Member from './components/pages/Member.vue';
import MemberActivity from './components/pages/MemberActivity.vue';
import Planets from './components/pages/Planets.vue';
import Planet from './components/pages/Planet.vue';
import PlanetIncoming from './components/pages/PlanetIncoming.vue';
import PlanetOutgoing from './components/pages/PlanetOutgoing.vue';
import PlanetHistory from './components/pages/PlanetHistory.vue';
import Galaxies from './components/pages/Galaxies.vue';
import Galaxy from './components/pages/Galaxy.vue';
import GalaxyIncoming from './components/pages/GalaxyIncoming.vue';
import GalaxyOutgoing from './components/pages/GalaxyOutgoing.vue';
import GalaxyHistory from './components/pages/GalaxyHistory.vue';
import Alliances from './components/pages/Alliances.vue';
import Alliance from './components/pages/Alliance.vue';
import AllianceIncoming from './components/pages/AllianceIncoming.vue';
import AllianceOutgoing from './components/pages/AllianceOutgoing.vue';
import AllianceHistory from './components/pages/AllianceHistory.vue';
import Scans from './components/pages/Scans.vue';
import Covops from './components/pages/Covops.vue';
import Attack from './components/pages/Attack.vue';
import AttackBooking from './components/pages/AttackBooking.vue';
import Attacks from './components/pages/Attacks.vue';
import Defence from './components/pages/Defence.vue';
import Misc from './components/pages/Misc.vue';
import Admin from './components/pages/Admin.vue';
import Activity from './components/pages/Activity.vue';
import Account from './components/pages/Account.vue';
import Battlegroups from './components/pages/Battlegroups.vue';
import Battlegroup from './components/pages/Battlegroup.vue';



export const routes = [
    { path: '/', component: Home },
    { path: '/members', component: Members, name: 'members' },
    { path: '/members/:id', component: Member, name: 'member' },
    { path: '/members/:id/activities', component: MemberActivity, name: 'memberActivity' },
    { path: '/planets', component: Planets, name: 'planets' },
    { path: '/planets/:id', component: Planet, name: 'planet' },
    { path: '/planets/:id/history', component: PlanetHistory, name: 'planetHistory' },
    { path: '/planets/:id/incoming', component: PlanetIncoming, name: 'planetIncoming' },
    { path: '/planets/:id/outgoing', component: PlanetOutgoing, name: 'planetOutgoing' },
    { path: '/galaxies', component: Galaxies },
    { path: '/galaxies/:id', component: Galaxy, name: 'galaxy' },
    { path: '/galaxies/:id/incoming', component: GalaxyIncoming, name: 'galaxyIncoming' },
    { path: '/galaxies/:id/outgoing', component: GalaxyOutgoing, name: 'galaxyOutgoing' },
    { path: '/galaxies/:id/history', component: GalaxyHistory, name: 'galaxyHistory' },
    { path: '/alliances', component: Alliances },
    { path: '/alliances/:id', component: Alliance, name: 'alliance' },
    { path: '/alliances/:id/incoming', component: AllianceIncoming, name: 'allianceIncoming' },
    { path: '/alliances/:id/outgoing', component: AllianceOutgoing, name: 'allianceOutgoing' },
    { path: '/alliances/:id/history', component: AllianceHistory, name: 'allianceHistory' },
    { path: '/account', component: Account, name: 'account' },
    { path: '/scans', component: Scans },
    { path: '/covops', component: Covops },
    { path: '/misc', component: Misc },
    { path: '/admin', component: Admin },
    { path: '/admin/activities', component: Activity, name: 'activity' },
    { path: '/attacks', component: Attacks, name: 'attacks', props: true },
    { path: '/attacks/:id', component: Attack, name: 'attack' },
    { path: '/attacks/:id/booking/:booking_id', component: AttackBooking, name: 'attackBooking' },
    { path: '/defence', component: Defence, name: 'defence', props: true },
    { path: '/battlegroups', component: Battlegroups, name: 'battlegroups', props: true },
    { path: '/battlegroups/:id', component: Battlegroup, name: 'battlegroup', props: true },

];
