<?php

namespace App\Models;

trait ShelfTrait{
    public $recommend = 'recommend';
    public $on_shelf = 'on_shelf';
    public $off_shelf = 'off_shelf';
    public function statusOptions()
    {
        return [
            $this->recommend => __('Recommend'),
            $this->on_shelf => __('On Shelf'),
            $this->off_shelf => __('Off Shelf')
        ];
    }
    
    public function statusClasses()
    {
        return [
            $this->recommend    => 'text text-danger',
            $this->on_shelf     => 'text text-success',
            $this->off_shelf    => 'text text-default',
        ];
    }
    
    public function statusLabel()
    {
        return $this->statusOptions()[$this->status] ?? null;
    }
    
    public function statusRichLabel()
    {
        $class = $this->statusClasses()[$this->status] ?? null;
        return "<span class='$class'>". $this->statusLabel() . "</span>";
    }
    
}