<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    // use SoftDeletes;
    use StatusTrait;
    use AddressTrait;
    
    public $table = 'suppliers';

    // protected $dates = ['deleted_at'];

    protected $fillable = [
        // 'id',
        'name',
        'company_name',
        'license_no',
        'account_no', 
        'contact',
        'mobile',
        'province_id',
        'city_id',
        'district_id',
        'street',
        'status',
        // 'commission'
    ];
    
    protected $casts = [
        'name' => 'string',
        'company_name' => 'string',
        'license_no' => 'string',
        'account_no' => 'string', 
        'contact' => 'string',
        'mobile' => 'string',
        // 'commission' => 'integer'
    ];

    public static $rules = [
        'name' => 'required|string',
        'company_name' => 'requried|string',
        'license_no' => 'required|string',
    ];
    
}
