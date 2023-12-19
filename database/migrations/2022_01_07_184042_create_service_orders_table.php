<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('device_id')->unsigned();
            $table->string('order_no')->unique();
            $table->string('title')->nullable();
            $table->string('detail')->nullable();
            $table->decimal('amount', 8,2)->nullable();
            $table->text('raw_data')->nullable();
            $table->string('status', 20)->nullable();
            $table->timestamps();
            
            $table->foreign('store_id')->references('id')->on('stores');  
            $table->foreign('user_id')->references('id')->on('users');  
            $table->foreign('device_id')->references('id')->on('devices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_orders');
    }
}
