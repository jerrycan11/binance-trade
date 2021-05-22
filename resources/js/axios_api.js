import axios from "axios";

const link = '';

const axios_api = {
    getConnection: function () {
        return axios.create({
            baseURL: '/',
            withCredentials: false,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + null
            }
        });
    },
    getBuyRecords: function () {
        let data = {};
        let url = link + '/api/getBuyRecords';
        return this.getConnection().post(url, data);
    },
    placeMarketBuyOrder: function (c, q) {
        let data = {
            c: c,
            q: q
        };
        let url = link + '/api/placeMarketBuyOrder';
        return this.getConnection().post(url, data);
    },
    placeMarketSellOrder: function (c, q) {
        let data = {
            c: c,
            q: q
        };
        let url = link + '/api/placeMarketSellOrder';
        return this.getConnection().post(url, data);
    },
    getCoinBalance: function (coin) {
        let data = {
            coin: coin
        };
        let url = link + '/api/getCoinBalance';
        return this.getConnection().post(url, data);
    },
    getTradeRecords: function () {
        let data = {};
        let url = link + '/api/getTradeRecords';
        return this.getConnection().post(url, data);
    }
}

export default axios_api;
