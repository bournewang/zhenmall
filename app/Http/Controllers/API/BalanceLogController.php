<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;

class BalanceLogController extends ApiBaseController
{
    /**
     * BalanceLog list api
     *
     * @OA\Get(
     *  path="/api/balance-logs",
     *  tags={"BalanceLog"},
     *  @OA\Response(response=200,description="successful operation")
     * )
     */
    public function index(Request $request)
    {
        $records = $this->user->balanceLogs()->where('open', true)->orderBy('id', 'desc');
        $total = $records->count();
        $perpage = $request->input('perpage', 20);
        $data = [
            'titles' => ["amount" => __('Amount'), 'comment' => __('Comment'), 'created_at' => __('Date')],
            'total' => $total,
            'pages' => ceil($total/$perpage),
            'page' => $request->input('page', 1),
            'items' => []
        ];
        $records = $records->paginate($perpage);
        foreach ($records as $record) {
            $info = $record->info();
            $data['items'][] = [
                'id' => $record->id,
                'amount' => money($record->amount),
                'comment' => $record->comment,
                'open' => $record->open,
                'created_at' => $record->created_at->toDateTimeString()
            ];
        }
        return $this->sendResponse($data);
    }
}
