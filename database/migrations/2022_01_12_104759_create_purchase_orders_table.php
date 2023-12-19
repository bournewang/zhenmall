<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PurchaseOrder;
class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('order_no', 32)->unique();
            $table->decimal('total_quantity',8,2)->nullable();
            $table->decimal('total_price',8,2)->nullable();
            $table->bigInteger('logistic_id')->unsigned()->nullable();
            $table->string('waybill_number', 32)->nullable();
            $table->text('items')->nullable();
            // $table->string('status', 20)->nullable();
            $table->enum('status', array_keys(PurchaseOrder::statusOptions()))->nullable();
            $table->string('comment')->nullable();
            $table->timestamps();
            
            $table->foreign('store_id')->references('id')->on('stores');  
            $table->foreign('user_id')->references('id')->on('users');  
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
        Schema::dropIfExists('purchase_orders');
    }
}
