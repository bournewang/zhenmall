<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $table = 'settings';

    public $fillable = [
        "level_0_rewards_days",// 注册用户可领红包天数
        "level_0_rewards_min", // 注册用户可领红包数额下限
        "level_0_rewards_max", // 注册用户可领红包数额上限

        // 一级（99元）
        "level_1_cat_id",
        "level_1_rewards_days",// 用户可领红包天数
        "level_1_rewards_min", // 用户可领红包数额下限
        "level_1_rewards_max", // 用户可领红包数额上限
        "level_1_rewards_quota",//商品下单获得积分（额度）
        "level_1_common_wealth_level",//商品下单影响共富层级
        "level_1_common_wealth_min",//商品下单,共富红包下限
        "level_1_common_wealth_max",//商品下单,共富红包上限

        // 二级（499元）
        "level_2_cat_id",
        "level_2_rewards_days",// 用户可领红包天数
        "level_2_rewards_min", // 用户可领红包数额下限
        "level_2_rewards_max", // 用户可领红包数额上限
        "level_2_rewards_quota",//商品下单用户获得积分（额度）
        "level_2_common_wealth_level",//商品下单影响共富层级
        "level_2_common_wealth_min",//商品下单,共富红包下限
        "level_2_common_wealth_max",//商品下单,共富红包上限
    ];

    protected $casts = [
        "level_0_rewards_days" => "integer",// 注册用户可领红包天数
        "level_0_rewards_min" => "integer", // 注册用户可领红包数额下限
        "level_0_rewards_max" => "integer", // 注册用户可领红包数额上限

        // 一级（99元）
        "level_1_cat_id" => "integer",
        "level_1_rewards_days" => "integer",// 用户可领红包天数
        "level_1_rewards_min" => "integer", // 用户可领红包数额下限
        "level_1_rewards_max" => "integer", // 用户可领红包数额上限
        "level_1_rewards_quota" => "integer",//商品下单获得积分（额度）
        "level_1_rewards_referer" => "integer", //商品下单直接推荐人收益
        "level_1_common_wealth_level" => "integer",//商品下单影响共富层级
        "level_1_common_wealth_min" => "integer",//商品下单,共富红包下限
        "level_1_common_wealth_max" => "integer",//商品下单,共富红包上限

        // 二级（499元）
        "level_2_cat_id" => "integer",
        "level_2_rewards_days" => "integer",// 用户可领红包天数
        "level_2_rewards_min" => "integer", // 用户可领红包数额下限
        "level_2_rewards_max" => "integer", // 用户可领红包数额上限
        "level_2_rewards_quota" => "integer",//商品下单用户获得积分（额度）
        "level_2_rewards_referer" => "integer", //商品下单直接推荐人收益
        "level_2_common_wealth_level" => "integer",//商品下单影响共富层级
        "level_2_common_wealth_min" => "integer",//商品下单,共富红包下限
        "level_2_common_wealth_max" => "integer",//商品下单,共富红包上限
    ];

    public function level1Category()
    {
        return $this->belongsTo(Category::class, 'level_1_cat_id');
    }

    public function level2Category()
    {
        return $this->belongsTo(Category::class, 'level_2_cat_id');
    }
}
