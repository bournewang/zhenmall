<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Http\Requests\NovaRequest;

class MembershipUsedItem extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\MembershipUsedItem::class;
    public static function label()
    {
        return __('Membership Used Item');
    }
    public static function group()
    {
        return __("Chain Store");
    }
    public static function icon()
    {
        return null;//view("nova::svg.".strtolower(explode('\\', self::class)[2]));
    }
    public static function availableForNavigation(Request $request): bool
    {
        return false;
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
//            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('Membership Card'), 'membershipCard', MembershipCard::class)->rules('required')->withoutTrashed(),
            BelongsTo::make(__('Store'), 'store', Store::class)->rules('required')->withoutTrashed(),
            BelongsTo::make(__('Handler'), 'user', Clerk::class)->rules('required'),
            BelongsTo::make(__('Customer'), 'customer', Customer::class)->rules('required'),
//            Text::make(__('Card No'), 'card_no')->rules('required'),
//            Currency::make(__('Total Price'), 'total_price')->currency('CNY')->rules('required'),
            Currency::make(__('Amount'), 'paid_price')->currency('CNY')->rules('required'),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    // public function cards(Request $request)
    // {
    //     return [];
    // }

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
