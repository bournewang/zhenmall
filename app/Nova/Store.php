<?php

namespace App\Nova;

use App\Models\Province;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Panel;
use OptimistDigital\NovaSimpleRepeatable\SimpleRepeatable;
use WesselPerik\StatusField\StatusField;
use NovaAjaxSelect\AjaxSelect;
class Store extends Resource
{
    public static $model = \App\Models\Store::class;
    public static $title = 'name';
    public static $search = [
        'name',
    ];
    public static function label()
    {
        return __('Store');
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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $user = $request->user();
        return [
            Text::make(__('Store Name'), 'name')->sortable()->rules('required', 'max:255'),
            Text::make(__('Company Name'), 'company_name')->rules('required', 'max:255'),
            Text::make(__('License No'), 'license_no')->rules('required', 'max:255'),
            Text::make(__('Account No'), 'account_no')->rules('required', 'max:255'),
//            $this->addressFields(),
            $this->mediaField(__('Contract'), 'contract'),
            $this->mediaField(__('License'), 'license'),
            $this->mediaField(__('Photo'), 'photo'),
            BelongsTo::make(__('Manager'), 'manager', User::class)->searchable()->nullable(),
            BelongsTo::make(__('Vice Manager'), 'viceManager', User::class)->searchable()->nullable(),
            // Select::make(__('Status'), 'status')->options((new \App\Models\Store)->statusOptions())->onlyOnForms(),
            StatusField::make(__('Status'), 'status')
                    ->values([
                        'inactive'  => $this->inactive == $this->status,
                        'pending'   => $this->pending == $this->status,
                        'active'    => $this->active == $this->status
                    ])
                    ->info($this->statusLabel())
                    ->exceptOnForms(),
            SimpleRepeatable::make(__('Profit Sharing'), 'profit_sharing', [
                Select::make(__('Role'), 'role')->options(\App\Models\User::sharingRoleOptions())->displayUsingLabels(),
                Number::make(__('Sharing Ratio'), 'sharing_ratio')
                    ->min(1)->max(100)
                    ->displayUsing(function($v){return $v.'%';})
                    ->help('填写1-100之间的整数，最小为1，最大为100')
            ]),

            new Panel(__('Address'), [
                Select::make(__('Province'), 'province_id')
                    ->options(Province::pluck('name', 'id')->all())
                    ->displayUsingLabels()
                    ->onlyOnForms(),

                AjaxSelect::make(__('City'), 'city_id')
                    ->get('/api/provinces/{province_id}/cities')
                    ->parent('province_id')
                    ->onlyOnForms(),

                AjaxSelect::make(__('District'), 'district_id')
                    ->get('/api/cities/{city_id}/districts')
                    ->parent('city_id')
                    ->onlyOnForms(),

                Text::make(__('Street'), 'street')->onlyOnForms(),
                Text::make(__('Manager').__('Name'), 'contact')->onlyOnForms()->nullable(),
                Text::make(__('Manager').__('Mobile'), 'mobile')->onlyOnForms()->nullable(),
                Text::make(__('Vice Manager').__('Name'), 'vice_contact')->onlyOnForms()->nullable(),
                Text::make(__('Vice Manager').__('Mobile'), 'vice_mobile')->onlyOnForms()->nullable(),

                Text::make(__('Address'), 'address')->displayUsing(function(){
                    return $this->display_address() . ($this->vice_mobile ? '('. __('Vice Manager').$this->vice_contact . $this->vice_mobile .')': '');
                })->onlyOnDetail(),
                Text::make(__('Address'), 'address')->displayUsing(function(){
                    $s = $this->display_address();
                    if (mb_strlen($s) > 15) {
                        return mb_substr($s, 0, 15) . '...';
                    }
                    return $s;
                })->onlyOnIndex(),
            ]),
            HasMany::make(__('Device'), 'devices', Device::class),
            HasMany::make(__('Service Order'), 'serviceOrders', ServiceOrder::class)
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
        return [
            new Actions\Activate,
            new Actions\Deactivate
        ];
    }
}
