<?php
namespace updc\utils;
use updc\bean\Signature;

class SignatureBulider{
	//根据algorithm获取签名类（可能存在不用的签名验签步骤）
	static function build($algorithm,$publicKey,$privateKey,$privateKeyPassword){
		if($algorithm["code"]==1){
			return new Signature($algorithm,$publicKey,$privateKey,$privateKeyPassword);
		}else{
			throw new \Exception("输入错误加密算法的类型", 1);
			
		}
	}
}
?>