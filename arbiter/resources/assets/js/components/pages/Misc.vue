<template>
    <main class="my-3">
        <div class="row justify-content-center">

            <div class="col-md-6">
                <div class="card card-default">
                    <div class="card-header">
                        Eff<br/>
                        <small>Calculates the efficiency of the specified number of ships</small>
                    </div>

                    <div class="card-body">
                        <form>
                            <input type="text" v-model="effAmount" v-on:keyup="effSubmit" placeholder="Amount"></input>
                            <select v-model="effShip" v-on:change="effSubmit">
                                <optgroup class="race-bg" v-for="(race, index) in ships" v-bind:label="index">
                                    <option v-for="ship in race" v-bind:value="ship.id">
                                    {{ ship.name }} [{{ ship.class.substring(0,2) }}] {{ (ship.t1 ? ship.t1.substring(0,2) : "" )}}{{ (ship.t2 ? "/" + ship.t2.substring(0,2) : "" )}}{{ (ship.t3 ? "/" + ship.t3.substring(0,2) : "" )}}
                                    </option>
                                </optgroup>
                            </select>
                            <div v-if="effResult">
                                <br/>{{ effResult }}
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-default">
                    <div class="card-header">
                        Stop<br/>
                        <small>Calculates the required defence to the specified number of ships</small>
                    </div>

                    <div class="card-body">
                        <form>
                            <input type="text" v-model="stopAmount" v-on:keyup="stopSubmit" placeholder="Amount"></input>
                            <select v-model="stopShip" v-on:change="stopSubmit">
                                <optgroup class="race-bg" v-for="(race, index) in ships" v-bind:label="index">
                                    <option v-for="ship in race" v-bind:value="ship.id">
                                    {{ ship.name }} [{{ ship.class.substring(0,2) }}] {{ (ship.t1 ? ship.t1.substring(0,2) : "" )}}{{ (ship.t2 ? "/" + ship.t2.substring(0,2) : "" )}}{{ (ship.t3 ? "/" + ship.t3.substring(0,2) : "" )}}
                                    </option>
                                </optgroup>
                            </select>
                            <div v-if="stopResult">
                                <br/>{{ stopResult }}
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <div class="row justify-content-center my-3">

          <div class="col-md-6">
              <div class="card card-default">
                  <div class="card-header">
                      Cost<br/>
                      <small>Calculates the cost of producing the specified number of ships</small>
                  </div>

                  <div class="card-body">
                      <form>
                          <input type="number" v-model="costAmount" v-on:keyup="costSubmit" placeholder="Amount"></input>
                          <select v-model="costShip" v-on:change="costSubmit">
                                <optgroup class="race-bg" v-for="(race, index) in ships" v-bind:label="index">
                                    <option v-for="ship in race" v-bind:value="ship.id">
                                    {{ ship.name }} [{{ ship.class.substring(0,2) }}] {{ (ship.t1 ? ship.t1.substring(0,2) : "" )}}{{ (ship.t2 ? "/" + ship.t2.substring(0,2) : "" )}}{{ (ship.t3 ? "/" + ship.t3.substring(0,2) : "" )}}
                                    </option>
                                </optgroup>
                          </select>
                          <div v-if="costResult">
                              <hr/>
                              <p style="white-space: pre-wrap">{{ costResult }}</p>
                          </div>
                      </form>
                  </div>
              </div>
          </div>

            <div class="col-md-6">
                <div class="card card-default">
                    <div class="card-header">
                        Roidcost<br/>
                        <small>Calculate how long it will take to repay a value loss capping roids</small>
                    </div>

                    <div class="card-body">
                        <form>
                            <input type="number" v-model="roidcostRoids" v-on:keyup="roidcostSubmit" placeholder="Roids"></input>
                            <input type="number" v-model="roidcostCost" v-on:keyup="roidcostSubmit" placeholder="Value"></input>
                            <input type="number" v-model="roidcostBonus" v-on:keyup="roidcostSubmit" placeholder="Bonus"></input>

                            <div v-if="roidcostResult">
                                <hr/>
                                <p>{{ roidcostResult }}</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <div class="row justify-content-center my-3">

            <div class="col-md-6">
                <div class="card card-default">
                    <div class="card-header">
                        Lazy Calc<br/>
                        <small>Creates a calc using stored scans</small>
                    </div>

                    <div class="card-body">
                        <form>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="coords">Target</label><br>
                                    <input type="number" id="coord_x" placeholder="x" size="2" v-model.number="lazyX" style="width: 40px" v-on:change="lazySubmit">:
                                    <input type="number" id="coord_y" placeholder="y" size="2" v-model.number="lazyY" style="width: 40px" v-on:change="lazySubmit">:
                                    <input type="number" id="coord_z" placeholder="z" size="2" v-model.number="lazyZ" style="width: 40px" v-on:change="lazySubmit">
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="defenders">Defenders (eg: x:y:z x:y:z)</label><br>
                                    <textarea id="defenders" v-model="lazyDef" v-on:keyup="lazySubmit" style="margin-right: 15px"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="attackers">Attackers (eg: x:y:z x:y:z)</label><br>
                                    <textarea id="attackers" v-model="lazyAtt" v-on:keyup="lazySubmit"></textarea>
                                </div>
                            </div>

                            <div v-if="lazyResult">
                                <hr/>
                                <a target="_BLANK" v-bind:href="lazyResult">Calc</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-default">
                    <div class="card-header">
                        Afford<br/>
                        <small>Calculates the number of a certain ship the planet can produce based on the most recent planet scan</small>
                    </div>

                    <div class="card-body">
                        <form>
                            <label for="coords">Co-ords</label><br>
                            <input type="number" id="coord_x" placeholder="x" size="2" v-model.number="affordX" style="width: 40px">:
                            <input type="number" id="coord_y" placeholder="y" size="2" v-model.number="affordY" style="width: 40px">:
                            <input type="number" id="coord_z" placeholder="z" size="2" v-model.number="affordZ" style="width: 40px">
                            <select v-model="affordShip" v-on:change="affordSubmit">
                                <optgroup class="race-bg" v-for="(race, index) in ships" v-bind:label="index">
                                    <option v-for="ship in race" v-bind:value="ship.id">
                                    {{ ship.name }} [{{ ship.class.substring(0,2) }}] {{ (ship.t1 ? ship.t1.substring(0,2) : "" )}}{{ (ship.t2 ? "/" + ship.t2.substring(0,2) : "" )}}{{ (ship.t3 ? "/" + ship.t3.substring(0,2) : "" )}}
                                    </option>
                                </optgroup>
                            </select>
                            <div v-if="affordResult">
                                <hr/>
                                <p style="white-space: pre-wrap">{{ affordResult }}</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </main>
</template>

<script>
    export default {
        data() {
            return {
                ships: {},
                effAmount: "",
                effShip: "",
                effResult: "",
                stopAmount: "",
                stopShip: "",
                stopResult: "",
                costAmount: "",
                costShip: "",
                costResult: "",
                roidcostRoids: "",
                roidcostCost: "",
                roidcostBonus: "",
                roidcostResult: "",
                affordX: "",
                affordY: "",
                affordZ: "",
                affordShip: "",
                affordResult: "",
                lazyX: "",
                lazyY: "",
                lazyZ: "",
                lazyDef: "",
                lazyAtt: "",
                lazyResult: ""
            };
        },
        methods: {
            effSubmit() {
                if(this.effAmount && this.effShip) {
                    axios.get('api/v1/misc/eff?ship=' + this.effShip + '&amount=' + this.effAmount)
                    .then((response) => {
                        this.effResult = response.data;
                    });
                }
            },
            stopSubmit() {
                if(this.stopAmount && this.stopShip) {
                    axios.get('api/v1/misc/stop?ship=' + this.stopShip + '&amount=' + this.stopAmount)
                    .then((response) => {
                        this.stopResult = response.data;
                    });
                }
            },
            costSubmit() {
                if(this.costAmount && this.costShip) {
                    axios.get('api/v1/misc/cost', {
                        params: {
                            ship: this.costShip,
                            amount: this.costAmount
                        }
                    })
                    .then((response) => {
                        this.costResult = response.data;
                    });
                }
            },
            roidcostSubmit: _.debounce(function () {
                if(this.roidcostRoids && this.roidcostCost) {
                    var roidcostBonus = (this.roidcostBonus) ? this.roidcostBonus : 0;
                    axios.get('api/v1/misc/roidcost?roids=' + this.roidcostRoids + '&cost=' + this.roidcostCost + '&bonus=' + roidcostBonus)
                    .then((response) => {
                        this.roidcostResult = response.data;
                    });
                }
            }, 500),
            affordSubmit() {
                this.affordResult = "";
                if(this.affordX && this.affordY && this.affordZ && this.affordShip) {
                    axios.get('api/v1/misc/afford', {
                        params: {
                            x: this.affordX,
                            y: this.affordY,
                            z: this.affordZ,
                            ship: this.affordShip
                        }
                    })
                    .then((response) => {
                        this.affordResult = response.data;
                    });
                }
            },
            lazySubmit: _.debounce(function () {
                this.lazyResult = "";
                if(this.lazyX && this.lazyY && this.lazyZ) {
                    axios.get('api/v1/misc/calc', {
                        params: {
                            x: this.lazyX,
                            y: this.lazyY,
                            z: this.lazyZ,
                            def: this.lazyDef,
                            att: this.lazyAtt
                        }
                    })
                    .then((response) => {
                        this.lazyResult = response.data;
                    });
                }
            }, 500),
        },
        mounted() {
            axios.get('api/v1/ships')
            .then((response) => {
                this.ships = response.data;
            });
        }
    }
</script>
