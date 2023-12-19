<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\PurchaseOrder;
use App\Models\Logistic;
use App\Models\LogisticProgress;

class LogisticController extends ApiBaseController
{    
    public function notify(Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        \Log::debug($request->all());
        if ($str = $request->input('result')) {
            $res = json_decode($str);
            $com = $res->expSpellName;
            $nu = $res->mailNo;
            // find logistic id
            if (!$log = Logistic::where('code', $com)->first()) {
                \Log::error("logistic $com not exists");
                return "logistic $com not exists";
            }
            if ($model = Order::where('logistic_id', $log->id)->where('waybill_number', $nu)->first()) {
                // \Log::error("order with logistic/waybill_number $com/$nu not exists");
            }elseif ($model = PurchaseOrder::where('logistic_id', $log->id)->where('waybill_number', $nu)->first() ) {
                
            }else{
                return "(purchase)order with logistic/waybill_number $com/$nu not exists";
            }
            
            $data = json_decode(json_encode($res), 1);
            $data['order_type'] = get_class($model);
            $data['order_id'] = $model->id;
            $data['updated_at'] = $data['updateStr'];
            // $data['data'] = json_encode($data['data']);
            unset($data['updateStr']);
            \Log::debug($data);
            if (!$model->logisticProgress) {
                LogisticProgress::create($data);
            }else{
                $model->logisticProgress->update($data);
            }
            $model->update(['ship_status' => $data['status']]);
            
            echo json_encode(["success" => true]);
        }
    }
}