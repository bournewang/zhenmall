<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('goods_id')->unsigned();
            $table->decimal('quantity',8,2)->nullable();
            $table->timestamps();
            
            $table->foreign('store_id')->references('id')->on('stores');  
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->unique(['store_id', 'goods_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
