<?php
namespace App\Helpers;
use Image;
use Storage;

class ImageHelper
{
    static public function resize($ori, $refresh = false)
    {
        $path = Storage::disk('public')->path($ori);
        if (!file_exists($path)){
            \Log::debug("$path not exists, return");
            return [];
        } 
        $info = pathinfo($ori);
        $ext = $info['extension'] ?? 'jpg';

        $thumb = $info['dirname'] . '/' . $info['filename'] . "-s.$ext";
        $thumb_path = Storage::disk('public')->path($thumb);
        if (!file_exists($thumb_path) || $refresh) {
            $img = Image::make($path);
            $img->resize(config('wemall.image_resize.thumb', 100), null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumb_path);
        }

        $medium = $info['dirname'] . '/' . $info['filename'] . "-m.$ext";
        $medium_path = Storage::disk('public')->path($medium);
        if (!file_exists($medium_path) || $refresh) {
            $img = Image::make($path);
            $img->resize(config('wemall.image_resize.medium', 500), null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($medium_path);
        }

        $large = $info['dirname'] . '/' . $info['filename'] . "-l.$ext";
        $large_path = Storage::disk('public')->path($large);
        if (!file_exists($large_path) || $refresh) {
            $img = Image::make($path);
            $img->resize(config('wemall.image_resize.large', 1000), null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($large_path);
        }
        
        return [
            'thumb' => $thumb,
            'medium' => $medium,
            'large' => $large
        ];
    }
}
