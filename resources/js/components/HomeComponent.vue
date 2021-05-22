<template>
    <el-container>
        <el-row>
            <el-col :span="5">
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
                                <el-tag v-else type="success">
                                    {{ scope.row.d }}
                                </el-tag>
                            </template>
                        </el-table-column>
                        <el-table-column prop="c" label="Current"></el-table-column>
                    </el-table>
                    <el-tag>
                        Diff Total: {{ Math.round(arr_current_diff * 1000) / 1000 }}
                    </el-tag>
                    <el-tag>
                        Current Total: {{ Math.round(arr_current_positon * 1000) / 1000 }}
                    </el-tag>
                </el-card>
            </el-col>
            <el-col :span="11">
                <el-card>
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
                                <el-input-number v-model="profit_margin" size="mini"></el-input-number>
                            </el-descriptions-item>
                            <el-descriptions-item label="Buy At lower" size="mini">
                                <el-input-number v-model="buy_low_at" size="mini"></el-input-number>
                            </el-descriptions-item>
                            <el-descriptions-item label="Spend Per Order" size="mini">
                                <el-input-number v-model="spend_per_order" size="mini"></el-input-number>
                            </el-descriptions-item>
                        </el-descriptions>
                    </template>
                    <el-table :data="arr_prices" size="mini">
                        <el-table-column prop="id" label="ID" width="50px"></el-table-column>
                        <el-table-column prop="name" label="Name" width="110px"></el-table-column>
                        <el-table-column label="Price" width="120px">
                            <template #default="scope">
                                <el-tag v-if="scope.row.price<scope.row.buy" type="danger" size="mini">
                                    {{ scope.row.price }}
                                </el-tag>
                                <el-tag v-else type="success" size="mini">
                                    {{ scope.row.price }}
                                </el-tag>
                            </template>
                        </el-table-column>
                        <el-table-column prop="buy" label="Buy At" width="120px"></el-table-column>
                        <el-table-column prop="high" label="24High" width="120px"></el-table-column>
                        <el-table-column prop="low" label="24Low" width="120px"></el-table-column>
                    </el-table>
                </el-card>
            </el-col>
            <el-col :span="8">
                <el-card>
                    <template #header>
                        Trade Records
                    </template>
                    <el-table :data="arr_trade_records" size="mini">
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
            usdtBalance: 0,
            interval: null,
            stopButtonCurrentRecord: 'Start',
            arr_coins: [
                "XLMUSDT", "XVSUSDT",
                "FIROUSDT", "DOGEUSDT", "AUDIOUSDT",
                "SHIBUSDT", "ADAUSDT", "XRPUSDT",
                "CHZUSDT", "XLMUSDT", "ONEUSDT",
                "REFUSDT", "AAVEUSDT", "COTIUSDT",
                "LTCUSDT", 'WINUSDT', "ATMUSDT",
                "CELRUSDT", "ETCUSDT", "DATAUSDT",
                "ICTUSDT", "BTTUSDT", "SNXUSDT",
                "NKNUUSDT", "QTUMUSDT", "SUSHIUSDT",
                'DOTUSDT', "MATICUSDT", "SOLUSDT",
                "EOSUSDT", "LINKUSDT", "NMRUSDT"
            ]
        }
    },
    methods: {
        stopCurrentData() {
            if (this.stopButtonCurrentRecord === "Stop") {
                clearInterval(this.interval);
                this.interval = null;
                this.stopButtonCurrentRecord = "Start";
            } else {
                this.refreshData();
                this.interval = setInterval(this.getCurrentPrices, 3000);
                this.stopButtonCurrentRecord = "Stop";
            }
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
        getCurrentPrices() {
            binance_api.dailyStats().then((response) => {
                let that = this;
                let count = 1;
                that.arr_prices = [];
                _.forEach(response, function (item) {
                    if (item.symbol === 'BTCUSDT') {
                        let buyPrice = Math.round((parseInt(item.lowPrice * 100000000) / 100000000 + parseInt((item.highPrice - item.lowPrice) * that.buy_low_at / 100 * 100000000) / 100000000) * 100000000) / 100000000;
                        that.arr_market_direction['price'] = Math.round(item.lastPrice);
                        that.arr_market_direction['low'] = Math.round(item.lowPrice);
                        that.arr_market_direction['high'] = Math.round(item.highPrice);
                        if (buyPrice > item.lastPrice) {
                            that.arr_market_direction['dir'] = "DOWN";
                        } else {
                            that.arr_market_direction['dir'] = "UP";
                        }
                    }
                    if (that.arr_coins.includes(item.symbol)) {
                        let buyPrice = Math.round((parseInt(item.lowPrice * 100000000) / 100000000 + parseInt((item.highPrice - item.lowPrice) * that.buy_low_at / 100 * 100000000) / 100000000) * 100000000) / 100000000;
                        that.arr_current_price[item.symbol] = item.lastPrice;
                        that.arr_prices.push({
                            'id': count,
                            'name': item.symbol,
                            'price': item.lastPrice,
                            'high': item.highPrice,
                            'buy': buyPrice,
                            'low': item.lowPrice
                        });
                        count++;
                        if (that.arr_market_direction['dir'] === "UP") {
                            if (item.lastPrice < buyPrice && that.usdtBalance > that.spend_per_order) {
                                let amount = Math.round(that.spend_per_order / item.lastPrice);
                                if (item.lastPrice > 1) {
                                    amount = Math.round(that.spend_per_order / item.lastPrice * 100) / 100;
                                }
                                axios_api.placeMarketBuyOrder(item.symbol, amount).then((response) => {
                                    if (response.data.status) {
                                        this.$message(response.data.msg);
                                        this.refreshData();
                                    }
                                }).catch((err) => {
                                    console.log(err);
                                });
                            }
                        }
                    }
                });
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
                        let sellPrice = item.price * (100 + that.profit_margin) / 100;
                        if (that.arr_current_price[item.name] > sellPrice) {
                            axios_api.placeMarketSellOrder(item.symbol, item.qty).then((response) => {
                                if (response.data.status) {
                                    this.$message(response.data.msg);
                                    this.refreshData();
                                }
                            }).catch((err) => {
                                console.log(err);
                            })
                        }
                    });
                }
            }).catch((err) => {
                console.log(err);
            })
        }
    },
    mounted() {
        this.getCurrentPrices();
    }
}
</script>

<style scoped>

</style>
