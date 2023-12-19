<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use App\Models\Store;
use App\Models\Device;
use App\Helpers\DeviceHelper;
use Auth;

class AddToCart extends Action
{
    use InteractsWithQueue, Queueable;
    public function name()
    {
        return __('Add To Cart');
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
        $cart = Auth::user()->getCart();
        foreach ($models as $model) {
            $cart->add($model, $fields->quantity);
        }
        return Action::message(__("Add to cart success"));
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make(__('Quantity'), 'quantity')->required()->default(1)
        ];
    }
}
