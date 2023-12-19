<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use WesselPerik\StatusField\StatusField;
class Device extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Device::class;
    public static function label()
    {
        return __('Device');
    }
    public static function group()
    {
        return __("Stock");
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
    public static $title = 'device_name';

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
            ID::make(__('ID'), 'id')->sortable(),
            BelongsTo::make(__('Store'), 'store', Store::class),
            // Text::make(__('Product Key'), 'product_key')
            Select::make(__('Device Type'), 'product_key')->options(\App\Models\Setting::deviceTypes())->displayUsingLabels(),
            Text::make(__('Device Name'), 'device_name'),
            StatusField::make(__('Status'), 'status')
                    ->values([
                        'inactive'  => !$this->status,
                        'active'    => $this->status
                    ])
                    ->info($this->statusLabel())
                    ->exceptOnForms(),
            HasMany::make(__('Service Order'), 'serviceOrders', ServiceOrder::class)
        ];
    }

    public function detachedFilters(Request $request)
    {
        return [
            new Filters\DeviceTypeFilter
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
        return [
            new Actions\ImportDevices,
            new Actions\Activate,
            new Actions\Deactivate
        ];
    }
}
