<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Goods;
use App\Models\Store;
use App\Models\Cart;

class ConfigController extends ApiBaseController
{
    protected function get()
    {
        return $this->sendResponse([
            'mallName' => config('app.name')
        ]);
        $data =file_get_contents(base_path('config.json'));
        return $data;
    }
}