<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
// use Laravel\Nova\Fields\Image;
// use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use App\Helpers\StoreHelper;

class Category extends Resource
{
    public static $model = \App\Models\Category::class;
    public static $title = 'name';
    public static $with = ['parent'];
    public static $search = [
        'id', 'name',
    ];

    public static function label()
    {
        return __('Category');
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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(),
            BelongsTo::make(__('Parent').__('Category'), 'parent', Category::class)->nullable(),
            Text::make(__('Name'), 'name')->rules('required', 'max:255'),
            Select::make(__('Status'), 'status')->options((new \App\Models\Category)->statusOptions())->onlyOnForms(),
            Text::make(__('Status'))->displayUsing(function(){return $this->statusRichLabel();})->asHtml()->exceptOnForms(),
            $this->mediaField(__('Image'), 'photo'),
            HasMany::make(__('Goods'), 'goods', Goods::class),
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
            new Actions\Recommend,
            new Actions\OnShelf,
            new Actions\OffShelf,
            // new Actions\Derecommend,
        ];
    }
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }
}
