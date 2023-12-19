<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use App\Models\City;
use AwesomeNova\Filters\DependentFilter;

class CityFilter extends DependentFilter
{
    public $dependentOf = ['province_id'];
    public $name = '市';
    public $attribute = 'city_id';

    public function options(Request $request, array $filters = [])
    {
        return City::where('province_id', $filters['province_id'])
            ->pluck('name', 'id');
    }
}
