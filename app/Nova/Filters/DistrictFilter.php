<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use App\Models\District;
use AwesomeNova\Filters\DependentFilter;

class DistrictFilter extends DependentFilter
{
    public $dependentOf = ['city_id'];
    public $name = '区';
    public $attribute = 'district_id';

    public function options(Request $request, array $filters = [])
    {
        return District::where('city_id', $filters['city_id'])
            ->pluck('name', 'id');
    }
}
