<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_goods', function (Blueprint $table) {
            $table->bigInteger('cart_id')->unsigned();
            $table->bigInteger('goods_id')->unsigned();
            $table->decimal('price', 8,2);
            $table->decimal('quantity', 8,2);
            $table->decimal('subtotal', 8,2);
            
            $table->foreign('cart_id')->references('id')->on('carts');
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
        Schema::dropIfExists('cart_goods');
    }
}
