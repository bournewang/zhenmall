<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Http;
use App\Models\Category;
use App\Models\Goods;
use Carbon\Carbon;

class SyncHelper
{
    // [
    //   "categoryId" => 57,
    //   "categoryName" => "女性健康",
    //   "pid" => 6,
    // ],
    // [
    //   "categoryId" => 58,
    //   "categoryName" => "男士必备",
    //   "pid" => 6,
    // ],    
    static public function categories()
    {
        $items = MallHelper::categories();
        foreach ($items as $item) {
            if ($id = ($item['categoryId'] ?? null) ) {
                $data = [
                    'id' => $id,
                    'name' => $item['categoryName'],
                    'pid' => $item['pid'],
                ];
                if ($cat = Category::find($id)) {
                    $cat->update($data);
                    \Log::debug("update category $id $cat->name");
                }else{
                    $cat = Category::create($data);
                    \Log::debug("create category $id $cat->name");
                }
            }
        }
    }
    
    
    // "shopId" => "aGpINm0zQjBSdDZDc0NqL25QbnV3QT09",
    // "goodsId" => "1505",
    // "name" => "预售商品",
    // "qty" => "0",
    // "categoryId" => 55,
    // "type" => "2",
    // "brand" => "",
    // "saleFlag" => "1",
    // "price" => "100.00",
    // "img" => "https://image.ln-sx.com/images/27/1620629736775.jpg",
    // "pv" => 1,
    // "saleCount" => 0,
    // "customs_id" => "",    
    static public function goods()
    {
        $items = MallHelper::goods();
        $i = 0;
        $fields = [
            'goodsId' => 'id',
            'goodsName' => 'name', 
            'goodsType' => 'type',
            'goodsImg' => 'img',
            'goodsPrice' => 'price',
            'goodsDetail' => 'detail',
            'categoryId' => 'category_id'
        ];
        foreach ($items as $item) {
            if ($id = ($item['goodsId'] ?? null) ) {
                foreach ($fields as $a => $n) {
                    $item[$n] = $item[$a] ?? null;
                    unset($item[$a]);
                }
                
                // fetch image
                if ($url = ($item['img'] ?? null)) {
                    $filename = last(explode('/', $url));
                    $imgUrl = "goods/".$item['category_id']."/".$filename;
                    $path = \Storage::disk('public')->path($imgUrl);
                    if (!file_exists($path)) {
                        $res = Http::get($url);
                        if ($res->ok()) {
                            \Storage::disk('public')->put($imgUrl, $res->body());
                            // $arr = ImageHelper::resize($path);
                            \Log::debug("put image to $url");
                        }
                    }
                    $arr = ImageHelper::resize($imgUrl);
                    $item['img_s'] = $arr['thumb'] ?? $imgUrl;
                    $item['img_m'] = $arr['medium'] ?? $imgUrl;
                    $item['img'] = $arr['large'] ?? $imgUrl;
                    \Log::debug("imageHelper::resize $path");
                    \Log::debug($arr);
                }
                
                if ($cat = Goods::find($id)) {
                    $cat->update($item);
                    \Log::debug("update goods $id $cat->name");
                }else{
                    $cat = Goods::create($item);
                    \Log::debug("create goods $id $cat->name");
                }
            }
        }
    }
}