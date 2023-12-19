<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Device;
use App\Models\Setting;
use App\Models\Store;

class DevicesImport extends ModelImport
{
    public $pk = 'device_name';
    public $model = Device::class;

    protected function prepareData(array $row)
    {
        $types = array_flip(Setting::deviceTypes());
        return [
            'store_id'      => $this->parse(trim($row['门店']), Store::class),      
            'product_key'   => $types[trim($row['设备类型'])] ?? null,
            'device_name'   => trim($row['设备编号']),
            'status'        => 1  
        ];
    }
}
