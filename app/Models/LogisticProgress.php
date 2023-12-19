<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticProgress extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_type',
        'order_id',
        'queryTimes',
        'fee_num',
        'status',
        'upgrade_info',
        'expSpellName',
        'expTextName',
        'mailNo',
        'msg',
        'possibleExpList',
        'flag',
        'ret_code',
        'logo',
        'tel',
        'data'
    ];
    
    protected $casts = [
        'order_type' => 'string',
        'order_id' => 'integer',
        'queryTimes' => 'integer',
        'fee_num' => 'integer',
        'status' => 'integer',
        'upgrade_info' => 'string',
        'expSpellName' => 'string',
        'expTextName' => 'string',
        'mailNo' => 'string',
        'msg' => 'string',
        'possibleExpList' => 'json',
        'data' => 'json',
        'flag' => 'boolean',
        'ret_code' => 'integer',
        'logo' => 'string',
        'tel' => 'string',
    ];
    
    public function order()
    {
        return $this->morphTo();
    }
    
    public function detail()
    {
        $str = [];
        foreach ($this->data as $item){
            $str[] = $item['time'] ."<br/>". $item['context'];
        }
        return implode('<br><br/>',$str);
    }
    
    // "status": "快递状态 1 暂无记录 2 在途中 3 派送中 4 已签收 (完结状态) 5 用户拒签 6 疑难件 7 无效单 (完结状态) 8 超时单 9 签收失败 10 退回",    
    static public function statusOptions()
    {
        return [
            0 => null,
            1 => __('No Record'),
            2 => __('On The Way'),
            3 => __('Dispatching'),
            4 => __('Received'),
            5 => __('User Rejected'),
            6 => __('Problem'),
            7 => __('Invalid'),
            8 => __('Timeout'),
            9 => __('Receive Fail'),
            10 => __('Return')
        ];
    }
    
    public function statusLabel()
    {
        return self::statusOptions()[$this->status] ?? '-';
    }
}
