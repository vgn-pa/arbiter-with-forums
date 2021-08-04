<template>
    <div>
        <div class="nav-header">
            <div class="mr-auto"><router-link class="home mobile" to="/"><i class="fa fa-home"></i></router-link></div>
            <div class="ml-auto">
                <input type="text" placeholder="Search.." v-model="search" v-on:keyup="lookup" class="search"/>
                <span class="setting-buttons collapse-font">
                    <a class="d-lg-none mobile-nav" v-on:click="toggleNav()" v-bind:class="{ selected: nav }"><i class="fas" v-bind:class="{ 'fa-bars': !nav, 'fa-times': nav }"></i></a>
                    <router-link class="nav-link" to="/admin" :class="{'active': subIsActive('/admin')}" v-if="settings.role == 'Admin'"><i class="fa fa-cog"></i></router-link>
                    <router-link to="/account" class="user-name" :class="{'active': subIsActive('/account')}"><i class="fas fa-user icon"></i><span class="label"> {{ settings.user.name }}</span></router-link>
                    <a href="/logout" class="btn-danger"><i class="fas fa-sign-out-alt"></i></a>
                </span>
            </div>
            <nav class="navbar navbar-expand-lg d-lg-none navbar-light navbar-laravel mobile" v-show="nav">
                <ul class="navbar-nav">
                    <li class="nav-item"><router-link class="nav-link" to="/members" :class="{'active': subIsActive('/members')}">Members</router-link></li>
                    <li class="nav-item"><router-link class="nav-link" to="/planets":class="{'active': subIsActive('/planets')}">Planets</router-link></li>
                    <li class="nav-item"><router-link class="nav-link" to="/galaxies":class="{'active': subIsActive('/galaxies')}">Galaxies</router-link></li>
                    <li class="nav-item"><router-link class="nav-link" to="/alliances":class="{'active': subIsActive('/alliances')}">Alliances</router-link></li>
                    <li class="nav-item"><router-link class="nav-link" to="/scans":class="{'active': subIsActive('/scans')}">Scans</router-link></li>
                    <li class="nav-item"><router-link class="nav-link" to="/covops" :class="{'active': subIsActive('/covops')}">CovOps</router-link></li>
                    <li class="nav-item"><router-link class="nav-link" to="/attacks" :class="{'active': subIsActive('/attacks')}">Attacks</router-link></li>
                    <li class="nav-item"><router-link class="nav-link" to="/defence" :class="{'active': subIsActive('/defence')}">Defence</router-link></li>
                    <li class="nav-item"><router-link class="nav-link" to="/battlegroups" :class="{'active': subIsActive('/battlegroups')}">BGs</router-link></li>
                    <li class="nav-item"><router-link class="nav-link" to="/misc" :class="{'active': subIsActive('/misc')}">Misc</router-link></li>
                </ul>
            </nav>
        </div>
        <nav class="sidebar d-none d-lg-block">
            <router-link class="home" to="/"><i class="fa fa-home"></i></router-link>
            <ul class="navbar-nav">
                <li class="nav-item"><router-link class="nav-link" to="/members" :class="{'active': subIsActive('/members')}">Members</router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/planets":class="{'active': subIsActive('/planets')}">Planets</router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/galaxies":class="{'active': subIsActive('/galaxies')}">Galaxies</router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/alliances":class="{'active': subIsActive('/alliances')}">Alliances</router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/scans":class="{'active': subIsActive('/scans')}">Scans <span v-if="settings.scans" class="badge badge-danger">{{ settings.scans }}</span> <span v-if="settings.myScans" class="badge badge-light">{{ settings.myScans }}</span></router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/covops" :class="{'active': subIsActive('/covops')}">CovOps</router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/attacks" :class="{'active': subIsActive('/attacks')}">Attacks</router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/defence" :class="{'active': subIsActive('/defence')}">Defence</router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/battlegroups" :class="{'active': subIsActive('/battlegroups')}">BGs</router-link></li>
                <li class="nav-item"><router-link class="nav-link" to="/misc" :class="{'active': subIsActive('/misc')}">Misc</router-link></li>
            </ul>
            <ul class="small-nav">
                <li class="nav-item"><a class="small nav-link" href="/userscript">userscript</a></li>
            </ul>
        </nav>

    </div>
</template>

<script>
    export default {
        props: ['links', 'settings'],
        data() {
            return {
                nav: false,
                search: ""
            };
        },
        methods: {
            subIsActive(input) {
                const paths = Array.isArray(input) ? input : [input]
                return paths.some(path => {
                    return this.$route.path.indexOf(path) === 0 // current path starts with this path string
                })
            },
            toggleNav() {
                this.nav = !this.nav;
            },
            lookup: _.debounce(function () {
                if(this.search) {
                    axios.get('api/v1/search?search=' + this.search)
                    .then((response) => {
                        var data = response.data;
                        if(data.planet) {
                            this.$router.push('/planets/' + data.planet);
                        }
                        if(data.galaxy) {
                            this.$router.push('/galaxies/' + data.galaxy);
                        }
                        if(data.alliance) {
                            this.$router.push('/alliances/' + data.alliance);
                        }
                        if(data.alliances) {
                            this.$router.push('/alliances');
                        }
                    });
                }
            }, 1000)
        },
        watch:{
            $route (to, from){
                this.nav = false;
            }
        }
    }
</script>
