<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;
use App\Models\User;
class UserTrend extends CountTrend
{
    protected $resource = User::class;

    public function uriKey()
    {
        return 'user-trend';
    }
    
    public function name()
    {
        return __('User') . __('Register'). __('Trend');
    }
}

