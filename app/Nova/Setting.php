<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Panel;

class Setting extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Setting::class;

    public static function label()
    {
        return __('Setting');
    }
    public static function group()
    {
        return __("Settings");
    }
    public static function icon()
    {
        return view("nova::svg.".strtolower(explode('\\', self::class)[2]));
    }
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Panel::make("0元注册用户", [
                Text::make("注册用户可领红包天数", "level_0_rewards_days"),
                Text::make("注册用户可领红包数额下限", "level_0_rewards_min"),
                Text::make("注册用户可领红包数额上限", "level_0_rewards_max"),
            ]),

            // 一级（99元）
            Panel::make("一级（99元）", [
                // Text::make("商品分类", "level_1_cat_id"),
                BelongsTo::make(__('Category'), 'level1Category', Category::class)->sortable()->rules('required'),
                Text::make("用户可领红包天数", "level_1_rewards_days"),
                Text::make("用户可领红包数额下限", "level_1_rewards_min"),
                Text::make("用户可领红包数额上限", "level_1_rewards_max"),
                Text::make("商品下单获得积分（额度）", "level_1_rewards_quota"),
                Text::make("商品下单直接推荐人收益", "level_1_rewards_referer"),
                Text::make("商品下单影响共富层级", "level_1_common_wealth_level"),
                Text::make("商品下单,共富红包下限", "level_1_common_wealth_min"),
                Text::make("商品下单,共富红包上限", "level_1_common_wealth_max"),
            ]),

            // 二级（499元）
            Panel::make("二级（499元）", [
                BelongsTo::make(__('Category'), 'level2Category', Category::class)->sortable()->rules('required'),
                Text::make("用户可领红包天数", "level_2_rewards_days"),
                Text::make("用户可领红包数额下限", "level_2_rewards_min"),
                Text::make("用户可领红包数额上限", "level_2_rewards_max"),
                Text::make("商品下单用户获得积分（额度）", "level_2_rewards_quota"),
                Text::make("商品下单直接推荐人收益", "level_2_rewards_referer"),
                Text::make("商品下单影响共富层级", "level_2_common_wealth_level"),
                Text::make("商品下单,共富红包下限", "level_2_common_wealth_min"),
                Text::make("商品下单,共富红包上限", "level_2_common_wealth_max"),
            ])
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
