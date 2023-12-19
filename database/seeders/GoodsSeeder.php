<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Goods;
use App\Imports\GoodsImport;
use App\Imports\GoodsImagesImport;
use Maatwebsite\Excel\Facades\Excel;

class GoodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // if ($data = (config('seed')['goods'] ?? null)) {
        //     foreach ($data as $item) {
        //         $c = Goods::create($item);
        //         echo "create goods $c->name \n";
        //     }
        // }
        Excel::import(new GoodsImport, './database/goods.xlsx');
        (new GoodsImagesImport('./database/goods-images.zip'))->import();
        
    }
}
