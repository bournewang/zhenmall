<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use App\Models\Device;
use App\Models\Store;
use App\Helpers\DeviceHelper;
class Deactivate extends Action
{
    use InteractsWithQueue, Queueable;
    public function name()
    {
        return __('Make Inactive');
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
        \Log::debug(__CLASS__.'->'.__FUNCTION__. " count: ".$models->count());
        if ($models->first()::class == Store::class) {
            foreach ($models as $model) {
                \Log::debug("update status to ".$model->inactive);
                $model->update(['status' => $model->inactive]);
            }
        }elseif ($models->first()::class == Device::class) {
            $devices = $models->pluck('device_name')->all();
            $sn = implode(',', $devices);
            $res = DeviceHelper::statusChange($sn, 0);
            if ($res->status == 1) {
                foreach ($res->device_status as $item) {
                    $models->where('device_name', $item->devicename)->first()->update(['status' => $item->status]);
                }
                return Action::message($res->success);
            }else{
                return Action::danger($res->error_reason);
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
        return [];
    }
}
