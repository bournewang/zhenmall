<?php

namespace App\Nova\Actions;

use App\Models\Bill;
use Illuminate\Bus\Queueable;
use Anaseqal\NovaImport\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Select;

use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ImportBills extends Action
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
        return __('Generate Bills');
    }

    /**
     * @return string
     */
    public function uriKey() :string
    {
        return 'import-bills';
    }

    /**
     * Perform the action.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @return mixed
     */
    public function handle(ActionFields $fields)
    {
        $parts = explode('-', $fields->year_month_period);
        Bill::generate(intval($parts[0]), intval($parts[1]), intval($parts[2]));

        return Action::message('It worked!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $index = [];
        $m = 0;
        $day = Carbon::today()->format('d');
        if ($day > 20) {
            $period = 3;
        }elseif($day > 10) {
            $period = 2;
        }else{
            $period = 1;
        }

        do {
            $month = Carbon::today()->subMonth($m)->format('Y-m');
            $i = $m == 0 ? $period : 3;
            do {
                $index[$month .'-' . $i] = $month . __('Period Index', ['period' => $i]);
            } while ($i-- > 1);
        } while($m++ < 1);
        return [
            Select::make(__('Settlement Period'), 'year_month_period')->options($index)->displayUsingLabels(),
        ];
    }
}
