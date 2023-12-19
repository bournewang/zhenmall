<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Store;
class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('company_name')->unique()->nullable();
            $table->string('license_no')->nullable();
            $table->string('account_bank')->nullable();
            $table->string('account_no')->nullable();
            $table->string('contact')->index()->nullable();
            $table->string('mobile')->index()->nullable();
            $table->string('vice_contact')->index()->nullable();
            $table->string('vice_mobile')->index()->nullable();
            $table->bigInteger('manager_id')->unsigned()->nullable();
            $table->bigInteger('vice_manager_id')->unsigned()->nullable();
            $table->bigInteger('salesman_id')->unsigned()->nullable();
            $table->bigInteger('province_id')->unsigned()->nullable();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->bigInteger('district_id')->unsigned()->nullable();
            $table->string('street')->nullable();
            $table->enum('status', array_keys((new Store)->statusOptions()))->nullable();
            $table->text('profit_sharing')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('district_id')->references('id')->on('districts');
//            $table->foreign('manager_id')->references('id')->on('users');
//            $table->foreign('vice_manager_id')->references('id')->on('users');
//            $table->foreign('salesman_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
