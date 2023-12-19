<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Pdmfc\NovaFields\ActionButton;
use App\Helpers\StoreHelper;
use OptimistDigital\NovaDetachedFilters\NovaDetachedFilters;

class Goods extends Resource
{
    public static $model = \App\Models\Goods::class;
    public static $title = 'name';
    public static $with = ['category'];
    public static $search = [
        'name',
    ];

    public static function label()
    {
        return __('Goods');
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
        $rel = !!$request->input('viaRelationship');
        if ($rel) {
            return [
                Text::make(__('Name'), 'name')->sortable()
            ];
        }
        return [
            ID::make()->sortable(),
            BelongsTo::make(__('Category'), 'category', Category::class)->sortable()->rules('required'),
            BelongsTo::make(__('Supplier'), 'supplier', Supplier::class)->sortable(),
            Text::make(__('Name'), 'name')->sortable()->rules('required', 'max:255')
                    ->creationRules("unique:goods,name,NULL,id")
                    ->updateRules("unique:goods,name,{{resourceId}},id")
                    ,
            Text::make(__('Stock'), 'qty')->sortable()->rules('required', 'max:255'),
            Text::make(__('Brand'), 'brand')->sortable()->nullable()->hideFromIndex(),
            $this->money(__('Price Original'), 'price_ori')->sortable()->nullable(),
            $this->money(__('Price Sales'), 'price')->sortable()->nullable(),
            $this->money(__('Price Purchase'), 'price_purchase')->sortable()->nullable(),
            Select::make(__('Status'), 'status')->options((new \App\Models\Category)->statusOptions())->onlyOnForms(),
            Text::make(__('Status'))->displayUsing(function(){return $this->statusRichLabel();})->asHtml()->exceptOnForms(),
//            ActionButton::make(__('Add To Cart'))->action(Actions\AddToCart::class, $this->id)->text(__('Add To Cart')),
            $this->mediaField(__('Main'), 'main'),
            $this->mediaField(__('Detail'), 'detail'),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function detachedFilters(Request $request)
    {
        return [
            new Filters\CategoryFilter,
            new Filters\SupplierFilter
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
        if ($request->input('viaRelationship')) return [];
        return [
            new Actions\Recommend,
            new Actions\OnShelf,
            new Actions\OffShelf,
//            $this->actionButton(new Actions\AddToCart, 'Ordering', $request),
            (new Actions\ImportGoods)->canSeeWhen(__('Create').__('Goods'), $this)
        ];
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query;
    }
}
