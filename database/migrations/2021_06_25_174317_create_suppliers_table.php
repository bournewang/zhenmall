<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Supplier;
class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('license_no')->nullable();
            $table->string('account_bank')->nullable();
            $table->string('account_no')->nullable();
            $table->string('contact')->nullable();
            $table->string('mobile')->nullable();
            
            $table->bigInteger('province_id')->unsigned()->nullable();
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->bigInteger('district_id')->unsigned()->nullable();
            $table->string('street')->nullable();
            $table->enum('status', array_keys((new Supplier)->statusOptions()));

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
