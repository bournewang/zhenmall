<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
class BaseModel extends Model implements HasMedia
{
    use MediaTrait;
    public function info()
    {
        if ($this->info_fields) {
            $info = [];
            foreach ($this->info_fields as $attr){
                $info[$attr] = $this->$attr;
            }
        } else {
            $info = $this->getOriginal();
        }
        if (isset($info['amount'])){
            $info['amount_label'] = money($info['amount']);
        }
        foreach (['created_at', 'updated_at', 'deleted_at'] as $key) {
            $info[$key] = $this->$key ? $this->$key->toDateTimeString() : null;
        }
        return $info;
    }

    public function getMediaData($collect)
    {
//        $imgs = [];
        $imgs = ['thumb' => [], 'large' => []];
        foreach ($this->getMedia($collect) as $item) {
            $imgs['thumb'][] = $item->getUrl('thumb');
            $imgs['large'][] = $item->getUrl('large');
        }
        return $imgs;
    }
}
