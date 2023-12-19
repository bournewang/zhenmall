<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
class Address extends BaseModel
{
    //
    use SoftDeletes;
    use AddressTrait;

    protected $primaryKey = 'id';
    
    public $table = 'addresses';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'user_id',
        'province_id',
        'city_id',
        'district_id',
        'street',
        'contact',
        'mobile',
        'default'
    ];    
    
    protected $casts = [
        'user_id' => 'integer',
        'contact' => 'string',
        'mobile' => 'string',
        'province_id' => 'integer',
        'city_id' => 'integer',
        'district_id' => 'integer',
        'street' => 'string',
        'default' => 'boolean'
    ];
    
    public static $rules = [
        'store_id' => 'integer|required',
        'user_id' => 'integer|required',
        'contact' => 'string|required|max:12',
        'mobile' => 'string|required|max:24',
        'province' => 'string|required|max:32',
        'city' => 'string|required|max:32',
        'county' => 'string|required|max:32',
        'street' => 'string',
    ];
    
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function district()
    {
        return $this->belongsTo(District::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    
    public function toString()
    {
        return  implode('', array_filter([$this->province->name??null, $this->city->name??null, $this->district->name??null, $this->street])); 
                // ' '.implode(' ', [$this->contact, $this->mobile]);
    }
    
    public function detail()
    {
        return array_merge($this->info(), [
            'province' => $this->province->name??null,
            'city' => $this->city->name??null,
            'district' => $this->district->name??null,
            'display_address' => $this->toString()
        ]);
    }
}

