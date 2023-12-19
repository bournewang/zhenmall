<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class MallHelper
{
    static public function categories($args = []) 
    {
        if (env('APP_ENV') =='local')
            return json_decode(file_get_contents(base_path('database/categories.json')),1)['results'];
        return self::request('/getCategory', ['categoryName' => '', 'categoryId' => '']);
    }
    static public function goods($category_id = '', $goodsId = '', $keywords = '') 
    {
        if (env('APP_ENV') =='local')
            return json_decode(file_get_contents(base_path('database/goods.json')),1)['results'];
        return self::request('/getShopGoods', ['shopId' => config('mall.shop_id'), 'categoryId' => $category_id, 'goodsId' => $goodsId, 'goodsName' => $keywords, 'startTime' => '20100101', 'endTime' => '20301231']);
    }
    
    static public function request($url, $data)
    {
        // $sdf = Carbon::now()->format('YmdHis');
        $serviceTime = Carbon::now()->format('YmdHis');
        $userId = config('mall.user_id');
        $arr = [
            'userId' => $userId, 
            'serviceTime' => $serviceTime
        ];
        $url = config('mall.base_url') . $url . "?"  . http_build_query($arr);
        $string = "userId:" . $userId . "||serviceTime:" . $serviceTime . "||params:" . json_encode($data);
        $authorization = self::sign($string, config('mall.key'));
        $res = Http::withHeaders(['Authorization' => $authorization])->post($url, $data);
        // echo $res;
        \Log::debug($url);
        \Log::debug($data);
        \Log::debug('-------------');
        \Log::debug($res);
        if (!empty($res)) {
            $arr = json_decode($res, 1);
            return $arr['results'] ?? null;
        }
        return null;
    }

    static public function sign($content, $privateKey)
    {
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($privateKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";

        $key = openssl_get_privatekey($privateKey);
        openssl_sign($content, $signature, $key, "SHA256");
        openssl_free_key($key);
        $sign = base64_encode($signature);
        return $sign;
    }    
    
}