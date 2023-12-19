<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogisticProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistic_progress', function (Blueprint $table) {
            $table->id();
            $table->string('order_type', 32);
            $table->bigInteger('order_id')->unsigned();
            // $table->bigInteger('logistic_id')->unsigned();
            $table->integer('queryTimes')->nullable();
            $table->integer('fee_num')->nullable();
            $table->integer('status')->nullable();
            $table->string('upgrade_info')->nullable();
            $table->string('expSpellName')->nullable();
            $table->string('expTextName')->nullable();
            $table->string('mailNo');
            $table->string('msg')->nullable();
            $table->datetime('updateStr')->nullable();
            $table->string('possibleExpList')->nullable();
            $table->boolean('flag')->nullable();
            $table->integer('ret_code')->nullable();
            $table->string('logo')->nullable();
            $table->string('tel')->nullable();
            $table->text('data')->nullable();
            $table->timestamps();
            
            // $table->foreign('order_id')->references('id')->on('orders');  
            // $table->foreign('logistic_id')->references('id')->on('logistics');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logistic_progress');
    }
}
