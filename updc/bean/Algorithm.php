<?php
namespace updc\bean;
/**
 * 加密算法枚举类
 * 0：SHA1withRSA
 * 1:SHA256withRSA
 * 3:SM3withSM2
 * 4:HMAC-SHA256
 * 5:MD5
 */
class Algorithm{
	const SHA256withRSA = array('code'=>1,'desc'=>'sha256WithRSAEncryption');
    private static $kvCodeDesc = [
        Algorithm::SHA256withRSA['code']=>Algorithm::SHA256withRSA['desc'],
    ];
    public static function getCodeDesc($code){
        if(isset(self::$kvCodeDesc[$code])){
            return self::$kvCodeDesc[$code];
        }else{
            return "未知";
        }
    }
    public static function getAll(){
        $list = [];
        foreach(self::$kvCodeDesc as $k=>$v){
            $list[] = array('code'=>$k,'desc'=>$v);
        }
        return $list;
    }
}
?>