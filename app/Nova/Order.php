<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Panel;
use Laravel\Nova\Http\Requests\NovaRequest;
use Pdmfc\NovaFields\ActionButton;

class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Order::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'order_no';

    public static function label()
    {
        return __("Order");
    }

    public static function group()
    {
        return __("Mall");
    }

    public static function icon()
    {
        return view("nova::svg.".strtolower(explode('\\', self::class)[2]));
    }
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
            // ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('Store'), 'store', Store::class),
            BelongsTo::make(__('User'), 'user', User::class),
            // Text::make(__('Order No'), 'order_no'),
            Text::make(__('Contact'), 'contact'),
            Text::make(__('Mobile'), 'mobile'),
            $this->addressFields(),
            $this->money(__('Amount'), 'amount'),
            Select::make(__('Status'), 'status')->options(\App\Models\Order::statusOptions())->displayUsingLabels(),
            ActionButton::make('')->action((new Actions\Deliver), $this->id)
                ->canSee(function()use($request){return $this->status == \App\Models\Order::PAID && $request->user()->can(__('Deliver'));})
                ->text(__('Deliver'))
                ->onlyOnDetail(),
            Panel::make(__('Logistic'), [
                // BelongsTo::make(__('Logistic'), 'logistic', Logistic::class)->nullable(),
                // Text::make(__('Waybill Number'), 'waybill_number')->onlyOnDetail(),
                Select::make(__('Ship Status'), 'ship_status')->options(\App\Models\LogisticProgress::statusOptions())->displayUsingLabels(),
                $this->editorField(__('Logistic Progress'), 'logisticProgress')->displayUsing(function(){
                    return !$this->logisticProgress ? null : $this->logisticProgress->detail();
                })->onlyOnDetail()
            ]),
            $this->datetime(),

            HasOne::make(__('Review'), 'review', Review::class),
            BelongsToMany::make(__('Goods'), 'goods', Goods::class)->fields(new Fields\CartItemFields)
        ];
    }

    public function detachedFilters(Request $request)
    {
        return [
            new Filters\ProvinceFilter,
            new Filters\CityFilter,
            new Filters\DistrictFilter,
            new Filters\OrderStatusFilter
        ];
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
        return [
            (new Actions\Deliver)
                    ->canSee(function()use($request) {
                        $can = $request->user()->can(__('Deliver'));
                        return $request->getMethod() == 'POST' ? $can :
                            ($can && $this->status == \App\Models\Order::PAID);})
                    ->canRun(function()use($request) {
                        return $request->user()->can(__('Deliver'));
                    }),
            // new Actions\LogisticQuery
        ];
    }
}
