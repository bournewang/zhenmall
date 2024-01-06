<?php
namespace App\Helpers;
use App\Models\Store;
use App\Models\Order;
use App\Models\User;
use App\Exceptions\ApiException;
use Carbon\Carbon;
use updc\bean\Algorithm;
use updc\bean\GateWayRequest;

require(base_path("updc/init.php"));
use updc\bean\Client as UnionPayClient;
// use updc\UnionPay;
// require(base_path("updc/UnionPay.php"));

class UnionPayHelper
{
    static public function getClient()
    {
        // return UnionPay::getClient();
        $client = new UnionPayClient();
        $client->__set("appId", env('UNION_PAY_APPID'));
        $client->__set("url", env('UNION_PAY_URL'));
        $client->__set("privateKeyPassword", env("UNION_PAY_PRIVATE_KEYPASS"));
        // echo "pasword: ".env("UNION_PAY_PRIVATE_KEYPASS")."\n";
        $client->__set("privateKey", env("UNION_PAY_PRIVATE_KEY"));
        $client->__set("publicKey", env("UNION_PAY_GATEWAY_KEY"));
        $client->__set("isHttps", true);
        return $client;
    }
    static public function createOrder($order)
    {
        // $bizContentJson='{"sndDt":"20200909093637","busiMerNo":"100000000000002","msgBody":{"trxTtlAmt":"1","trxCurr":"CNY","subject":"条码支付","ordrDesc":"支付","oprId":"9527XX","trxTrmNo":"1000001","trxTrmIp":"127.0.0.1","timeoutExpress":"5m","buyerInf":{"bankCode":"00000000","acctType":"0","limitCreditPay":"0"},"sceneInf":{"storeId":"10006","storeNm":"一间小店","areaCode":"5810","address":"广州"},"goodsDtl":[{"gdsId":"1001","gdsPlfId":"10009","gdsNm":"一件商品","gdsCnt":"1","gdsPrice":"1000","gdsCstPrice":"1000","gdsCtgy":"10010","gdsDesc":"商品描述","showUrl":"127.0.0.1"}],"trxType":"NATIVE","trxTrmInf":{"trxTrmType":"08","trxTrmNo":"10086","trxTrmIp":"10.10.0.1","trxTrmMac":"F0E1D2C3B4A5","trxTrmImei":"10002","trxTrmImsi":"10003","trxTrmIccid":"10001","trxTrmWifiMac":"10004"},"rskInf":{"devId":"10000","devcMode":"设备型号XXX","devcLanguage":"JAV","devcLocation":"116.360207","devcNumber":"10086","devcSimNumber":"2"},"merOrdrNo":"10000000000000220200909093637001","bizFunc":"111011","busiId":"00250005","remark":"remark","remark1":"remark1","remark2":"remark2","remark3":"remark3","remark4":""},"notifyUrl":"http://192.168.180.116:8080/gnete-upbc-merdemo/resultNotifyServlet","remark":"商户联调测试"}';
        $bizContentJson = json_encode([
                "sndDt" => Carbon::now()->format('YmdHis'),
                "busiMerNo" => env('UNION_PAY_MERCHANT_NO'),
                "notifyUrl" => env('APP_URL').'/api/unionpay/notify', // "商户接收支付结果通知的接口地址",
                "remark" => "", //"备注字段，可以填商户自己的订单号，和 merOrdrNo 进行对应",
                "msgBody" => [
                    "merOrdrNo" => $order->order_no,
                    "trxTtlAmt" => $order->amount * 100,
                    "busiId" => "00250007",
                    "bizFunc" => "111011",
                    "subject" => $order->goods->first()->name, //"订单标题",
                    "ordrDesc" => $order->goods->first()->name, //"订单描述",
                    "timeoutExpress" => "30m", //"允许的最晚付款时间，逾期将关闭交易",
                    "trxType" => "JSAPI",
                    "buyerInf" => [
                        "bankCode" => "00060606",
                        "openId" => $order->user->openid
                    ]
                ]
            ]);
        //请求客户端对象
		$client=self::getClient();
		//设置加密算法类型
		$client->__set("algorithm",Algorithm::SHA256withRSA);

		//设置公共请求参数对象
		$gateWayRequest=new GateWayRequest();
		//服务名
		$gateWayRequest->__set("apiInterfaceId","gnete.upbc.code.trade");
		//方法名
		$gateWayRequest->__set("methodName","precreate");
		//版本号
		$gateWayRequest->__set("version","1.0.6");
		$gateWayRequest->__set("bizContent",$bizContentJson);

		$client->__set("gateWayRequest",$gateWayRequest);

		 $flag=$client->apiRequest();
		 if($flag!=1){
            throw new ApiException("请求发送成功，响应验签失败");
		 }

		 // echo "请求主体：\n";
		 // var_dump($client->__get("requestBody"));

		 // echo "响应主体：\n";
		 // var_dump($client->__get("responseBody"));
         $response = json_decode($client->__get("responseBody"));
         // return $this->sendResponse($response->msg);
         // $response->wcPayData
        //  "appId": "wx82eb617caf833e3c",
        // "pkg": "prepay_id=wx2214315155197926aba7c05938a0970000",
        // "signType": "RSA",
        // "paySign": "s1jRGvSfmlVzYumu7IZAKBRB+lqVbnACjbx0OGeHtA+4P7IY0JW7Vsj/tjOWoA14Fvskn+cmO7 fZZmURtD/v7aRgjRA3qt9EszJCu0iEnN9+U5Vd0UCLCwPdASOVYsFJCwhbxHq2PexRgl22qZUo W5x3um81rjI16+ywbvTjBc0d7327f2hs+FdjoXiI3f/u8289GULt4sGLObkwdaI/KkpDljG/rxpHbDd9P JhtI6O8s1aSL1nMm1Zsi7keJdokdCS9+2d1YT1speawZWMyFBoIWqnYUb/MafCzEmG2z0ddEz1b xSgJo2LubEfrhihfSveBsbLPnaKDnueycZ3Ndg\u003d\u003d",
        // "nonceStr": "0d09221d91424f2988c39f0ed7d91358",
        // "prepayId": "wx2214315155197926aba7c05938a0970000",
        // "timeStamp": "1603348311"
         return 0;

    }
}
