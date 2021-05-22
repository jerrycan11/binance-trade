<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsCryptosTable extends Migration
{
    public function up()
    {
        Schema::create('records_cryptos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('price');
            $table->string('qty');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('records_cryptos');
    }
}
