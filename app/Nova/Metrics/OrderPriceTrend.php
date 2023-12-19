<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use App\Models\Order;
class OrderPriceTrend extends SumTrend
{
    protected $resource = Order::class;
    protected $sum_field = 'amount';

    public function uriKey()
    {
        return 'order-price-trend';
    }
    
    public function name()
    {
        return __('Ordering') . __('Amount'). __('Trend');
    }
}
