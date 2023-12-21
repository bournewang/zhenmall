<?php

namespace App\Imports;

use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

use App\Models\Goods;
use App\Models\Category;
// use App\Models\Supplier;
use App\Helpers\ValidatorHelper;

class ModelImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use LogTrait;
    // public $model_name;
    public $pk = 'name';
    public $model = 'model';
    public function __construct()
    {
        HeadingRowFormatter::default('none'); 
        $model_name = strtolower(explode('\\', $this->model)[2]);
        $log = public_path(config('mall.import_log_location').$model_name.".html");
        $this->initLog($log);
    }
    
    public function __destruct()
    {
        fclose($this->log);
    }
    protected function prepareData(array $row)
    {
        return [
            'name'  => $row[0],
            'qty'   => $row[1],
            // 'supplier_id'   => $this->parse($row[2], 'Supplier')
        ];
    }
    

    public function prepareForValidation(array $row)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        $this->logData(implode(',', array_values($row)));
        return $this->prepareData($row);
    }
    
    public function model(array $data)
    {
        \Log::debug(__CLASS__.'->'.__FUNCTION__);
        if($model = $this->model::where($this->pk, $data[$this->pk])->first()){
            \Log::debug("-  update $model->id ".$data[$this->pk]);
            $model->update($data);
            $this->logWarning(__("Update"). __('Success'));
            return $model;
        }else {
            \Log::debug('-  create '.$data[$this->pk]);
            $model = new $this->model($data);
            $this->logSuccess(__("Create"). __('Success'));
            return $model;
        }
    }
    
    protected function parse($str, $class, $field='name')
    {
        if ($record = $class::where($field, $str)->first()) {
            return $record->id;
        }
        return null;
    }
    
    public function batchSize(): int
    {
        return 1000;
    }  
    
    public function rules(): array
    {
        return $this->model::$rules;
    }
    
    public function onFailure(Failure ...$failures)
    {
        $errors = [];
        foreach ($failures as $failure) {
            $errors = array_merge($errors, $failure->errors());
        }
        $this->logDanger(implode(", ", $errors));
    }    
}
