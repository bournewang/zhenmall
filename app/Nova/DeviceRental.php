<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Pdmfc\NovaFields\ActionButton;

class DeviceRental extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\DeviceRental::class;
    public static function label()
    {
        return __('DeviceRental');
    }
    public static function group()
    {
        return __("Chain Store");
    }
    public static function icon()
    {
        return view("nova::svg.collection");
    }
//    public static function availableForNavigation(Request $request): bool
//    {
//        return true;
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
            BelongsTo::make(__('Store'), 'store', Store::class)->rules('required')->withoutTrashed()->exceptOnForms(),
            BelongsTo::make(__('Clerk'), 'user', Clerk::class)->rules('required')->exceptOnForms(),
            BelongsTo::make(__('Customer'), 'customer', Customer::class)->rules('required'),
//            Text::make(__('Card No'), 'card_no')->rules('required'),
            Currency::make(__('Deposit Price'), 'deposit_price')->currency('CNY')->rules('required'),
            Currency::make(__('Rental Price'), 'paid_price')->currency('CNY')->rules('required'),
            Select::make(__('Status'), 'status')->options(\App\Models\DeviceRental::statusOptions())->displayUsingLabels()->rules('required')->exceptOnForms(),
            Text::make(__('Comment'), 'comment')->hideFromIndex(),
            new Panel(__('Validity Period'), [
                Select::make(__('Validity Type'), 'validity_type')->options(\App\Models\DeviceRental::periodOptions())->displayUsingLabels()->onlyOnForms()->rules('required'),
                Number::make(__('Validity Period'), 'validity_period')->onlyOnForms()->rules('required'),
                Date::make(__('Validity Start'), 'validity_start')->onlyOnForms()->nullable(),
                Date::make(__('Validity To'), 'validity_to')->onlyOnForms()->nullable(),
                Text::make(__('Validity Period'))->displayUsing(function(){
                    return $this->validity_period . $this->periodLabel(). " (".$this->validity_start->toDateString() .' ~ '. $this->validity_to->toDateString().")";
                })->exceptOnForms()
            ]),
//            ActionButton::make(__('WriteOff'))->action($this->validity_type == \App\Models\MembershipCard::ACCOUNT ? WriteOff::class : null, $this->id)->text(__('Write Off'))->buttonColor("var(--danger)")
//                ->readonly(function(){return $this->validity_type != \App\Models\MembershipCard::ACCOUNT;})
//                ->withMeta(['color' => 'green'])
//            ,
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
