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
use Laravel\Nova\Fields\Text;

use App\Imports\DevicesImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportDevices extends Action
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
        return __('Import Devices');
    }

    /**
     * @return string
     */
    public function uriKey() :string
    {
        return 'import-devices';
    }

    /**
     * Perform the action.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @return mixed
     */
    public function handle(ActionFields $fields)
    {
        Excel::import(new DevicesImport, $fields->file);

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
            File::make(__('File'), 'file')
                ->rules('required')
                ->help(
                    "<a class='text text-primary' href='/templates/devices.xlsx' download='设备.xlsx'>".__('Template Download') . "</a><br/>".
                    "<a class='text text-default' href='/log.php?p=device' target=_blank>".__('Import Log') .'</a>'
                )
                
            
        ];
    }
}