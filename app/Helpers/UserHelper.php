<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Http;
use App\Models\Category;
use App\Models\Goods;
use Carbon\Carbon;

class UserHelper
{
    static public function qrcode($user)
    {
        $mpp = \EasyWeChat::miniProgram();
        $response = $mpp->app_code->getUnlimit("from=".$user->id, ['path' => '/pages/goods/list']);
        
        // 保存小程序码到文件
        $filename = null;
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $path  = "user/$user->id.jpg";
            $filename = $response->save(\Storage::disk('public')->path("user", "$user->id.jpg"));
            if (file_exists(\Storage::disk('public')->path($path))) {
                return $path;
            }
            // \Log::debug("save to $filename");
            // return $filename;
        }
        return null;
    }
    
}