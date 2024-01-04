<?php
namespace updc\bean;
use updc\utils\SignatureBulider;
use updc\utils\RequestApiUtil;
/**
 * 
 */

class Client
{
	
	private $url;

	private $isHttps;

	private $appId;

	private $privateKey;

	private $publicKey;

	private $privateKeyPassword;

	private $gateWayRequest;
	
	private $algorithm;

	private $requestBody;

	private $responseBody;

	public function __set($name,$value){
		$this->$name=$value;
	}

	public function __get($name){
		return $this->$name;
	}


	/**
	 * 网关接口请求
	 * @return [int] 1：请求发送成功，响应验签成功  0：请求发送成功，响应验签失败
	 */
	public function apiRequest(){
		$timestamp=date("Y-m-d H:i:s");
		$gateWayRequest=$this->gateWayRequest;
		$algorithm=$this->algorithm;
		//设置签名数据
		$signArr=array(
			'app_id'=>$this->appId,
			'method'=>$gateWayRequest->__get("apiInterfaceId").".".$gateWayRequest->__get("methodName"),
			'timestamp'=>$timestamp,
			'v'=>$gateWayRequest->__get("version"),
			'sign_alg'=>$algorithm["code"],
			'biz_content'=>$gateWayRequest->__get("bizContent"),
		);
		$signData=http_build_query($signArr);
		
		 
		$signature=SignatureBulider::build($this->algorithm,$this->publicKey,$this->privateKey,$this->privateKeyPassword);
	
		$sign=$signature->sign($signData);
		
		$postArr=array_merge($signArr,array('sign'=>$sign));

		//请求发送内容
		$postData=http_build_query($postArr);

		//var_dump("请求数据：");
		//var_dump($postData);
		
		$this->requestBody=json_encode($postArr);
		$res=RequestApiUtil::request($this->isHttps,$this->url,$postData);
		if($res==false){
		    return ;
        }
		$this->responseBody=$res;
		
	        $resArr=json_decode($res,true);
	        $reSign=$resArr["sign"];
	        //var_dump("响应sign：");
			//var_dump($reSign);
		

	        //签名原文不能使用json字符串和数组之间的转换获取，该处理过程将键值冒号前的空着除去，导致与原文不一致
	        $index=strpos($res,"sign");
	        $reJson=substr($res,0,$index-2)."}";
	        //var_dump("响应验签主体：");
			//var_dump($reJson);
	        
	       
	        $flag=$signature->verify($reJson,$reSign);
	        return $flag;
	      }

}
?>