<?php

namespace App\Nova;

use Illuminate\Http\Request;
// use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
// use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
// use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Helpers\StoreHelper;

class Banner extends Resource
{
    public static $model = \App\Models\Banner::class;
    public static $title = 'title';
    public static $with = ['store'];
    public static $search = [
        'id', 'title', 
    ];

    public static function group()
    {
        return __("Mall");
    }
    public static function label()
    {
        return __('Banner');
    }
  
    public static function icon()
    {
        return view("nova::svg.".strtolower(explode('\\', self::class)[2]));
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
            ID::make(),
            // BelongsTo::make(__('Store'), 'store', Store::class)->nullable(),
            BelongsTo::make(__('Goods'), 'goods', Goods::class)->nullable(),
            Text::make(__('Title'), 'title')->nullable(),//->rules('required', 'max:255'),
            Text::make(__('Link'), 'link')->nullable(),
            // Image::make(__('Image'), 'image')->nullable(),//->preview(function($val){return $val;}),
            $this->mediaField(__('Image'), 'main'),
            Boolean::make(__('Status'), 'status')
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
        return [];
    }
}
