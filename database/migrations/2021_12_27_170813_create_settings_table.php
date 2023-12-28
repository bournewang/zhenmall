<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer("level_0_rewards_days");// 注册用户可领红包天数
            $table->integer("level_0_rewards_min"); // 注册用户可领红包数额下限
            $table->integer("level_0_rewards_max"); // 注册用户可领红包数额上限

            // 一级（99元）
            $table->bigInteger('level_1_cat_id')->unsigned(); // 商品类别
            $table->integer("level_1_rewards_days");// 用户可领红包天数
            $table->integer("level_1_rewards_min"); // 用户可领红包数额下限
            $table->integer("level_1_rewards_max"); // 用户可领红包数额上限
            $table->integer("level_1_rewards_quota");//商品下单获得积分（额度）
            $table->integer("level_1_rewards_referer");//商品下单直接推荐人收益
            $table->integer("level_1_common_wealth_level");//商品下单影响共富层级
            $table->integer("level_1_common_wealth_min");//商品下单,共富红包下限
            $table->integer("level_1_common_wealth_max");//商品下单,共富红包上限

            // 二级（499元）
            $table->bigInteger('level_2_cat_id')->unsigned(); // 商品类别
            $table->integer("level_2_rewards_days");// 用户可领红包天数
            $table->integer("level_2_rewards_min"); // 用户可领红包数额下限
            $table->integer("level_2_rewards_max"); // 用户可领红包数额上限
            $table->integer("level_2_rewards_quota");//商品下单用户获得积分（额度）
            $table->integer("level_2_rewards_referer");//商品下单直接推荐人收益
            $table->integer("level_2_common_wealth_level");//商品下单影响共富层级
            $table->integer("level_2_common_wealth_min");//商品下单,共富红包下限
            $table->integer("level_2_common_wealth_max");//商品下单,共富红包上限

            $table->timestamps();
            $table->foreign('level_1_cat_id')->references('id')->on('categories');
            $table->foreign('level_2_cat_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
