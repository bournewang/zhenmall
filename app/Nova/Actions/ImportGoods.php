<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Anaseqal\NovaImport\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;

use App\Imports\GoodsImport;
use App\Imports\GoodsImagesImport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ImportGoods extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function shownOnIndex()
    {
        return true;
    }
    public function shownOnDetail()
    {
        return false;
    }

    /**
     * Get the displayable name of the action.
     *
     * @return string
     */
    public function name() {
        return __('Import Goods');
    }

    /**
     * @return string
     */
    public function uriKey() :string
    {
        return 'import-goods';
    }

    /**
     * Perform the action.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @return mixed
     */
    public function handle(ActionFields $fields)
    {
        if ($fields->type == 'excel'){
            Excel::import(new GoodsImport, $fields->file);
        }elseif ($fields->type == 'zip'){
            $dir = "import/".Carbon::now()->format('Y-m-d');
            $zip = $fields->file->store($dir);
            $zip_file = storage_path('app/'.$zip);
            if (file_exists($zip_file)) {
                (new GoodsImagesImport($zip_file))->import();
            }
        }

        return Action::message('It worked!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make(__('Type'), 'type')->options(['excel' => __('Goods'), 'zip' => __('Image')])->displayUsingLabels(),
            File::make(__('File'), 'file')
                ->help(
                    "商品信息导入<a class='text text-primary' href='/templates/goods.xlsx' download='商品.xlsx'>".__('Template Download') . "</a>；<br/>".
                    "图片格式：建立以商品名称同名的文件夹，里面建'主图'和'详情'文件夹，分别放主图和详情图；图片以字母顺序排列；".
                    "最后将商品文件夹压缩成<span class='text text-danger'>zip</span>格式上传。<br/>".
                    "<a class='text text-default' href='/log.php?p=goods' target=_blank>".__("Goods").__('Import Log') .'</a><br/>' .
                    "<a class='text text-default' href='/log.php?p=goods-images' target=_blank>".__('Image').__('Import Log') .'</a>',
                )   
        ];
    }
}