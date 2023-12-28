<?php
namespace App\Helpers;
// use EasyWeChat\Factory;
use Exception;

class WechatHelper
{
    static public function withdraw($withdraw)
    {
        $app = \EasyWeChat::payment();
        $res = $app->transfer->toBalance([
            'partner_trade_no' => $withdraw->id, // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
            'openid' => $withdraw->user->openid,
            'check_name' => 'NO_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
            're_user_name' => null, // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
            'amount' => $withdraw->amount * 100, // 企业付款金额，单位为分
            'desc' => __('Withdraw'), // 企业付款操作说明信息。必填
            'notify_url' => env('APP_URL')."/api/wechat/withdrawNotify"
        ]);


        //   'result_code' => 'SUCCESS',
        //   'return_code' => 'SUCCESS',
        if ($res['result_code'] != 'SUCCESS' || $res['return_code'] != 'SUCCESS'){
            throw new Exception($res['err_code_des'] ?? $res['return_msg']);
        }
    }
}
