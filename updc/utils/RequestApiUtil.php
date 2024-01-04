<?php
namespace updc\utils;

class RequestApiUtil{

	/**
	 * 发送post请求
	 * @param  [bool] $isHttps 是否发送https请求
	 * @param  [String] $url     请求地址
	 * @param  [String] $data    [请求主体内容]	
	 * @return [String]          [响应主体内容]
	 */
	static function request($isHttps,$url,$data){
		$header = ["Content-type:text/json;charset=utf-8"];

		$curl=curl_init();

		//根据是否发送https请求设置是否进行证书检测
		if($isHttps){
			//没有证书，规避证书检测
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);//规避SSL验证
			curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, false);//跳过HOST验证
		}else{
			
			curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
			curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, false);
		}
			curl_setopt($curl,CURLOPT_URL,$url);
	        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // 使用自动跳转
	        curl_setopt($curl, CURLOPT_AUTOREFERER, true); // 自动设置Referer
	        curl_setopt($curl, CURLOPT_POST, true); // 发送一个常规的Post请求
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
	        curl_setopt($curl, CURLOPT_TIMEOUT, 10000); // 设置超时限制防止死循环
	        curl_setopt($curl, CURLOPT_HEADER, false); // 显示返回的Header区域内容
	        //curl_setopt($curl, CURLOPT_PROXY, '127.0.0.1:8888');
	        //设置请求头
    		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);//设置请求数据
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
	       
	        $res=curl_exec($curl);
			if($res==false){
				echo "请求发送失败:". curl_error($curl)."\n";
			}
        	curl_close($curl);

	        return $res;
	}
	
}
?>
