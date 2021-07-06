<template>
  <div class="search-bar" style="display: flex;">
      <div style="flex: 1; text-align: left;">
          <span v-if="settings.tick"><i class="fas fa-clock" v-tooltip:top="'Current tick'"></i> {{ settings.tick }}</span>
      </div>
      <div v-if="attacks.length" style="flex: 1; text-align: center;">
          <i class="fas fa-crosshairs" style="margin-right: 5px;" v-tooltip:top="'Open attacks'"></i><span class="attack" v-for="attack in attacks"><router-link :to="{ name: 'attack', params: { id: attack.attack_id }}"><button class="btn btn-danger btn-sm">#{{ attack.id }}</button></router-link></span>
      </div>
      <div style="flex: 1; text-align: right;">
          <span v-if="settings.user.planet.galaxy_id">
              <i class="fas fa-globe-europe" v-tooltip:top="'My planet'"></i>
              <div class="double-button">
                  <router-link :to="{ name: 'galaxy', params: { id: settings.user.planet.galaxy_id }}" class="first">
                      {{ settings.user.planet.x }}:{{ settings.user.planet.y }}
                  </router-link><router-link :to="{ name: 'planet', params: { id: settings.user.planet.id }}" class="second">
                      {{ settings.user.planet.z }}
                  </router-link>
              </div>
          </span>
      </div>
  </div>
</template>

<script>
    export default {
        props: ['settings'],
        data() {
            return {
                search: "",
                attacks: {}
            };
        },
        methods: {
            lookup: _.debounce(function () {
                if(this.search) {
                    axios.get('/api/v1/search?search=' + this.search)
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
            }, 1000),
            loadAttacks: function() {
                axios.get('/api/v1/attacks')
                .then((response) => {
                    this.attacks = response.data;
                });
            },
        },
        mounted() {
            this.loadAttacks();
        }
    }
</script>
