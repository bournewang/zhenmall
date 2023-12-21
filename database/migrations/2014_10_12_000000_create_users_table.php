<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id')->unsigned()->nullable();
            $table->bigInteger('referer_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('openid', 64)->nullable()->unique();
            $table->string('unionid', 64)->nullable()->unique();
            $table->string('nickname', 32)->nullable();
            $table->string('avatar')->nullable();
            $table->integer('gender')->nullable();
            $table->string('mobile', 24)->nullable()->unique();
            $table->string('province', 32)->nullable();
            $table->string('city', 32)->nullable();
            $table->string('county', 32)->nullable();
            $table->string('qrcode', 64)->nullable();
            $table->string('id_no', 24)->nullable();
            $table->decimal('balance', 10, 2)->nullable();
            $table->decimal('quota', 10, 2)->nullable();

            $table->string('wechat', 24)->nullable();
            $table->string('bank_key', 24)->nullable();
            $table->string('bank_name', 32)->nullable();
            $table->string('account_no', 32)->nullable();

            $table->enum('type', array_keys(User::typeOptions()))->nullable();
            $table->enum('status', array_keys(User::statusOptions()))->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('api_token', 80)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

//            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('referer_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
