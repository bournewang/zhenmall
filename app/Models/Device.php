<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends BaseModel
{
    use HasFactory;
    public $table = 'devices';
    public $fillable=[
        'store_id',
        'product_key',
        'device_name',
        'status'
    ];
    
    public static $rules = [
        'store_id' => 'required',
        'product_key' => 'required',
        'device_name' => 'required',
    ];
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    
    public function statusLabel()
    {
        return $this->status ? __('Active') : __('Inactive');
    }
    
    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }
}
