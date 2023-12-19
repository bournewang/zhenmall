<?php

namespace App\Models;

trait AddressTrait{
    
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
    
    public function display_address()
    {
        return implode(array_filter([
            $this->province->name ?? null,
            $this->city->name ?? null,
            $this->district->name ?? null,
            $this->street,
            " ".$this->contact,
            $this->mobile
        ]));
    }
    
}