<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuotaLogController extends ApiBaseController
{
    /**
     * Quotalog list api
     *
     * @OA\Get(
     *  path="/api/quota-logs",
     *  tags={"BalanceLog"},
     *  @OA\Response(response=200,description="successful operation")
     * )
     */
    public function index(Request $request)
    {
        $data = $this->buildList(
            $request,
            $this->user->quotaLogs(),
            [
                'id' => __('Index No'),
                'amount_label' => __('Amount'),
                'comment' => __('Comment'),
                'created_at' => __('Date')
            ]
        );

        return $this->sendResponse($data);
    }
}
