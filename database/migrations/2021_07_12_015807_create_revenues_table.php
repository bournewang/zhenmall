<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->integer('year');
            $table->integer('index'); // month: 1-12 /week: 1-52
            $table->datetime('start');
            $table->datetime('end');
            $table->decimal('ppv', 8,2)->nullable();
            $table->decimal('gpv', 8,2)->nullable();
            $table->decimal('tgpv', 8, 2)->nullable(); 
            $table->decimal('pgpv', 8, 2)->nullable(); 
            $table->decimal('agpv', 8, 2)->nullable();
            $table->decimal('income_ratio', 8, 2)->nullable();
            $table->decimal('retail_income', 8, 2)->nullable();
            $table->decimal('level_bonus', 8, 2)->nullable();
            $table->decimal('leader_bonus', 8, 2)->nullable();
            $table->decimal('width_bonus', 8, 2)->nullable();
            $table->decimal('depth_bonus', 8, 2)->nullable();
            $table->decimal('total_income', 8, 2)->nullable();
            // $table->text('detail')->nullable();
            $table->boolean('clearing_status')->defualt(0);
            $table->timestamps();
            $table->softDeletes();
            
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
        Schema::dropIfExists('revenues');
    }
}
