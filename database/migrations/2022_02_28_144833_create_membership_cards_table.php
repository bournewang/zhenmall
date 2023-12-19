<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembershipCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_cards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->string('card_no');
            $table->decimal('total_price', 8,2)->nullable();
            $table->decimal('paid_price', 8,2);
            $table->decimal('single_price', 8, 2)->nullable();
            $table->string  ('validity_type');
            $table->integer ('validity_period');
            $table->date('validity_start')->nullable();
            $table->date('validity_to')->nullable();
            $table->integer('used_times')->nullable();
            $table->enum('status', array_keys(\App\Models\MembershipCard::statusOptions()));
            $table->string('comment')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('users');

            $table->unique(['store_id', 'card_no']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('membership_cards');
    }
}
