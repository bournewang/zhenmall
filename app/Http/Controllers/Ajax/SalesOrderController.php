<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Goods;
use Auth;

class SalesOrderController extends AppBaseController
{
    public function create(Request $request)
    {
        $user = Auth::user();
        $total_quantity = 0;
        $total_price = 0;
        $items = [];
        foreach ($request->input('items') as $row) {
            $item = $row['attributes'];
            if ($good = Goods::find($item['goods_id'] ?? null)) {
                $quantity = ($item['quantity'] ?? 0);
                if ($quantity > 0) {
                    $total_quantity += $quantity;
                    $total_price += $good->price * $quantity;
                    
                    $items[] = $row;
                }
            }
        }
        $data = array_merge($request->all(), [
            'store_id' => $user->store_id,
            'user_id' => $user->id,
            'total_quantity' => $total_quantity,
            'total_price' => $total_price,
            'items' => $items
        ]);
        \Log::debug($data);
        if ($id = $request->input('id')) {
            if ($sales_order = SalesOrder::find($id))
                $sales_order->update($data);
        }else{
            $sales_order = SalesOrder::create($data);
        }
        
        return $this->sendResponse(['id' => $sales_order->id]);
    }
    
    public function show($id)
    {
        if (!$order = SalesOrder::find($id)) {
            return $this->sendError("no sales order found");
        }
        return $this->sendResponse($order->detail());
    }    
}
