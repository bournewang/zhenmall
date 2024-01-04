<?php
namespace updc\utils;

class CommonUtil{

	
/**
 * 字符串转换成字节数组
 * @param  [String] $str 
 * @return [byte[]]     
 */
static  function getBytes($str) { 
    $len = strlen($str);
        $bytes = array();
        for($i=0;$i<$len;$i++) {
            if(ord($str[$i]) >= 128){
                $byte = ord($str[$i]) - 256;
            }else{
                $byte = ord($str[$i]);
            }
            $bytes[] =  $byte ;
        }
        return $bytes;

    } 


    


    /**
     * 字节数组转换成字符串 ascii
     * @param  [byte[]] $bytes 
     * @return [String]  
     */
    public static function toStr($bytes) {
        $str = '';
        foreach($bytes as $ch) {
            $str .= chr($ch);
        }


           return $str;
    }

        public  static  function  byteArrayToString($bytes,$charset) {

        $bytes=array_map('chr',$bytes);
        $str=implode('',$bytes);
        $str = iconv('UTF-16',$charset,$str);
        return $str;

    }


    public static function hexdec_string($content) {
        preg_replace_callback(
            "(\\\\x([0-9a-f]{2}))i",
            function($matches) {return chr(hexdec($matches[1]));},
            // $string
            $content
        );

        return $content;
    }


}
?>