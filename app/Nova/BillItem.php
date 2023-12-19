<?php

namespace App\Nova;

use App\Nova\Filters\PeriodFilter;
use App\Nova\Filters\YearMonthFilter;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class BillItem extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\BillItem::class;
    public static function label()
    {
        return __('Bill Item');
    }
    public static function group()
    {
        return __("Accounting Center");
    }
    public static function icon()
    {
        return view("nova::svg.view-list");
    }
//    public static function availableForNavigation(Request $request): bool
//    {
//        return false;
//    }
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
            BelongsTo::make(__('Store'), 'store', Store::class)->nullable(),
            BelongsTo::make(__('User'), 'user', User::class),
            MorphTo::make(__('Order'), 'order'),
//            Text::make(__('Month'), 'month')->onlyOnForms(),
//            Number::make(__('Period'), 'period')->onlyOnForms(),
            Text::make(__('Settlement Period'))->displayUsing(function(){return $this->year.'-'.$this->month .  __('Month').__('Period Index', ['period' => $this->period ]);})->exceptOnForms(),
            Text::make(__('Role'), 'role')->displayUsing(function(){return \App\Models\User::sharingRoleOptions()[$this->role] ?? null;}),
            Currency::make(__('Consume Price'), 'price', )->currency('CNY'),
            Text::make(__('Share'), 'share')->displayUsing(function(){return $this->share.'%';}),
            Currency::make(__('Sharing').__('Amount'), 'amount')->currency('CNY'),
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
    public function detachedFilters(Request $request)
    {
        return [
            new YearMonthFilter(),
            new PeriodFilter()
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
        return [];
    }
}
