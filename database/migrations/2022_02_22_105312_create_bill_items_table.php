<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->string('order_type', 32);
            $table->bigInteger('order_id')->unsigned();
            $table->smallInteger('year');
            $table->tinyInteger('month');
            $table->tinyInteger('period')->unsigned();
            $table->string('role',16)->nullable();
            $table->decimal('price', 10,2);
            $table->decimal('share', 4,2);
            $table->decimal('amount', 10,2);
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
