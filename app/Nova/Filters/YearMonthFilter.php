<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Carbon\Carbon;

class YearMonthFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    public function name()
    {
        return __('Month');
    }
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        $range = explode('-', $value);
        return $query->where('year', intval($range[0]))->where('month', intval($range[1]));
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        $index = [];
        $i = 0;
        do {
            $month = Carbon::today()->subMonth($i)->format('Y-m');
            $index[$month] = $month;
        }while ($i++ < 12);
        return $index;
    }
}
