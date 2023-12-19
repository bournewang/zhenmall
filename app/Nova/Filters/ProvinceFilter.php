<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use App\Models\Province;
use AwesomeNova\Filters\DependentFilter;

class ProvinceFilter extends DependentFilter
{
    public $name = '省份';
    public $attribute = 'province_id';

    public function options(Request $request, array $filters = [])
    {
        return Province::pluck('name', 'id');
    }
}
