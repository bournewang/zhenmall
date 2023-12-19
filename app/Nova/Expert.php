<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Epartment\NovaDependencyContainer\HasDependencies;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
class Expert extends Resource
{
    use UserTrait;
    use HasDependencies;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Expert::class;
    public static function label()
    {
        return __('Expert');
    }
    public static function group()
    {
        return __("Expert");
    }
    public static $search = [
        'id', 'nickname', 'name', 'mobile',
    ];
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
        return array_merge($this->userFields($request), [
//            ID::make(__('ID'), 'id')->sortable(),
//            Text::make(__('Realname'), 'name')->sortable()->rules('required', 'max:255'),
//            Text::make(__('Gender'), 'gender'),
//            Text::make(__('Mobile'), 'mobile'),
//            Text::make(__('Wechat Account'), 'wechat'),
            Select::make(__('Bank'), 'bank_key')
                ->options(\App\Models\Setting::first()->banks)
                ->displayUsing(function(){
                    if ($this->bank_key == 'OTHER') {
                        return $this->bank_name;
                    }else{
                        return \App\Models\Setting::first()->banks[$this->bank_key] ?? null;
                    }
                }),
            NovaDependencyContainer::make([
                Text::make(__('Bank Name'), 'bank_name'),
            ])->dependsOn('bank_key', 'OTHER')->onlyOnForms(),
            Text::make(__('Account No'), 'account_no'),

        ]);
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

    public static function indexQuery(NovaRequest $request, $query)
    {
        return parent::indexQuery($request, $query)->where('type', \App\Models\Salesman::EXPERT);
    }
}
