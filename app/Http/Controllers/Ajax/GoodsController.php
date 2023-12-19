<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Goods;

class GoodsController extends AppBaseController
{
    public function index(Request $request)
    {
        $data = [];
        $goods = Goods::where('status', '!=', (new Goods)->off_shelf)->get();
        foreach ($goods as $good) {
            $data[$good->id] = $good->info();
        }
        return $this->sendResponse($data);
    }
    
    public function options()
    {
        // return $this->sendResponse(['a'=>'aaaa', 'b'=>'bbbb']);
        $data = Goods::where('status', '!=', (new Goods)->off_shelf)->select('id', 'name')->get()->toArray();
        return $this->sendResponse($data);
    }
}
