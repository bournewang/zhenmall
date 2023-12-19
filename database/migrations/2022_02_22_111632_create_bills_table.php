<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->smallInteger('year');
            $table->tinyInteger('month');
            $table->tinyInteger('period')->unsigned();
            $table->decimal('amount', 10,2);
            $table->enum('status', array_keys(\App\Models\Bill::statusOptions()))->nullable();
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
