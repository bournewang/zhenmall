<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Auth;
class PlaceOrder extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;
    
    public $withoutConfirmation = true;
    // public function showOnIndexToolbar(){return true;}
    /**
     * Get the displayable label of the button.
     *
     * @return string
     */
    public function name()
    {
        return __('Place Order');
    }

    /**
     * Perform the action.
     *
     * @param  ActionFields  $fields
     *
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $order = $model->submit($model->store);
        }        
        return Action::push('/resources/orders/'.$order->id);
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