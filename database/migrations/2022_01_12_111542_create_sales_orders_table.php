<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->string('order_no', 32)->unique();
            $table->decimal('total_quantity',8,2)->nullable();
            $table->decimal('total_price',8,2)->nullable();
            $table->decimal('paid_price',8,2)->nullable();
            $table->string('status', 20)->nullable()->default('shipped');
            $table->text('items')->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
            
            $table->foreign('store_id')->references('id')->on('stores');  
            $table->foreign('user_id')->references('id')->on('users');  
            $table->foreign('customer_id')->references('id')->on('users');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_orders');
    }
}
