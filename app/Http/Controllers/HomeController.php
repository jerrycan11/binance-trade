<?php

namespace App\Http\Controllers;

use App\Models\CryptoTradings;
use App\Models\RecordsCoins;
use App\Models\RecordsCrypto;
use Binance\API;
use Binance\RateLimiter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $api;

    public function __construct()
    {
    }

    public function getCoinBalance(Request $request)
    {
        $coin = $request->coin;
        $api = new API(env('B_KEY'), env('B_SECRET'));
        $api = new RateLimiter($api);
        $balances = $api->balances();
        return response($balances[$coin]['available']);
    }

    public function getBuyRecords()
    {
        return response(RecordsCrypto::select('name', 'price', 'qty')->get());
    }

    public function getTradeRecords()
    {
        return response(CryptoTradings::select('ticker', 'amount', 'price', 'buy_sell')->orderBy('id', 'DESC')->take(25)->get());
    }

    public function addCoin(Request $request)
    {
        $rs = RecordsCoins::where('coin', $request->c)->first();
        if ($rs == null) {
            $rc = new RecordsCoins();
            $rc->coin = $request->c;
            $rc->save();
            return response([
                'status' => true,
                'msg' => 'Coin added!'
            ]);
        } else {
            return response([
                'status' => false,
                'msg' => 'Coin already present!'
            ]);
        }
    }

    public function removeCoin(Request $request)
    {
        $rs = RecordsCoins::where('coin', $request->c)->first();
        if ($rs != null) {
            RecordsCoins::where('coin', $request->c)->delete();
            return response([
                'status' => true,
                'msg' => 'Coin removed!'
            ]);
        } else {
            return response([
                'status' => false,
                'msg' => 'Coin not present!'
            ]);
        }
    }

    public function getCoins()
    {
        $coins = RecordsCoins::select('coin')->get();
        $arr_coins = [];
        foreach ($coins as $coin) {
            array_push($arr_coins, $coin->coin);
        }
        return response($arr_coins);
    }

    public function placeMarketBuyOrder(Request $request)
    {
        $api = new API(env('B_KEY'), env('B_SECRET'));
        $api = new RateLimiter($api);
        $order = $api->marketBuy($request->c, $request->q);
        $this->saveOrder($order);
        $this->saveBuyDetail($order);
        return response([
            'status' => true,
            'msg' => "Market buy order placed!"
        ]);
    }


    public function placeMarketSellOrder(Request $request)
    {
        $api = new API(env('B_KEY'), env('B_SECRET'));
        $api = new RateLimiter($api);
        $order = $api->marketSell($request->c, $request->q);
        $this->saveOrder($order);
        RecordsCrypto::where('name', $request->c)->delete();
        return response([
            'status' => true,
            'msg' => "Market sell order placed!"
        ]);
    }


    private function saveBuyDetail($order)
    {
        $rc = new RecordsCrypto();
        $rc->name = $order['symbol'];
        $rc->price = $order['cummulativeQuoteQty'] / $order['executedQty'];
        $rc->qty = $order['executedQty'];
        $rc->save();
    }

    private function saveOrder($order)
    {
        $ct = new CryptoTradings();
        $ct->ticker = $order['symbol'];
        $ct->amount = $order['executedQty'];
        $ct->price = $order['cummulativeQuoteQty'];
        $ct->buy_sell = $order['side'];
        $ct->save();
    }
}
