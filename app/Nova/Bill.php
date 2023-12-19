<?php

namespace App\Nova;

use App\Nova\Actions\BillClosed;
use App\Nova\Actions\ImportBills;
use App\Nova\Actions\Outstanding;
use App\Nova\Filters\BillStatusFilter;
use App\Nova\Filters\PeriodFilter;
use App\Nova\Filters\YearMonthFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use WesselPerik\StatusField\StatusField;

class Bill extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Bill::class;
    public static function label()
    {
        return __('Bill');
    }
    public static function group()
    {
        return __("Accounting Center");
    }
    public static function icon()
    {
        return view("nova::svg.currency-yen");
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
            BelongsTo::make(__('Store'), 'store', Store::class)->nullable(),
            BelongsTo::make(__('User'), 'user', User::class),
            Text::make(__('Settlement Period'))->displayUsing(function(){return $this->year.'-'.$this->month .  __('Month').__('Period Index', ['period' => $this->period ]);})->exceptOnForms(),
            Currency::make(__('Amount'), 'amount')->currency('CNY'),
            Select::make(__('Status'), 'status')->options(\App\Models\Bill::statusOptions())->onlyOnForms(),
            StatusField::make(__('Status'), 'status')
                ->values([
                    'pending'  => \App\Models\Bill::OUTSTANDING  == $this->status,
//                    'pending'   => $this->pending == $this->status,
                    'active'    => \App\Models\Bill::CLOSED == $this->status
                ])
                ->info($this->statusLabel())
                ->exceptOnForms(),
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
            new PeriodFilter(),
            new BillStatusFilter()
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
        return [
            new ImportBills(),
            new Outstanding(),
            new BillClosed()
        ];
    }
}
