<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Helpers\WithdrawHelper;

class WithdrawController extends ApiBaseController
{
    /**
     * Withdraw list api
     *
     * @OA\Post(
     *  path="/api/withdraw",
     *  tags={"Withdraw"},
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="amount",
     *                   description="提现金额，必须为100倍数，且小于等于余额和提现额度",
     *                   type="integer"
     *               )
     *           )
     *       )
     *   ),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function create(Request $request)
    {
        if (!$amount = $request->input('amount')) {
            return $this->sendError("no amount");
        }
        // needs to be 100 times
        if (($amount % 100) != 0){
            return $this->sendError("amount needs to be 100 times");
        }
        // check balance and quota
        if ($amount > $this->user->balance || $amount > $this->user->quota) {
            return $this->sendError("amount cannot greater than balance and quota");
        }

        WithdrawHelper::create($this->user, $amount);
        return $this->sendResponse(null);
    }

    /**
     * BalanceLog list api
     *
     * @OA\Get(
     *  path="/api/withdraw",
     *  tags={"Withdraw"},
     *  @OA\Response(response=200,description="successful operation")
     * )
     */
    public function index(Request $request)
    {
        $data = $this->buildList(
            $request,
            $this->user->withdraws(),
            [
                'id' => __('Index No'),
                'amount_label' => __('Amount'),
                'created_at' => __('Date'),
                'status_label' => __('Status')
            ]
        );

        return $this->sendResponse($data);
    }
}
