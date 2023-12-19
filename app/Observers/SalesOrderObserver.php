<?php

namespace App\Observers;
use App\Models\SalesOrder;

class SalesOrderObserver
{
    //
    public function created(SalesOrder $order)
    {
        $order->export();
    }
    
}
