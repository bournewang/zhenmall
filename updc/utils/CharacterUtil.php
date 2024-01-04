<?php

namespace updc\utils;
define("MIN_RADIX",2);
	define("MAX_RADIX",36);
	define("DECIMAL_DIGIT_NUMBER",9);
	define("A_DATA","u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800".
    "\u100F\u4800\u100F\u4800\u100F\u5800\u400F\u5000\u400F\u5800\u400F\u6000\u400F".
    "\u5000\u400F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800".
    "\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F".
    "\u4800\u100F\u4800\u100F\u5000\u400F\u5000\u400F\u5000\u400F\u5800\u400F\u6000".
    "\u400C\u6800\\030\u6800\\030\u2800\\030\u2800\u601A\u2800\\030\u6800\\030\u6800".
    "\\030\uE800\\025\uE800\\026\u6800\\030\u2000\\031\u3800\\030\u2000\\024\u3800\\030".
    "\u3800\\030\u1800\u3609\u1800\u3609\u1800\u3609\u1800\u3609\u1800\u3609\u1800".
    "\u3609\u1800\u3609\u1800\u3609\u1800\u3609\u1800\u3609\u3800\\030\u6800\\030".
    "\uE800\\031\u6800\\031\uE800\\031\u6800\\030\u6800\\030\\202\u7FE1\\202\u7FE1\\202".
    "\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1".
    "\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202".
    "\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1\\202\u7FE1".
    "\\202\u7FE1\uE800\\025\u6800\\030\uE800\\026\u6800\\033\u6800\u5017\u6800\\033\\201".
    "\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2".
    "\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201".
    "\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2\\201\u7FE2".
    "\\201\u7FE2\\201\u7FE2\\201\u7FE2\uE800\\025\u6800\\031\uE800\\026\u6800\\031\u4800".
    "\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u5000\u100F".
    "\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800".
    "\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F".
    "\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800".
    "\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F\u4800\u100F".
    "\u3800\\014\u6800\\030\u2800\u601A\u2800\u601A\u2800\u601A\u2800\u601A\u6800".
    "\\034\u6800\\030\u6800\\033\u6800\\034\\000\u7005\uE800\\035\u6800\\031\u4800\u1010".
    "\u6800\\034\u6800\\033\u2800\\034\u2800\\031\u1800\u060B\u1800\u060B\u6800\\033".
    "\u07FD\u7002\u6800\\030\u6800\\030\u6800\\033\u1800\u050B\\000\u7005\uE800\\036".
    "\u6800\u080B\u6800\u080B\u6800\u080B\u6800\\030\\202\u7001\\202\u7001\\202\u7001".
    "\\202\u7001\\202\u7001\\202\u7001\\202\u7001\\202\u7001\\202\u7001\\202\u7001\\202".
    "\u7001\\202\u7001\\202\u7001\\202\u7001\\202\u7001\\202\u7001\\202\u7001\\202\u7001".
    "\\202\u7001\\202\u7001\\202\u7001\\202\u7001\\202\u7001\u6800\\031\\202\u7001\\202".
    "\u7001\\202\u7001\\202\u7001\\202\u7001\\202\u7001\\202\u7001\u07FD\u7002\\201\u7002".
    "\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201".
    "\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002".
    "\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002\u6800".
    "\\031\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002\\201\u7002".
    "\u061D\u7002");

/**
 * 该类主要是256位字节数组和512位十六进制字符串的相互转换
 * 详细运算和注解参考jar包org.apache.commons.codec.binary.Hex
 */
class CharacterUtil{
	private   static $digits_lower = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f' );

	 private static $A=array();

	 static function dealA_DATA(){
	 	$resArr=array();
		$arr=explode("\\", A_DATA);
		for($i=0;$i<count($arr);$i++){
			if(strpos($arr[$i], "u")==0){
				$resArr[$i]=base_convert(substr($arr[$i],1,strlen($arr[$i])),16,10);
			}else{
				$resArr[$i]=base_convert($arr[$i],8,10);
			}
		}
		return $resArr;

	 }

	static function staticData(){
		$data=self::dealA_DATA();
		if(count($data)!=(256*2)){
			throw new \Exception("长度不满足", 1);
		}
		$i=0;
		$j=0;
		while ($i<(256*2)) {
			$entry=$data[$i]<<16;
			$i++;
			self::$A[$j]=$entry | $data[$i];
			$i++;
			$j++;
		}
	}
	static function digit($ch,$radix){
		if(count(self::$A)==0){
			self::staticData();
		}
		
		$value=-1;
		if($radix>=MIN_RADIX && $radix<=MAX_RADIX){
			$val=self::getProperties($ch);
			$kind=$val & 0x1F;
			
			if($kind==DECIMAL_DIGIT_NUMBER){
				$value=($ch+(($val & 0x3E0)>>5)) & 0x1F;
				
			}else if (($val & 0xC00)==0x00000C00) {
				$value=(($ch+(($val &0x3E0)>>5))&0x1F)+10;
				
			}
		}
		return ($value<$radix)?$value:-1;
	}
	static function getProperties($ch){
		$props=self::$A[$ch];
		return $props;
	}

	static function toDigit($ch,$index){
		$digit=self::digit($ch,16);
		
		if($digit==-1){
			throw new \Exception( "Illegal hexadecimal character " .$ch. " at index: ".$index."\n", 1);
		}else{
			return $digit;
		}
	}

	static function decodeHex($data){
		
	$len=count($data);
	if(($len&1)!=0){
		throw new \Exception("Odd number of characters", 1);
	}else{
		$out=array();
		$i=0;

		for($j=0;$j<$len;++$i){
			$f=self::toDigit($data[$j],$j)<<4;
			
			++$j;
			$f=$f | self::toDigit($data[$j],$j);
			
			++$j;
			$val=$f&255;
			if($val>=-128 && $val <=127){
				$out[$i]=$val;
			}else{
				$out[$i]=$val-127-129;
			}
			$out[$i]=$val;
			
		}
		
		return $out;

	}
}

public static function encodeHex($data){
    	$toDigits=self::$digits_lower;
    	$len=count($data);
    	$i=0;
    	$out=array();
    	for($var=0;$i<$len;++$i){
    		
    		$var1=240&$data[$i];
    		
    		$index1=self::unsignedRight($var1,4);
    		
    		$out[$var]=$toDigits[$index1];
    		
    		$var++;
    		$index2=15&$data[$i];
    		$out[$var]=$toDigits[$index2];
    		$var++;
    	}
    	

    	return $out;
    }


    

	static function unsignedRight($int, $n){
    for ($i=0; $i < $n; $i++) { 
        if( $int < 0 ){
            $int >>= 1;
            $int &= PHP_INT_MAX;
        }else{
            $int >>= 1;
        }
    }
    return $int;
}







}
?>