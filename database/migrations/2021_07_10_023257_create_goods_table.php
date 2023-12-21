<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->unsigned();
            // $table->bigInteger('supplier_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('qty')->nullable();
            // $table->string('type')->nullable();
            $table->string('brand')->nullable();
            $table->decimal('price_ori', 8, 2)->nullable();
            $table->decimal('price_purchase', 8, 2)->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->text('detail')->nullable();
            $table->enum('status', array_keys((new App\Models\Goods)->statusOptions()))->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('categories');
            // $table->foreign('supplier_id')->references('id')->on('suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
    }
}
