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
use App\Models\LogisticProgress;
use App\Helpers\ExpressHelper;
class LogisticQuery extends Action
{
    use InteractsWithQueue, Queueable;
    public function name()
    {
        return __('Logistic Query');
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
        foreach ($models as $model) {
            if (!$model->logistic || !$model->waybill_number) {
                continue;
            }
            $res = ExpressHelper::query($model->logistic->code, $model->waybill_number);
            if (!$res->flag) { // success
                continue;
            }
            $data = json_decode(json_encode($res), 1);
            $data['order_id'] = $model->id;
            $data['updated_at'] = $data['updateStr'];
            // $data['data'] = json_encode($data['data']);
            unset($data['updateStr']);
            \Log::debug($data);
            if (!$model->logisticProgress) {
                LogisticProgress::create($data);
            }else{
                $model->logisticProgress->update($data);
            }
            $model->update(['ship_status' => $data['status']]);
            // $model->deliver($fields->logistic_id, $fields->waybill_number);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
