<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\User;
use App\Models\Order;
use App\Models\ServiceOrder;
use Log;

class MappController extends ApiBaseController
{
    // service order callback, data example: 
    // array{
    // 'shopPhone' => '13700038882',
    // 'money' => 0.02,
    // 'customerPhone' => '13817635183',
    // 'orderTitle' => '负氧离子仪订单',
    // 'orderDetails' => '30分钟康复调理（5元）',
    // 'weChatPayNo' => NULL,
    // 'orderNo' => '20220107182553019392',
    // 'deviceName' => 'QYB_D0001',
    // 'timestamp' => '1641551205',
    // 'businessType' => '1',
    // 'unionid' => 'oGuhh6F9ny6jTSLUqunGD2wx9bC4',   
    // } 
    public function notify(Request $request)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        \Log::debug($request->all());
        if (!$unionid = $request->input('unionid')) {
            \Log::error("no unionid");
            return json_encode(['success' => false, 'message' => "no unionid"]);
        }
        if (!$user = User::where('unionid', $unionid)->first()) {
            $msg = "no user with unionid $unionid found";
            \Log::error($msg);
            return json_encode(['success' => false, 'message' => $msg]);
        }
        $device = Device::where('device_name', $request->input('deviceName'))->first();
        ServiceOrder::create([
            'store_id' => $device->store_id ?? null,
            'device_id' => $device->id ?? null,
            'user_id' => $user->id,
            'order_no' => $request->input('orderNo'),
            'title' => $request->input('orderTitle'),
            'detail' => $request->input('orderDetails'),
            'amount' => $request->input('money'),
            'status' => Order::PAID,
            'raw_data' => $request->all()
        ]);
        
        return json_encode(['success' => true]);
    }
}