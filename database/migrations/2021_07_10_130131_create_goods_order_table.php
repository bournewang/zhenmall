<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_order', function (Blueprint $table) {
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('goods_id')->unsigned();
            $table->decimal('price', 8,2);
            $table->decimal('quantity', 8,2);
            $table->decimal('subtotal', 8,2);
            
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('goods_id')->references('id')->on('goods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_order');
    }
}
