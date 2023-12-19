<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Anaseqal\NovaImport\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Text;

// use App\Imports\PurchaseOrdersImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportPurchaseOrders extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;
    public $withoutConfirmation = true;
    
    public function shownOnIndex()
    {
        return true;
    }
    public function shownOnDetail()
    {
        return false;
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name() {
        return __('New') . __('PurchaseOrder');
    }

    /**
     * @return string
     */
    public function uriKey() :string
    {
        return 'import-purchase-orders';
    }

    /**
     * Perform the action.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @return mixed
     */
    public function handle(ActionFields $fields)
    {
        return Action::push("/sales-orders?order_type=purchase-orders");
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