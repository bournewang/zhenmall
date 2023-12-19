<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Address extends Resource
{
    public static $model = \App\Models\Address::class;
    public static $title = 'id';
    public static $with = ['user', 'store'];
    public static $search = [
        'id', 'contact', 'mobile'
    ];

    public static function label()
    {
        return __("Address");
    }
    public static function group()
    {
        return __("Mall");
    }
    public static function icon()
    {
        return view("nova::svg.".strtolower(explode('\\', self::class)[2]));
    }
//    public static function availableForNavigation(Request $request): bool
//    {
//        return false;
//    }
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
            BelongsTo::make(__('User'), 'user', User::class),
            Boolean::make(__('Default'), 'default'),
            $this->addressFields(),
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
            new Filters\ProvinceFilter,
            new Filters\CityFilter,
            new Filters\DistrictFilter,
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
