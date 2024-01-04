<?php
namespace updc\bean;
use updc\utils\CommonUtil;
use updc\utils\CharacterUtil;
class Signature{
	private $publicKey;
	private $privateKey;
	private $privateKeyPass;
	private $algorithm;

	public function __set($name,$value){
		$this->$name=$value;
	}

	public function __get($name){
		return $this->$name;
	}

	public function __construct($algorithm,$publicKey,$privateKey,$privateKeyPassword){
		$this->__set("algorithm",$algorithm);
		$this->__set("publicKey",$publicKey);
		$this->__set("privateKey",$privateKey);
		$this->__set("privateKeyPass",$privateKeyPassword);
	}

	/**
	 * 请求内容进行签名
	 * @param  [String] $content 请求内容-签名
	 * @return [String]   签名 512位十六进制字符串
	 */
	public  function sign($content){
		if(!file_exists($this->privateKey)){
			throw new \Exception("私钥证书不存在", 1);

		}
			//获取私钥证书内容
			$privatePem=file_get_contents($this->privateKey);

			echo "private key file: ".$this->privateKey."\n";
			if (file_exists($this->privateKey)){
				echo "file exists: ".$this->privateKey."\n";
			}
			// echo "private key pem: ".$privatePem."\n";
			//获取私钥
		    $privateKey = openssl_get_privatekey($privatePem);

		    //加密算法类型
		    $signAlg=$this->algorithm["desc"];

		    openssl_sign($content,$resign,$privateKey,$signAlg);


			// echo "private key: ";
			// var_dump($privateKey);
			// die();
		    openssl_free_key($privateKey);

		    //签名转换的byte数组 256
		    $signByteArr=CommonUtil::getBytes($resign);
	         // var_dump($signByteArr);
	         //对签名进行处理，获取发送的签名内容 512位十六进制字符串
		     $signArr=CharacterUtil::encodeHex($signByteArr);
		     $sign=implode($signArr);

		    return $sign;

	}



	/**
	 * 响应内容进行签名验签
	 * @param  [String] $data 验签原文内容
	 * @param  [String] $sign [响应签名]
	 * @return [int]     验签是否通过	 	0：不通过  1：通过
	 */
	 function verify($data,$sign){
	 	if(!file_exists($this->publicKey)){
			throw new \Exception("公钥证书不存在", 1);
		}
	 	//获取公钥内容
		$pem=file_get_contents($this->publicKey);

			$publicKey=openssl_pkey_get_public($pem);

			$sign=CommonUtil::getBytes($sign);
			//对响应签名内容进行处理，转换成256位字符串
			$sign=CharacterUtil::decodeHex($sign);

			$sign=CommonUtil::toStr($sign);

		    $flag=openssl_verify($data,$sign, $publicKey,$this->algorithm["desc"]);

			echo "private key: ";
			var_dump($publicKey);
		     openssl_free_key($publicKey);

		     return $flag;
}
}
?>
