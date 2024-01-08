<?php

namespace App\Http\Controllers\API;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Order;

// require(base_path("updc/utils/CommonUtil.php"));
// require(base_path("updc/utils/CharacterUtil.php"));
// require(base_path("updc/bean/Algorithm.php"));
use updc\utils\CharacterUtil;
use updc\utils\CommonUtil;
use updc\bean\Algorithm;

class UnionPayController extends ApiBaseController
{
    public function notify(Request $request)
    {
        // header('Content-Type:text/html;charset=utf-8');
        // $publicKey="C:/Users/Dreamer/Desktop/project/phpDemo/gateway_public_dev.pem";
        $publicKey=base_path('cert/gateway_public_test.pem');
        $raw_post_data = file_get_contents('php://input', 'r');
        if(is_null($raw_post_data)){
            echo "响应信息为空";
            return;
        }

        if(strlen($raw_post_data)<=0) {
            echo "响应信息为空";
            return;
        }

        // var_dump("respMsg=".$raw_post_data);

        $index=strrpos($raw_post_data,"sign");
        $content=substr($raw_post_data,0,$index-2)."}";
        //var_dump("响应签名原文：".$content);
        $resArr=json_decode($raw_post_data, true);
        $sign=$resArr["sign"];

        // check sign
        $pem=file_get_contents($publicKey);
        $publicKey=openssl_pkey_get_public($pem);
        $signBytes=CommonUtil::getBytes($sign);
        //对响应签名内容进行处理，转换成256位字符串
        $signHandler=CharacterUtil::decodeHex($signBytes);
        $signStr=CommonUtil::toStr($signHandler);
        $flag=openssl_verify($content,$signStr, $publicKey,Algorithm::SHA256withRSA["desc"]);
        openssl_free_key($publicKey);

        if($flag){
            echo("响应报文验签成功\n");
            echo("报文解析：\n");
            $oriMerOrdrNo = $resArr["response"]["msgBody"]["oriMerOrdrNo"] ?? null;
            $tradeStatus = $resArr["response"]["msgBody"]["tradeStatus"] ?? null;
            $retCode = $resArr["response"]["msgBody"]["retCode"] ?? null;
            $retMsg = $resArr["response"]["msgBody"]["retMsg"] ?? null;
            echo $oriMerOrdrNo." "."$tradeStatus"." ".$retCode." ".$retMsg;
            return;

        }else{
            echo("响应报文验签失败\n");
            return;

        }


    }
}



?>
