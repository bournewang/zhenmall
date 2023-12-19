<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use App\Models\Logistic;

class Deliver extends Action
{
    use InteractsWithQueue, Queueable;
    public function name()
    {
        return __('Deliver');
    }
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        //
        if (!$logistic = Logistic::find($fields->logistic_id)) {
            return Action::danger("没有找到该物流");
        }
        foreach ($models as $model) {
            try{
                $model->deliver($logistic, $fields->waybill_number);
            }catch(\Exception $e) {
                \Log::error($e->getMessage());
            }
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            // BelongsTo::make(__('Logistic'), 'logistic', \App\Nova\Logistic::class)->required()->searchable(),
            Select::make(__('Logistic'), 'logistic_id')
                ->options(function(){
                    // FIXME, cache options;
                    return Logistic::options();
                })
                ->required()
                ->displayUsingLabels()
                ->searchable(),
            Text::make(__('Waybill Number'), 'waybill_number')->required()
        ];
    }
}
