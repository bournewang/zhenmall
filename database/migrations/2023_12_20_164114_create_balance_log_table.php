<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balance_log', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->enum('type', array_keys(\App\Models\BalanceLog::typeOptions()))->nullable();
            $table->decimal('amount', 10,2);
            $table->decimal('balance', 10,2);
            $table->string('comment');
            $table->boolean('open')->default(1);
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
        Schema::dropIfExists('balance_log');
    }
}
