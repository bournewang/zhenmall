<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Pdmfc\NovaFields\ActionButton;
use Gate;
class Cart extends Resource
{
    public static $model = \App\Models\Cart::class;
    public static $title = 'id';
    public static $with = ['user'];
    public static $search = [
        'id',
    ];
    
    public static function label()
    {
        return __("Cart");
    }
    public static function group()
    {
        return __("Mall");
    }
    public static function icon()
    {
        return view("nova::svg.".strtolower(explode('\\', self::class)[2]));
    }
    
    public static function availableForNavigation(Request $request): bool
    {
        return Gate::allows('viewIndex', \App\Models\User::class);
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
            // ID::make(__('ID'), 'id')->sortable(),
            // BelongsTo::make(__('User'), 'user', Customer::class),
            Text::make(__('Store'))->displayUsing(function(){return $this->store->name ?? null;}),
            Text::make(__('User'))->displayUsing(function(){return $this->user->name ?? ($this->user->nickname ?? null);}),
            Text::make(__('Total Quantity'), 'total_quantity'),
            $this->money(__('Total Price'), 'total_price'),
            Text::make(__('Address'), 'address')->displayUsing(function(){return $this->store->display_address();})->exceptOnForms(),
            ActionButton::make('')->action(Actions\PlaceOrder::class, $this->id)->text(__('Place Order'))->buttonColor("var(--danger)"),
            BelongsToMany::make(__('Goods'), 'goods', Goods::class)->fields(new Fields\CartItemFields)->actions(function(){return null;})
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
        return [
            $this->actionButton(new Actions\PlaceOrder, 'Ordering', $request),
        ];
    }
}
