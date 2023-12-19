<?php

namespace App\Models;

trait StatusTrait{
    //status available:
    public $inactive = 'inactive';
    public $active = 'active';
    public $pending = 'pending';
    // public $info = 'info';
    // public $warning = 'warning';
    // public $help = 'help';
    public $disabled = 'disabled';
    
    public function statusOptions()
    {
        return [
            $this->inactive => __(ucfirst($this->inactive)),
            $this->active 	=> __(ucfirst($this->active)),
            $this->pending 	=> __(ucfirst($this->pending)),
            // $this->info 	=> __(ucfirst($this->info)),
            // $this->warning 	=> __(ucfirst($this->warning)),
            // $this->help 	=> __(ucfirst($this->help)),
            $this->disabled => __(ucfirst($this->disabled)),
        ];
    }
    
    public function statusLabel()
    {
        return $this->statusOptions()[$this->status] ?? null;
    }
}