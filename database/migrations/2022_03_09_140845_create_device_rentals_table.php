<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_rentals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->decimal('deposit_price', 8,2);
            $table->decimal('paid_price', 8,2);
            $table->string  ('validity_type');
            $table->integer ('validity_period');
            $table->date('validity_start')->nullable();
            $table->date('validity_to')->nullable();
            $table->enum('status', array_keys(\App\Models\DeviceRental::statusOptions()));
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
        Schema::dropIfExists('device_rentals');
    }
}
