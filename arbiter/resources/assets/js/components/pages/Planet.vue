<template>
    <main class="my-3">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="card card-default mb-3 has-table">
                    <div class="card-header">
                        <i class="fas fa-shield-alt" v-if="planet.is_protected" style="margin-right: 5px;" v-tooltip:top="'In protection for ' + (24-planet.age) + ' more tick(s)'"></i>{{ planet.ruler_name }} of {{ planet.planet_name }} (<router-link :to="{ name: 'galaxy', params: { id: planet.galaxy_id }}"><a>{{ planet.x }}:{{ planet.y }}</a></router-link> <router-link :to="{ name: 'planet', params: { id: planet.id }}"><a>{{ planet.z }}</a></router-link>) <span class="race" v-bind:class="planet.race">{{ planet.race }}</span> - {{ planet.nick }} <span v-if="planet.nick && planet.alliance">/ </span><router-link v-if="planet.alliance" :to="{ name: 'alliance', params: { id: planet.alliance.id }}"><a>{{ planet.alliance.name }}</a></router-link>
                        <span class="mb-0 scans" style="font-style: italic" v-if="planet.latest_p || planet.latest_d || planet.latest_u || planet.latest_n || planet.latest_j || planet.latest_a">
                            - <template v-if="planet.latest_p">
                              <a v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+planet.latest_p.scan.pa_id" target="_blank" v-bind:class="{ new: planet.latest_p.scan.tick >= (settings.tick-1), mid: (planet.latest_p.scan.tick >= (settings.tick-12) && planet.latest_p.scan.tick < (settings.tick-1)), old: planet.latest_p.scan.tick < (settings.tick-12) }">P</a>
                            </template>
                            <template v-if="planet.latest_d">
                              <a v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+planet.latest_d.scan.pa_id" target="_blank" v-bind:class="{ new: planet.latest_d.scan.tick >= (settings.tick-1), mid: (planet.latest_d.scan.tick >= (settings.tick-12) && planet.latest_d.scan.tick < (settings.tick-1)), old: planet.latest_d.scan.tick < (settings.tick-12) }">D</a>
                            </template>
                            <template v-if="planet.latest_u">
                              <a v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+planet.latest_u.pa_id" target="_blank" v-bind:class="{ new: planet.latest_u.tick >= (settings.tick-1), mid: (planet.latest_u.tick >= (settings.tick-12) && planet.latest_u.tick < (settings.tick-1)), old: planet.latest_u.tick < (settings.tick-12) }">U</a>
                            </template>
                            <template v-if="planet.latest_n">
                              <a v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+planet.latest_n.scan.pa_id" target="_blank" v-bind:class="{ new: planet.latest_n.scan.tick >= (settings.tick-1), mid: (planet.latest_n.scan.tick >= (settings.tick-12) && planet.latest_n.scan.tick < (settings.tick-1)), old: planet.latest_n.scan.tick < (settings.tick-12) }">N</a>
                            </template>
                            <template v-if="planet.latest_j">
                              <a v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+planet.latest_j.scan.pa_id" target="_blank" v-bind:class="{ new: planet.latest_j.scan.tick >= (settings.tick-1), mid: (planet.latest_j.scan.tick >= (settings.tick-12) && planet.latest_j.scan.tick < (settings.tick-1)), old: planet.latest_j.scan.tick < (settings.tick-12) }">J</a>
                            </template>
                            <template v-if="planet.latest_a">
                              <a v-bind:href="'https://game.planetarion.com/showscan.pl?scan_id='+planet.latest_a.pa_id" target="_blank" v-bind:class="{ new: planet.latest_a.tick >= (settings.tick-1), mid: (planet.latest_a.tick >= (settings.tick-12) && planet.latest_a.tick < (settings.tick-1)), old: planet.latest_a.tick < (settings.tick-12) }">A</a>
                            </template>
                        </span>
                    </div>

                    <div class="card-body">
                        <preloader :loading.sync="loading"></preloader>
                        <table id="members" class="table table-striped table-bordered" style="width:100%" v-if="!loading">
                          <thead>
                              <tr>
                                <th colspan="2"></th>
                                <th>Rank</th>
                                <th class="text-center" colspan="2">Today's growth</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <td class="text-right">Size</td>
                                  <td class="text-right"><span v-tooltip:top="planet.size.toLocaleString()">{{ planet.size | shorten }}</span></td>
                                  <td class="text-right">{{ planet.rank_size }} <span v-if="planet.growth_rank_size > 0" v-tooltip:top="'up ' + planet.growth_rank_size + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_size < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_size) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_size == 0"><i class="fa fa-caret-right"></i></span></td>
                                  <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_size == 0.0, green: planet.growth_percentage_size > 0.0, red: planet.growth_percentage_size < 0.0 }"><span v-tooltip:top="planet.growth_size.toLocaleString()">{{ planet.growth_size | shorten }}</span></td>
                                  <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_size == 0.0, green: planet.growth_percentage_size > 0.0, red: planet.growth_percentage_size < 0.0 }">{{ planet.growth_percentage_size | shorten }}%</td>
                              </tr>
                              <tr>
                                  <td class="text-right">Value</td>
                                  <td class="text-right" v-bind:class="{ basher: planet.basher }"><span v-tooltip:top="planet.value.toLocaleString()">{{ planet.value | shorten }}</span></td>
                                  <td class="text-right">{{ planet.rank_value }} <span v-if="planet.growth_rank_value > 0" v-tooltip:top="'up ' + planet.growth_rank_value + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_value < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_value) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_value == 0"><i class="fa fa-caret-right"></i></span></td>
                                  <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_value == 0.0, green: planet.growth_percentage_value > 0.0, red: planet.growth_percentage_value < 0.0 }"><span v-tooltip:top="planet.growth_value.toLocaleString()">{{ planet.growth_value | shorten }}</span></td>
                                  <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_value == 0.0, green: planet.growth_percentage_value > 0.0, red: planet.growth_percentage_value < 0.0 }">{{ planet.growth_percentage_value | shorten }}%</td>
                              </tr>
                              <tr>
                                  <td class="text-right">Score</td>
                                  <td class="text-right" v-bind:class="{ basher: planet.basher }"><span v-tooltip:top="planet.score.toLocaleString()">{{ planet.score | shorten }}</span></td>
                                  <td class="text-right">{{ planet.rank_score }} <span v-if="planet.growth_rank_score > 0" v-tooltip:top="'up ' + planet.growth_rank_score + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_score < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_score) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_score == 0"><i class="fa fa-caret-right"></i></span></td>
                                  <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_score == 0.0, green: planet.growth_percentage_score > 0.0, red: planet.growth_percentage_score < 0.0 }"><span v-tooltip:top="planet.growth_score.toLocaleString()">{{ planet.growth_score | shorten }}</span></td>
                                  <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_score == 0.0, green: planet.growth_percentage_score > 0.0, red: planet.growth_percentage_score < 0.0 }">{{ planet.growth_percentage_score | shorten }}%</td>
                              </tr>
                              <tr>
                                  <td class="text-right">Xp</td>
                                  <td class="text-right"><span v-tooltip:top="planet.xp.toLocaleString()">{{ planet.xp | shorten }}</span></td>
                                  <td class="text-right">{{ planet.rank_xp }} <span v-if="planet.growth_rank_xp > 0" v-tooltip:top="'up ' + planet.growth_rank_xp + ' places'"><i class="fa fa-caret-up"></i></span><span v-if="planet.growth_rank_xp < 0" v-tooltip:top="'down ' + Math.abs(planet.growth_rank_xp) + ' places'"><i class="fa fa-caret-down"></i></span><span v-if="planet.growth_rank_xp == 0"><i class="fa fa-caret-right"></i></span></td>
                                  <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_xp == 0.0, green: planet.growth_percentage_xp > 0.0, red: planet.growth_percentage_xp < 0.0 }"><span v-tooltip:top="planet.growth_xp.toLocaleString()">{{ planet.growth_xp | shorten }}</span></td>
                                  <td class="text-right" v-bind:class="{ yellow: planet.growth_percentage_xp == 0.0, green: planet.growth_percentage_xp > 0.0, red: planet.growth_percentage_xp < 0.0 }">{{ planet.growth_percentage_xp | shorten }}%</td>
                              </tr>
                              <tr>
                                  <td class="text-right">Round roids</td>
                                  <td class="text-right"><span v-tooltip:top="planet.round_roids.toLocaleString()">{{ planet.round_roids | shorten }}</span></td>
                                  <td class="text-right">{{ planet.rank_round_roids }} <span v-if="planet.rank_round_roids > planet.day_rank_round_roids"><i class="fa fa-caret-up"></i></span><span v-if="planet.rank_round_roids < planet.day_rank_round_roids"><i class="fa fa-caret-down"></i></span><span v-if="planet.rank_round_roids == planet.day_rank_round_roids"><i class="fa fa-caret-right"></i></span></td>
                                  <td colspan="2"></td>
                              </tr>
                              <tr>
                                  <td class="text-right">Lost roids</td>
                                  <td class="text-right"><span v-tooltip:top="planet.lost_roids.toLocaleString()">{{ planet.lost_roids | shorten }}</span></td>
                                  <td class="text-right">{{ planet.rank_lost_roids }} <span v-if="planet.rank_lost_roids > planet.day_rank_lost_roids"><i class="fa fa-caret-up"></i></span><span v-if="planet.rank_lost_roids < planet.day_rank_lost_roids"><i class="fa fa-caret-down"></i></span><span v-if="planet.rank_lost_roids == planet.day_rank_lost_roids"><i class="fa fa-caret-right"></i></span></td>
                                  <td colspan="2"></td>
                              </tr>
                              <tr class="totals">
                                  <td></td>
                                  <td class="text-right">Ticks roiding</td>
                                  <td class="text-right">{{ planet.ticks_roiding }}</td>
                                  <td class="text-right">Tick roids</td>
                                  <td class="text-right">{{ planet.tick_roids.toLocaleString() }}</td>
                              </tr>
                              <tr>
                                  <td></td>
                                  <td class="text-right">Ticks roided</td>
                                  <td class="text-right">{{ planet.ticks_roided }}</td>
                                  <td class="text-right">Av. roids</td>
                                  <td class="text-right">{{ parseInt(planet.tick_roids / planet.tick).toLocaleString() }}</td>
                              </tr>
                          </tbody>
                        </table>
                    </div>
                </div>

                <div class="card card-default mb-3 has-table" v-if="planet.income.length">
                    <div class="card-header">Income</div>

                    <div class="card-body">
                        <table class="table table-striped table-bordered" style="width:100%;table-layout: auto;">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Tax %</th>
                                    <th>Corporatism</th>
                                    <th>Democracy</th>
                                    <th>Nationalism</th>
                                    <th>Socialism</th>
                                    <th>Totalitarianism</th>
                                    <th>Anarchy</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td rowspan="6" class="text-center align-middle">Metal</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">{{ planet.income.metal.Corporatism[0] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Democracy[0] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Nationalism[0] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Socialism[0] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Totalitarianism[0] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Anarchy[0] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">1</td>
                                    <td class="text-right">{{ planet.income.metal.Corporatism[1] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Democracy[1] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Nationalism[1] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Socialism[1] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Totalitarianism[1] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Anarchy[1] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">2</td>
                                    <td class="text-right">{{ planet.income.metal.Corporatism[2] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Democracy[2] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Nationalism[2] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Socialism[2] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Totalitarianism[2] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Anarchy[2] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">3</td>
                                    <td class="text-right">{{ planet.income.metal.Corporatism[3] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Democracy[3] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Nationalism[3] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Socialism[3] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Totalitarianism[3] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Anarchy[3] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">4</td>
                                    <td class="text-right">{{ planet.income.metal.Corporatism[4] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Democracy[4] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Nationalism[4] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Socialism[4] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Totalitarianism[4] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Anarchy[4] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">5</td>
                                    <td class="text-right">{{ planet.income.metal.Corporatism[5] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Democracy[5] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Nationalism[5] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Socialism[5] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Totalitarianism[5] }}</td>
                                    <td class="text-right">{{ planet.income.metal.Anarchy[5] }}</td>
                                </tr>
                                <tr>
                                    <td rowspan="6" class="text-center align-middle">Crystal</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">{{ planet.income.crystal.Corporatism[0] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Democracy[0] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Nationalism[0] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Socialism[0] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Totalitarianism[0] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Anarchy[0] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">1</td>
                                    <td class="text-right">{{ planet.income.crystal.Corporatism[1] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Democracy[1] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Nationalism[1] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Socialism[1] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Totalitarianism[1] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Anarchy[1] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">2</td>
                                    <td class="text-right">{{ planet.income.crystal.Corporatism[2] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Democracy[2] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Nationalism[2] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Socialism[2] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Totalitarianism[2] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Anarchy[2] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">3</td>
                                    <td class="text-right">{{ planet.income.crystal.Corporatism[3] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Democracy[3] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Nationalism[3] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Socialism[3] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Totalitarianism[3] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Anarchy[3] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">4</td>
                                    <td class="text-right">{{ planet.income.crystal.Corporatism[4] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Democracy[4] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Nationalism[4] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Socialism[4] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Totalitarianism[4] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Anarchy[4] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">5</td>
                                    <td class="text-right">{{ planet.income.crystal.Corporatism[5] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Democracy[5] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Nationalism[5] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Socialism[5] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Totalitarianism[5] }}</td>
                                    <td class="text-right">{{ planet.income.crystal.Anarchy[5] }}</td>
                                </tr>
                                <tr>
                                    <td rowspan="6" class="text-center align-middle">Eonium</td>
                                    <td class="text-right">0</td>
                                    <td class="text-right">{{ planet.income.eonium.Corporatism[0] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Democracy[0] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Nationalism[0] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Socialism[0] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Totalitarianism[0] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Anarchy[0] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">1</td>
                                    <td class="text-right">{{ planet.income.eonium.Corporatism[1] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Democracy[1] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Nationalism[1] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Socialism[1] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Totalitarianism[1] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Anarchy[1] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">2</td>
                                    <td class="text-right">{{ planet.income.eonium.Corporatism[2] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Democracy[2] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Nationalism[2] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Socialism[2] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Totalitarianism[2] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Anarchy[2] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">3</td>
                                    <td class="text-right">{{ planet.income.eonium.Corporatism[3] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Democracy[3] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Nationalism[3] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Socialism[3] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Totalitarianism[3] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Anarchy[3] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">4</td>
                                    <td class="text-right">{{ planet.income.eonium.Corporatism[4] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Democracy[4] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Nationalism[4] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Socialism[4] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Totalitarianism[4] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Anarchy[4] }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right">5</td>
                                    <td class="text-right">{{ planet.income.eonium.Corporatism[5] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Democracy[5] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Nationalism[5] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Socialism[5] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Totalitarianism[5] }}</td>
                                    <td class="text-right">{{ planet.income.eonium.Anarchy[5] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card card-default mb-3 has-table" v-if="ranks.length">
                    <div class="card-header">
                        Rank History
                    </div>

                    <div class="card-body embed-responsive embed-responsive-16by9">
                        <GChart
                            class="embed-responsive-item"
                            type="LineChart"
                            :data="ranks"
                            :options="rankChartOptions"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <planet-ships :id.sync="planet.id"></planet-ships>
            </div>

            <div class="col-md-6">

                <div class="card card-default mb-3" v-bind:class="{ 'has-table': planet.attackedBy || planet.attacked || planet.defendedBy || planet.defended }">
                    <div class="card-header">Intel</div>

                    <div class="card-body">
                        <table class="table table-striped table-bordered" style="width:100%;table-layout: auto;" v-if="planet.attackedBy || planet.attacked || planet.defendedBy || planet.defended">
                            <tbody>
                                <tr>
                                    <td>Attacked</td>
                                    <td class="red">{{ planet.attacked }}</td>
                                    <td>Defended</td>
                                    <td class="green">{{ planet.defended }}</td>
                                </tr>
                                <tr>
                                    <td>Attacked By</td>
                                    <td class="red">{{ planet.attackedBy }}</td>
                                    <td>Defended By</td>
                                    <td class="green">{{ planet.defendedBy }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <p v-if="!planet.attackedBy && !planet.attacked && !planet.defendedBy && !planet.defended" class="no-data">No fleets tracked to or from this planet</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <outgoing-summary :type="'planet'" :id.sync="planet.id"></outgoing-summary>
            </div>
            <div class="col-md-6">
                <incoming-summary :type="'planet'" :id.sync="planet.id"></incoming-summary>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <planet-history :id.sync="planet.id"></planet-history>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <planet-movements :planetId.sync="planet.id"></planet-movements>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
        props: ['settings'],
        data() {
            return {
                planet: {
                    id: this.$route.params.id,
                    income: {},
                    ships: {},
                    outgoing: { data: {} },
                    incoming: { data: {} },
                    galaxy_id: 0
                },
                type: 'planet',
                tick: { tick: 0 },
                loading: true,
                ranks: {},
                rankChartOptions: {
                    backgroundColor: {
                      stroke: '#424242',
                      fill: '#424242',
                      strokeWidth: '0'
                    },
                    height: '100%',
                    hAxis: {
                        textStyle: {
                            color: '#fff',
                        },
                        minValue: 0,
                    },
                    vAxis: {
                        textStyle: {
                            color: '#fff',
                        },
                        direction: -1,
                        minValue: 0
                    },
                    legend: {
                        textStyle: {
                            color: '#fff'
                        }
                    }
                }
            };
        },
        methods: {
            loadPlanet: function() {
                this.loading = true;
                axios.get('api/v1/planets/' + this.$route.params.id)
                .then((response) => {
                    this.planet = response.data;
                    this.loading = false;
                });
            },
            loadRanks: function() {
                axios.get('api/v1/planets/' + this.$route.params.id + '/ranks')
                .then((response) => {
                    this.ranks = response.data;
                });
            }
        },
        watch: {
           '$route': 'loadPlanet'
        },
        mounted() {
            this.planet.id = this.$route.params.id;
            this.loadPlanet();
            this.loadRanks();
        },
    }
</script>
