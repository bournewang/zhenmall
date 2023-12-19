<?php

namespace App\Nova\Fields;

use Laravel\Nova\Fields\Text;
use App\Models\User;
class SalesFields
{
    /**
     * Get the pivot fields for the relationship.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            Text::make(__('Superior'), 'superior_id')->displayUsing(function($val){return User::find($val)->name ?? null;}),
            Text::make(__('Level'), 'level'),
            Text::make(__('Sharing Ratio'), 'sharing')//->displayUsing(function($val){return moneyformat( $val);})
        ];
    }
}