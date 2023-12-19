<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Revenue extends BaseModel
{
    use SoftDeletes;
    
    protected $primaryKey = 'id';
    
    public $table = 'revenues';

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'store_id',
        'user_id',
        'year',
        'index', // // month: 1-12 /week: 1-52
        'start',
        'end',
        'ppv',
        'gpv',
        'tgpv', // gpv + ppv
        'pgpv', // tgpv (exclude qualified directors' tgpv)
        'agpv', // Accumulative Group Point Value  since your first month join
        'retail_income',
        'level_bonus',
        'leader_bonus',
        'width_bonus',
        'depth_bonus',
        'total_income',
        'clearing_status'
    ];
    
    protected $casts = [
        'store_id' => 'integer',
        'user_id' => 'integer',
        'year' => 'integer',
        'index' => 'integer',
        'start'  => 'datetime',
        'end'  => 'datetime',
        'ppv' => 'float',
        'gpv' => 'float',
        'tgpv' => 'float',
        'pgpv' => 'float',
        'agpv' => 'float',
        'retail_income' => 'float',
        'level_bonus' => 'float',
        'leader_bonus' => 'float',
        'width_bonus' => 'float',
        'depth_bonus' => 'float',
        'total_income' => 'float',
        'clearing_status' => 'boolean'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'shopId',
    ];
    
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function clearing()
    {
        return $this->update(['clearing_status' => 1]);
    }
    
    public function disclearing()
    {
        return $this->update(['clearing_status' => 0]);
    }
}