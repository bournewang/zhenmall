<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->string('order_no', 24)->unique();
            $table->decimal('amount', 8,2)->nullable();
            $table->bigInteger('province_id')->unsigned()->nullable();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->bigInteger('district_id')->unsigned()->nullable();
            $table->string('street')->nullable();
            $table->string('contact', 24)->nullable();
            $table->string('mobile', 16)->nullable()->index();
            $table->bigInteger('logistic_id')->unsigned()->nullable();
            $table->string('waybill_number', 32)->nullable();
            $table->enum('status', array_keys(Order::statusOptions()))->nullable();
            $table->integer('ship_status')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('refund_at')->nullable();
            $table->boolean('profit_splited')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('logistic_id')->references('id')->on('logistics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
