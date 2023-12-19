<?php
namespace App\Models;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\HasMedia; 
use Spatie\MediaLibrary\InteractsWithMedia;

trait MediaTrait  
{
    // use HasMedia;
    use InteractsWithMedia;
    public function registerMediaConversions(Media $media = null) : void
    {
        $this->addMediaConversion('thumb')
            ->width(130)
            ->height(130);
            
        $this->addMediaConversion('medium')
            ->setManipulations(['w' => 1000, 'h' => 1000]);    
            
        $this->addMediaConversion('large')
            ->setManipulations(['w' => 2000, 'h' => 2000]);
    }

    public function registerMediaCollections() : void
    {
        foreach ($this->mediaCollections() as $n => $label) {
            $this->addMediaCollection($n);
        }
    } 
    
    public function mediaCollections()
    {
        $array = ['main', 'detail', 'contract', 'photo', 'license', 'id_card'];
        $c = [];
        foreach ($array as $n) {
            $c[$n] = __(ucwords($n));
        }
        return $c;
    } 
}