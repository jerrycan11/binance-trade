<template>
    <el-container>
        <el-main>
            <el-card>

            </el-card>
        </el-main>
    </el-container>
</template>

<script>
import binance_api from "../binance_api";

export default {
    name: "CryptoHome",
    data() {
        return {
            ticker: []
        }
    },
    methods: {
        getTicker(ticker) {
            let that = this;
            _.forEach(ticker, function (item) {
                if (item.symbol === "BTCUSDT") {
                    console.log(item);
                }
            });
        }
    },
    mounted() {
        binance_api.ws.allTickers(this.getTicker);
    }
}
</script>

<style scoped>

</style>
