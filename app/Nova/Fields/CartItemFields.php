<?php

namespace App\Nova\Fields;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Currency;
class CartItemFields
{
    /**
     * Get the pivot fields for the relationship.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            Currency::make(__('Price'), 'price')->currency('CNY'),//->displayUsing(function($val){return moneyformat( $val);}),
            Text::make(__('Quantity'), 'quantity'),
            Currency::make(__('Subtotal'), 'subtotal')->currency('CNY')//->displayUsing(function($val){return moneyformat( $val);})
        ];
    }
}