<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\User;

class SalesmenImport extends ModelImport
{
    public $pk = 'mobile';
    public $model = User::class;
    
    protected function prepareData(array $row)
    {
        $genders = array_flip(User::genderOptions());
        return [
            'name'      => $row['姓名'],
            'gender'    => $genders[$row['性别']] ?? null, 
            'mobile'    => $row['手机'],
            'type'      => User::SALESMAN
        ];
    }
}
