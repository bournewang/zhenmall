<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Goods;
use Storage;

class GoodsImagesImport
{
    use ZipImport;
    use LogTrait;
    const acccept_image_format = ['png', 'jpg', 'jpeg', 'git'];
    public $dir;

    public function __construct($zip_file)
    {
        $log = public_path(config('mall.import_log_location')."goods-images.html");
        $this->initLog($log);
        $this->dir = $this->extractZip($zip_file);
    }
    public function import($dir = null)
    {
        \Log::debug(__FUNCTION__." $dir");
        $dir = $dir ?? $this->dir;
        if (!is_dir($dir)) return;
        foreach ($this->sortFiles($dir) as $subdir) {
            \Log::debug("check $subdir");
            if (!is_dir($subdir)) {
                continue;
            }
            // get product
            $subdir_name = pathinfo($subdir, PATHINFO_FILENAME);
            if ($model = Goods::where('name', $subdir_name)->first()) {
                \Log::debug("goods $subdir_name exists");
                $this->importGoodsImage($model, $subdir);
                // continue;
            }else{
                $this->import($subdir);
            }
        }
    }
    
    protected function importGoodsImage($model, $dir)
    {
        $str = [];
        $this->logData(pathinfo($dir, PATHINFO_FILENAME));
        foreach ($this->sortFiles($dir) as $collect) {
            if (!is_dir($collect)) {
                continue;
            }
            // get collection name
            $dir_name = pathinfo($collect, PATHINFO_FILENAME);
            $str[] = $dir_name;
            $collect_name = array_flip((new Goods)->mediaCollections())[$dir_name] ?? null;
            \Log::debug("collection name $dir_name => $collect_name");
            if (!$collect_name) {
                continue;
            }
            
            // walk through all images and add to media library
            foreach ($this->sortFiles($collect) as $image) {
                $ext = pathinfo($image, PATHINFO_EXTENSION);
                $img_name = pathinfo($image, PATHINFO_FILENAME);
                if (in_array(strtolower($ext), self::acccept_image_format)) {
                    if (filesize($image) > config('mall.upload.image_limit')) {
                        \Log::error("请选择1M以内的图片 $image");
                        continue;
                    }
                    \Log::debug("add $image");
                    $model->addMedia($image)->toMediaCollection($collect_name);
                    $str[] = $img_name.$this->label_success("v");
                }else{
                    \Log::error("format not acccept: $ext, only accept: ".implode(',',self::acccept_image_format));
                    $str[] = $img_name.$this->label_error('x');
                }
            }  
        } 
        $this->logResult(implode("&nbsp;", $str));
    }
    protected function sortFiles($dir)
    {
        $files=[];
        foreach (glob("$dir/*") as $file) { // lists all files in folder called "test"
            $files[] = $file;
        } 
        sort($files);
        return $files;
    }
}
