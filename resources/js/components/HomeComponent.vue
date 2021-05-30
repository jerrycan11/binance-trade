<template>
    <el-container>
        <el-row>
            <el-col :span="5">
                <el-card>
                    <template #header>
                        Diff Records
                    </template>
                    <el-table :data="arr_buy_position" size="mini">
                        <el-table-column prop="n" label="Name" width="100px"></el-table-column>
                        <el-table-column label="Diff">
                            <template #default="scope">
                                <el-tag v-if="scope.row.d<0" type="danger" size="mini">
                                    {{ scope.row.d }}
                                </el-tag>
                                <el-tag v-else type="success" size="mini">
                                    {{ scope.row.d }}
                                </el-tag>
                            </template>
                        </el-table-column>
                        <el-table-column prop="c" label="Current"></el-table-column>
                    </el-table>
                    <el-tag size="mini">
                        Diff Total: {{ Math.round(arr_current_diff * 1000) / 1000 }}
                    </el-tag>
                    <el-tag size="mini">
                        Current Total: {{ Math.round(arr_current_positon * 1000) / 1000 }}
                    </el-tag>
                </el-card>
                <el-card>
                    <template #header>
                        Buy Records
                    </template>
                    <el-table :data="arr_buy_records" size="mini">
                        <el-table-column prop="name" label="Name" width="100px"></el-table-column>
                        <el-table-column prop="price" label="Price"></el-table-column>
                        <el-table-column prop="qty" label="Qty"></el-table-column>
                    </el-table>
                </el-card>
            </el-col>
            <el-col :span="11">
                <el-card size="mini">
                    <template #header>
                        Current Prices
                        <el-tag>
                            USDT Balance: {{ usdtBalance }}
                        </el-tag>
                        <el-tag>
                            BTCUSDT:
                            <el-tag type="success" effect="dark" size="mini">
                                {{ arr_market_direction['high'] }}
                            </el-tag>
                            <el-tag v-if="arr_market_direction['dir'] === 'DOWN'" type="danger" effect="dark"
                                    size="mini">
                                {{ arr_market_direction['price'] }}
                            </el-tag>
                            <el-tag v-else type="success" effect="dark" size="mini">
                                {{ arr_market_direction['price'] }}
                            </el-tag>
                            <el-tag type="danger" effect="dark" size="mini">
                                {{ arr_market_direction['low'] }}
                            </el-tag>
                        </el-tag>
                        <el-button @click="stopCurrentData" size="mini" type="info">
                            {{ stopButtonCurrentRecord }}
                        </el-button>
                        <el-descriptions>
                            <el-descriptions-item label="Profit Margin" size="mini">
                                <el-input-number @input="changeProfitMargin" v-model="profit_margin"
                                                 size="mini"></el-input-number>
                            </el-descriptions-item>
                            <el-descriptions-item label="Buy At lower" size="mini">
                                <el-input-number @input="changeBuyLowerAt" v-model="buy_low_at"
                                                 size="mini"></el-input-number>
                            </el-descriptions-item>
                            <el-descriptions-item label="Spend Per Order" size="mini">
                                <el-input-number @input="changeSpendPerOrder" v-model="spend_per_order"
                                                 size="mini"></el-input-number>
                            </el-descriptions-item>
                            <el-descriptions-item label="Coin" size="mini">
                                <el-row>
                                    <el-col :span="5">
                                        <el-input v-model="input_coin" placeholder="coin" size="mini"></el-input>
                                    </el-col>
                                    <el-col :span="2">
                                        <el-button @click="addCoin" type="info" size="mini">Add</el-button>
                                    </el-col>
                                    <el-col :span="3">
                                        <el-button @click="removeCoin" type="info" size="mini">Remove</el-button>
                                    </el-col>
                                </el-row>
                            </el-descriptions-item>
                        </el-descriptions>
                    </template>
                    <el-table :data="arr_coins" size="mini" height="663px">
                        <el-table-column label="Name" width="120px">
                            <template #default="scope">
                                {{ scope.row }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Price" width="130px">
                            <template #default="scope">
                                <el-tag v-if="arr_prices[scope.row].price<arr_prices[scope.row].buy" type="danger"
                                        size="mini">
                                    {{ arr_prices[scope.row].price }}
                                </el-tag>
                                <el-tag v-else type="success" size="mini">
                                    {{ arr_prices[scope.row].price }}
                                </el-tag>
                            </template>
                        </el-table-column>
                        <el-table-column label="Buy At" width="130px">
                            <template #default="scope">
                                {{ arr_prices[scope.row].buy }}
                            </template>
                        </el-table-column>
                        <el-table-column label="24High" width="130px">
                            <template #default="scope">
                                {{ arr_prices[scope.row].high }}
                            </template>
                        </el-table-column>
                        <el-table-column label="24Low" width="130px">
                            <template #default="scope">
                                {{ arr_prices[scope.row].low }}
                            </template>
                        </el-table-column>
                    </el-table>
                </el-card>
            </el-col>
            <el-col :span="8">
                <el-card>
                    <template #header>
                        Trade Records
                    </template>
                    <el-table :data="arr_trade_records" size="mini" height="800px">
                        <el-table-column prop="ticker" label="Name"></el-table-column>
                        <el-table-column label="Type">
                            <template #default="scope">
                                <el-tag v-if="scope.row.buy_sell === 'SELL'" type="danger" effect="dark"
                                        size="mini">
                                    {{ scope.row.buy_sell }}
                                </el-tag>
                                <el-tag v-else type="success" effect="dark" size="mini">
                                    {{ scope.row.buy_sell }}
                                </el-tag>
                            </template>
                        </el-table-column>
                        <el-table-column prop="amount" label="Qty"></el-table-column>
                        <el-table-column prop="price" label="Price"></el-table-column>
                    </el-table>
                </el-card>
            </el-col>
        </el-row>
    </el-container>
</template>

<script>
import binance_api from "../binance_api";
import axios_api from "../axios_api";

export default {
    name: "HomeComponent",
    data() {
        return {
            arr_prices: [],
            profit_margin: 4,
            buy_low_at: 5,
            spend_per_order: 40,
            arr_buy_records: [],
            arr_trade_records: [],
            arr_buy_position: [],
            arr_current_price: [],
            arr_market_direction: [],
            arr_current_diff: 0,
            arr_current_positon: 0,
            input_coin: null,
            sellMark: true,
            usdtBalance: 0,
            interval: setInterval(() => {
                this.checkForSell()
            }, 1000),
            stopButtonCurrentRecord: 'Ping',
            arr_coins: []
        }
    },
    methods: {
        checkForBuy() {
            let that = this;
            let checkBuy = [];
            _.forEach(this.arr_coins, function (coin) {
                checkBuy[coin] = true;
                _.forEach(that.arr_buy_records, function (item) {
                    if (coin === item.name) {
                        checkBuy[coin] = false;
                    }
                });
            });
            if (this.arr_market_direction['dir'] === "UP") {
                _.forEach(this.arr_coins, function (coin) {
                    if (that.arr_prices[coin].price < that.arr_prices[coin].buy && that.usdtBalance > that.spend_per_order && checkBuy[coin]) {
                        let amount = Math.round(that.spend_per_order / that.arr_prices[coin].price);
                        if (that.arr_prices[coin].price > 1) {
                            amount = Math.round(that.spend_per_order / that.arr_prices[coin].price * 1000) / 1000;
                        }
                        that.usdtBalance = 0;
                        axios_api.placeMarketBuyOrder(that.arr_prices[coin].name, amount).then((response) => {
                            if (response.data.status) {
                                that.$message(response.data.msg);
                                that.refreshData();
                            }
                        }).catch((err) => {
                            console.log(err);
                        });
                    } else {
                        //console.log(false, 'buy');
                    }
                });
            }
        },
        checkForSell() {
            if (this.arr_buy_records.length > 0) {
                this.arr_buy_position = [];
                this.arr_current_diff = 0;
                this.arr_current_positon = 0;
                let that = this;
                _.forEach(this.arr_buy_records, function (item) {
                    let diff = Math.round(((that.arr_current_price[item.name] * item.qty) - (item.price * item.qty)) * 100000) / 100000;
                    let currentPositon = Math.round(that.arr_current_price[item.name] * item.qty * 100000) / 100000;
                    that.arr_current_diff = that.arr_current_diff + diff;
                    that.arr_current_positon = that.arr_current_positon + currentPositon;
                    that.arr_buy_position.push({
                        'n': item.name,
                        'd': diff,
                        'c': currentPositon
                    });
                    let sellPrice = parseFloat(item.price) + ((item.price * that.profit_margin) / 100);

                    if ((that.arr_current_price[item.name] * 1) > sellPrice && typeof sellPrice !== 'undefined' && that.sellMark) {
                        that.sellMark = false;
                        console.log(sellPrice, that.arr_current_price[item.name]);
                        axios_api.placeMarketSellOrder(item.name, item.qty).then((response) => {
                            if (response.data.status) {
                                that.$message(response.data.msg);
                                that.refreshData();
                                that.sellMark = true;
                            }
                        }).catch((err) => {

                            that.sellMark = true;
                            console.log(err);
                        })
                    } else {
                        //console.log(false, "sell");
                    }
                });
                this.arr_current_positon = this.arr_current_positon + this.usdtBalance;
            }
            this.checkForBuy();
        },
        changeProfitMargin() {
            sessionStorage.setItem('profit_margin', this.profit_margin);
        },
        changeBuyLowerAt() {
            sessionStorage.setItem('buy_low_at', this.buy_low_at);
        },
        changeSpendPerOrder() {
            sessionStorage.setItem('spend_per_order', this.spend_per_order);
        },
        stopCurrentData() {
            this.getCoins();
            this.refreshData();
        },
        refreshData() {
            this.getBuyRecords();
            this.getTradeRecords();
            this.getUSDTBalance();
        },
        getUSDTBalance() {
            axios_api.getCoinBalance('USDT').then((response) => {
                this.usdtBalance = Math.round(response.data * 100) / 100;
            }).catch((err) => {
                console.log(err);
            })
        },
        getTradeRecords() {
            axios_api.getTradeRecords().then((response) => {
                this.arr_trade_records = response.data;
            }).catch((err) => {
                console.log(err);
            });
        },
        getBuyRecords() {
            axios_api.getBuyRecords().then((response) => {
                this.arr_buy_records = response.data;
            }).catch((err) => {
                console.log(err);
            });
        },
        getCurrentPrices(ticker) {
            let that = this;
            let count = 1;
            _.forEach(ticker, function (item) {
                if (item.symbol === 'BTCUSDT') {
                    let buyPrice = Math.round((parseInt(item.low * 100000000) / 100000000 + parseInt((item.high - item.low) * (that.buy_low_at) / 100 * 100000000) / 100000000) * 100000000) / 100000000;
                    that.arr_market_direction['price'] = Math.round(item.curDayClose);
                    that.arr_market_direction['low'] = Math.round(item.low);
                    that.arr_market_direction['high'] = Math.round(item.high);
                    if (buyPrice > item.curDayClose) {
                        that.arr_market_direction['dir'] = "DOWN";
                    } else {
                        that.arr_market_direction['dir'] = "UP";
                    }
                }
                if (that.arr_coins.includes(item.symbol)) {
                    let buyPrice = Math.round((parseInt(item.low * 100000000) / 100000000 + parseInt((item.high - item.low) * that.buy_low_at / 100 * 100000000) / 100000000) * 100000000) / 100000000;
                    that.arr_current_price[item.symbol] = item.curDayClose;
                    that.arr_prices[item.symbol] = {
                        'id': count,
                        'name': item.symbol,
                        'price': item.curDayClose,
                        'high': item.high,
                        'buy': buyPrice,
                        'low': item.low
                    };
                    count++;

                }
            });
        },
        getCoins() {
            axios_api.getCoins().then((response) => {
                this.arr_coins = response.data;
                let that = this;
                _.forEach(response.data, function (coin) {
                    that.arr_prices[coin] = [];
                });
                binance_api.ws.allTickers(this.getCurrentPrices);
            }).catch((err) => {
                console.log(err);
            });
        },
        addCoin() {
            if (this.input_coin !== null) {
                axios_api.addCoin(this.input_coin).then((response) => {
                    if (response.data.status) {
                        this.$message(response.data.msg);
                        this.input_coin = null;
                        this.getCoins();
                    } else {
                        this.$message(response.data.msg);
                    }
                }).catch((err) => {
                    console.log(err);
                });
            } else {
                this.$message('Enter Coin');
            }
        },
        removeCoin() {
            if (this.input_coin !== null) {
                axios_api.removeCoin(this.input_coin).then((response) => {
                    if (response.data.status) {
                        this.$message(response.data.msg);
                        this.input_coin = null;
                        this.getCoins();
                    } else {
                        this.$message(response.data.msg);
                    }
                }).catch((err) => {
                    console.log(err);
                });
            } else {
                this.$message('Enter Coin');
            }
        },
        print(ticker) {
            console.log(ticker.curDayClose);
        }
    },
    mounted() {
        if (sessionStorage.getItem('profit_margin') !== null) {
            this.profit_margin = sessionStorage.getItem('profit_margin');
        }
        if (sessionStorage.getItem('buy_low_at') !== null) {
            this.buy_low_at = sessionStorage.getItem('buy_low_at');
        }
        if (sessionStorage.getItem('spend_per_order') !== null) {
            this.spend_per_order = sessionStorage.getItem('spend_per_order');
        }
    }
}
</script>

<style scoped>

</style>
