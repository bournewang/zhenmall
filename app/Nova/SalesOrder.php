<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Whitecube\NovaFlexibleContent\Flexible;
use Laravel\Nova\Http\Requests\NovaRequest;
use NovaAttachMany\AttachMany;
// use Pdmfc\NovaFields\ActionButton;
// use Jubeki\Nova\Cards\Linkable\LinkableRouter;
class SalesOrder extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\SalesOrder::class;
    public static function label()
    {
        return __('SalesOrder');
    }
    public static function group()
    {
        return __("Chain Store");
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
    public static $title = 'order_no';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'order_no'
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
            Text::make(__('Order No'), 'order_no'),
            Text::make(__('Store'))->displayUsing(function(){return $this->store->name ?? null;})->exceptOnForms(),
            Text::make(__('User'))->displayUsing(function(){return $this->user->name ?? ($this->user->nickname ?? null);})->exceptOnForms(),
            BelongsTo::make(__('Customer'), 'customer', Customer::class)->searchable(),//->displayUsing(function(){return $this->user->name ?? ($this->user->nickname ?? null);}),
            Text::make(__('Total Quantity'), 'total_quantity'),
            $this->money(__('Total Price'), 'total_price'),
            $this->money(__('Paid Price'), 'paid_price'),
            Text::make(__('Comment'), 'comment'),
            Text::make(__('Edit'), function(){
                return "<a class='inline-flex cursor-pointer text-70 hover:text-primary mr-3 has-tooltip' onclick='Nova.app.\$router.push({path: \"/sales-orders?id=$this->id&order_type=sales-orders\"})'>".'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="edit" role="presentation" class="fill-current"><path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path></svg>'."<a/>";
            })->asHtml()->canSeeWhen(__('EditSalesOrder'), $this),
            Flexible::make(__('Goods'), 'items')
                        ->addLayout(null, 'items', [
                            // Text::make(__('Goods'), 'goods'),//, Goods::class),
                            Select::make(__('Goods'), 'goods_id')->options(function(){return \App\Models\Goods::where('id','>',0)->pluck('name', 'id')->all();})->displayUsingLabels()->searchable(),
                            Text::make(__('Quantity'), 'quantity')
                        ])
                        ->button('+'),
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
            (new Actions\ImportSalesOrders)->canSeeWhen(__('AddSalesOrder'), $this)
        ];
    }
}
