<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use WesselPerik\StatusField\StatusField;
class Health extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Health::class;
    public static function label()
    {
        return __('Expert Service');
    }
    public static function group()
    {
        return __("Expert");
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
    public static $title = 'id';

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
            // Text::make(__('Store'))->displayUsing(function(){return $this->store->name ?? null;})->exceptOnForms(),
            // Text::make(__('Customer'))->displayUsing(function(){return $this->user->name ?? ($this->user->nickname ?? null);}),//->exceptOnForms(),
            // Text::make(__('Expert'))->displayUsing(function(){return $this->expert->name ?? null;}),//->exceptOnForms(),
            BelongsTo::make(__('Store'), 'store', Store::class),
            BelongsTo::make(__('Customer'), 'user', User::class)->searchable(),
            BelongsTo::make(__('Expert'), 'expert', Expert::class),
            Trix::make(__('Detail'), 'detail'),
            Trix::make(__('Health Suggestion'), 'suggestion'),
            Select::make(__('Status'), 'status')->options(function(){return \App\Models\Health::statusOptions();})->onlyOnForms(),
            StatusField::make(__('Status'), 'status')
                    ->values([
                        'inactive'  => $this->status == \App\Models\Health::DENIED,
                        'pending'   => $this->status == \App\Models\Health::PENDING,
                        'active'    => $this->status == \App\Models\Health::REPLIED
                    ])
                    ->info($this->statusLabel())
                    ->exceptOnForms(),
            $this->mediaField(__('Main'), 'main'),
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
