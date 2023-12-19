<?php

namespace App\Nova\Metrics;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Carbon\Carbon;

class BonusPartition extends Partition
{
    public function name()
    {
        return __('Bonus Partition');
    }
    public function calculate(NovaRequest $request)
    {
        // $start = date('Y-m-d', strtotime("first day of ".date('Y-m')));
        // $end = date('Y-m-d', strtotime("last day of ".date('Y-m'))) . ' 23:59:59';
        $start = date('Y-m-d', strtotime("first day of 2021-07"));
        $end = date('Y-m-d', strtotime("last day of 2021-07")) . ' 23:59:59';
        
        $store = $request->user()->store;
        // $range = Carbon::today()->subDay($request->input('range', 30));
        $revenues = $store->revenues()->where('start', '>=', $start)->where('end', '<=', $end);
        $retail_income  = $revenues->sum('retail_income');
        $level_bonus    = $revenues->sum('level_bonus');
        $leader_bonus   = $revenues->sum('leader_bonus');
        
        return $this->result([
            __('Retail Income') => $retail_income,
            __('Level Bonus') => $level_bonus,
            __('Leader Bonus')  => $leader_bonus
        ])->colors([
            __('Retail Income') => 'var(--info)',
            __('Level Bonus') => 'var(--warning)',
            __('Leader Bonus')  => 'var(--danger)'
        ]);
    }

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
        return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'bonus-partition';
    }
}
