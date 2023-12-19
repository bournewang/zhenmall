<?php
namespace App\Helpers;
use Carbon\Carbon;
// use Image;
// use Storage;

class ExpressHelper
{
    // return format
    // "update": "更新时间戳",
    // "upgrade_info": "提示信息，用于提醒用户可能出现的情况",
    // "updateStr": "更新时间",
    // "logo": "快递公司logo",
    // "dataSize": "数据节点的长度",
    // "status": "快递状态 1 暂无记录 2 在途中 3 派送中 4 已签收 (完结状态) 5 用户拒签 6 疑难件 7 无效单 (完结状态) 8 超时单 9 签收失败 10 退回",
    // "fee_num": "计费次数。例如：0为计费0次，即不计费；1为计费1次",
    // "tel": "快递公司联系方式",
    // "data": "在途跟踪数据",
    // "-  time": "物流跟踪发生的时间",
    // "-  context": "物流跟踪信息",
    // "expSpellName": "快递编码",
    // "msg": "返回提示信息",
    // "mailNo": "快递单号",
    // "queryTimes": "无走件记录时被查询次数 注意：在24小时内，查询次数>10次将会计费",
    // "ret_code": "0 查询成功 或 提交成功。 1 输入参数错误。 2 查不到物流信息。 3 单号不符合规则。 4 快递公司编码不符合规则。 5 快递查询渠道异常。 6 auto时未查到单号对应的快递公司,请指定快递公司编码。 7 单号与手机号不匹配 其他参数：接口调用失败",
    // "flag": "true：查询成功，表示ret_code=0且data的长度>0。可使用本字段做是否读取data列表的依据。 false：查询失败。",
    // "expTextName": "快递简称",
    // "possibleExpList": "自动识别结果"  
    
    // example: 
    // +"queryTimes": 3,
    // +"upgrade_info": "",
    // +"fee_num": 1,
    // +"status": 4,
    // +"expSpellName": "zhongtong",
    // +"msg": "查询成功",
    // +"updateStr": "2022-01-05 22:37:34",
    // +"possibleExpList": [],
    // +"flag": true,
    // +"tel": "95311",
    // +"ret_code": 0,
    // +"logo": "http://static.showapi.com/app2/img/expImg/zto.jpg",
    // +"expTextName": "中通快递",
    static public function query($com, $nu, $phone = null)
    {
        $url = "https://ali-deliver.showapi.com/showapi_expInfo";
        $app_code = config('mall.express_app_code');
        $data = [
            'com' => $com,
            'nu' => $nu,
            'phone' => $phone,
            'callback_url' => config('app.url') . '/api/logistic/notify'
        ];
        $url .= '?' . http_build_query($data);
        \Log::debug("$url");
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', $url, ['headers' => [ 'Authorization' => 'APPCODE '.$app_code], 'timeout' => 20]);
        $str = $res->getBody();
        \Log::debug($str);
        return json_decode($str)->showapi_res_body ?? null;
    }    
}