<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\User;
// use App\Helpers\StoreHelper;

class StockController extends ApiBaseController
{
    /**
     * Stocks in a store(for manager/clerk and referer) 查看门店的商品库存
     *
     * @OA\Get(
     *  path="/api/stocks",
     *  tags={"Stock"},
     *  @OA\Parameter(name="perpage",       in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
     *  @OA\Parameter(name="page",          in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),  
     *  @OA\Response(response=200,description="successful operation")
     * )
     */       
    public function index(Request $request)
    {
        $this->checkStorePermit();
        
        $stocks = Stock::where('store_id', $this->user->store_id);
        $total = $stocks->count();
        $perpage = $request->input('perpage', 20);
        $data = [
            'titles' => ['index_no' => __('Index No'), 'img' => __('Image'), 'goods_name' => __('Goods').__('Name'), 'quantity' => __('Quantity')],
            'total' => $total,
            'pages' => ceil($total/$perpage),
            'page' => $request->input('page', 1),
            'items' => []
        ];
        if ($this->user->type == User::MANAGER) {
            $data['titles']['cost_label'] = __('Cost');
        }
        $stocks = $stocks->paginate($perpage);
        $i=1;
        foreach ($stocks as $review) {
            $d = $review->detail();
            $data['items'][] = array_merge($d, [
                'index_no' => $i++,
                'img' => $d['goods_img']
            ]);
        }
        return $this->sendResponse($data);
    }
    
}