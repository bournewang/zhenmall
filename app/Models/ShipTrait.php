<?php
namespace App\Models;
use App\Helpers\ExpressHelper;

trait ShipTrait  
{
    // models which use ShipTrait should have these fields:
    // 'status'
    // 'ship_status' => 1, // no record
    // 'logistic_id'
    // 'waybill_number'
    public function logistic()
    {
        return $this->belongsTo(Logistic::class);
    }
    
    public function logisticProgress()
    {
        return $this->morphOne(LogisticProgress::class, 'order');
    }
    
    // model should have two status, shipped/complete
    public function deliver($logistic, $num)
    {
        $this->update([
            'status' => self::SHIPPED,
            'ship_status' => 1, // no record
            'logistic_id' => $logistic->id,
            'waybill_number' => $num
        ]);
        ExpressHelper::query($logistic->code, $num, $logistic->code == 'shunfeng' ? substr($this->mobile, -4) : null);
    }
    
    public function receive()
    {
        $this->update(['status' => self::COMPLETE]);
    }    
}