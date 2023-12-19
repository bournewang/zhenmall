<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\Goods;
use Auth;

class PurchaseOrderController extends AppBaseController
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
                    $total_price += $good->price_purchase * $quantity;
                    
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
            if ($purchase_order = PurchaseOrder::find($id))
                $purchase_order->update($data);
        }else{
            $purchase_order = PurchaseOrder::create($data);
        }
        
        return $this->sendResponse(['id' => $purchase_order->id]);
    }
    
    public function show($id)
    {
        if (!$order = PurchaseOrder::find($id)) {
            return $this->sendError("no purchase order found");
        }
        return $this->sendResponse($order->detail());
    }    
}
