<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use App\Models\Order;
class OrderTrend extends CountTrend
{
    protected $resource = Order::class;

    public function uriKey()
    {
        return 'order-trend';
    }
    
    public function name()
    {
        return __('Ordering') . __('Trend');
    }
}
