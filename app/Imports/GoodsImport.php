<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Goods;
use App\Models\Category;
// use App\Models\Supplier;

class GoodsImport extends ModelImport
{
    public $pk = 'name';
    public $model = Goods::class;

    protected function prepareData(array $row)
    {
        $status = array_flip((new Goods)->statusOptions());
        return [
            'name'          => $row['名称'],
            'category_id'   => $this->parse($row['分类'], Category::class),
            // 'supplier_id'   => $this->parse($row['供应商'], Supplier::class),
            'brand'         => $row['品牌'],
            'price'         => $row['价格'],
            'price_ori'     => $row['原价'],
            'price_purchase' => $row['采购价'],
            'qty'           => $row['库存'],
            'detail'        => $row['详情'],
            'status'        => $status[$row['状态']] ?? null,
        ];
    }
}
