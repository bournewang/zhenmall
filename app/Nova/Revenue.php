<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class Revenue extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Revenue::class;
    public static $title = 'id';
    public static $with = ['user', 'store'];
    public static $priority = 2;
    public static $search = [
        'id',
    ];

    public static function label()
    {
        return __("Revenue");
    }

    public static function group()
    {
        return __("Finance");
    }

    public static function icon()
    {
        return view("nova::svg.".strtolower(explode('\\', self::class)[2]));
    }
    public static function availableForNavigation(Request $request): bool
    {
        return false;
    }
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('Store'), 'store', Store::class),
            BelongsTo::make(__('User'), 'user', Sales::class)->sortable(),
            Text::make(__('PPV'), 'ppv'),
            Text::make(__('GPV'), 'gpv'),
            Text::make(__('TGPV'), 'tgpv'),
            Text::make(__('PGPV'), 'pgpv'),
            // Text::make(__('AGPV'), 'agpv'),
            // Text::make(__('Revenue'), 'retail_income')->displayUsing(function($v){return money($v);}),
            // Text::make(__('Revenue'), 'retail_income')->displayUsing(function($v){return money($v);}),
            $this->moneyfield(__('Retail Income'), 'retail_income')->sortable(),
            $this->moneyfield(__('Level Bonus'), 'level_bonus')->sortable(),
            $this->moneyfield(__('Leader Bonus'), 'leader_bonus')->sortable(),
            $this->moneyfield(__('Total Income'), 'total_income')->sortable(),
            Date::make(__('Clearing Period'), 'start')->displayUsing(function(){return $this->year . '-' . $this->index . '月份';}),
            Text::make(__('Org Chart'), '-')->displayUsing(function(){
                return "<a class='no-underline font-bold dim text-danger' href='/sales/$this->user_id/relation' target=_blank>".__('Org Chart')."</a>";
            })->asHtml(),
            Boolean::make(__('Clearing').__('Status'), 'clearing_status'),
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
        return [
            new Filters\UserFilter,
            new Filters\IndexFilter
            // new Filters\GoodsFilter
        ];
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
        if (!$request->user()->can(__('Action'). __('Revenue'))) {
            return [];
        }
        return [
            (new Actions\Clearing)->canRun(function(){return 1;}),
            (new Actions\Disclearing)->canRun(function(){return 1;}),
        ];
    }
}
