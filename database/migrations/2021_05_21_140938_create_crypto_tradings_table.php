<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoTradingsTable extends Migration
{
    public function up()
    {
        Schema::create('crypto_tradings', function (Blueprint $table) {
            $table->id();
            $table->string('ticker');
            $table->string('amount');
            $table->string('price');
            $table->string('buy_sell');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('crypto_tradings');
    }
}
