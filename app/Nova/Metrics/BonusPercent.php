<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Carbon\Carbon;

class BonusPercent extends Value
{
    public function name()
    {
        return __('Bonus Ratio');
    }
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $store = $request->user()->store;
        $range = $request->input('range');//Carbon::today()->subDay($request->input('range'));
        $start = date('Y-m-d', strtotime("first day of $range"));
        $end = date('Y-m-d', strtotime("last day of $range")) . ' 23:59:59';
        $order_amount = $store->orders()->whereBetween('created_at', [$start, $end])->sum('amount');
        $revenue_amount =$store->revenues()->where('start', '>=', $start)->where('end', '<=', $end)->sum('total_income');
        \Log::debug("order: $order_amount, revenue: $revenue_amount");
        if ($order_amount == 0) {
            return $this->result(0);
        }
        return $this->result($revenue_amount * 100/$order_amount)->suffix('%')->previous(30);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        $index = [];
        $i = 0;
        do {
            $month = Carbon::today()->subMonth($i)->format('Y-m');
            $index[$month] = $month; //[$month->startOfMonth()->format('Y-m-d'), $month->endOfMonth()->format('Y-m-d')];
        }while ($i++ < 3);
        return $index;
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        return null;//now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'bonus-percent';
    }
}
