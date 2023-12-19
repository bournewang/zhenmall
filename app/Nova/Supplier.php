<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Comodolab\Nova\Fields\Help\Help;
// use Eminiarts\Tabs\Tabs;
// use Eminiarts\Tabs\Tab;
// use Eminiarts\Tabs\TabsOnEdit;
use NovaAjaxSelect\AjaxSelect;
use WesselPerik\StatusField\StatusField;
use App\Models\Province;
class Supplier extends Resource
{
    public static $model = \App\Models\Supplier::class;
    public static $title = 'name';
    public static $search = [
        'name', 
    ];
    public static function label()
    {
        return __('Supplier');
    }
    
    public static function group()
    {
        return __("Settings");
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
            // Tabs::make(__('Store') . __('Detail'), [
                // Tab::make('Info', [    
            // ID::make(),
            Text::make(__('Name'), 'name')->sortable()->rules('required', 'max:255'),
            Text::make(__('Company Name'), 'company_name')->rules('required', 'max:255'),
            Text::make(__('License No'), 'license_no')->rules('required', 'max:255'),
            Text::make(__('Account No'), 'account_no')->rules('required', 'max:255'),
            $this->addressFields(),
            StatusField::make(__('Status'), 'status')
                    ->values([
                        'inactive'  => $this->inactive == $this->status,
                        'pending'   => $this->pending == $this->status,
                        'active'    => $this->active == $this->status
                    ])
                    ->info($this->statusLabel())
                    ->exceptOnForms()     
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
            new Actions\Deactivate,
        ];
    }
}
