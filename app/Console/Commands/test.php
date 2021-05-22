<?php

namespace App\Console\Commands;

use Binance\API;
use Binance\RateLimiter;
use Illuminate\Console\Command;

class test extends Command
{
    protected $signature = 't:r';
    protected $description = 'Command description';
    private $api;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->api = new API(env('B_KEY'), env('B_SECRET'));
        $this->api = new RateLimiter($this->api);

        $orders = $this->api->orders("LTCUSDT");
        print_r($orders);
    }
}
