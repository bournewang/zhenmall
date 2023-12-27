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
        $data = $this->buildList(
            $request,
            $this->user->balanceLogs(),
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
