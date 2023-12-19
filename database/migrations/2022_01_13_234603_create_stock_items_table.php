<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\StockItem;
class CreateStockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('goods_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('stock_id')->unsigned();
            $table->string('order_type', 32);
            $table->bigInteger('order_id')->unsigned();
            $table->decimal('quantity',8,2)->nullable();
            $table->enum('type', array_keys(StockItem::typeOptions()))->nullable();
            $table->timestamps();
            
            $table->foreign('store_id')->references('id')->on('stores');  
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('stock_id')->references('id')->on('stocks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_items');
    }
}
