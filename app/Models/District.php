<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class District extends Model
{
    use SoftDeletes;

    public $table = 'districts';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'city_id',
        'name',
        'code'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'city_id' => 'integer',
        'name' => 'string',
        'code' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'city_id' => 'required',
        'name' => 'required',
        'code' => 'required'
    ];


	public function city(){
        return $this->belongsTo(City::class);
    }
}
