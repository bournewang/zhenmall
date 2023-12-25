<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\BalanceLog;

class RedPacketController extends ApiBaseController
{
    /**
     * Get red packets 获取红包列表
     *
     * @OA\Get(
     *  path="/api/red-packets",
     *  tags={"RedPacket"},
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )\
     */
    public function index(Request $request)
    {
        $data = $this->user->balanceLogs()->where('open', false)->pluck("amount", "id")->toArray();
        return $this->sendResponse($data);
    }


    /**
     * Open red packets 开红包
     *
     * @OA\Put(
     *  path="/api/red-packets/{id}",
     *  tags={"RedPacket"},
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function open($id, Request $request)
    {
        $balance_log = BalanceLog::find($id);
        // check if the request is from the owner
        if ($balance_log->user_id != $this->user->id) {
            $this->sendError("invalid request");
        }

        $balance_log->update(['open' => true]);
        return $this->sendResponse(null);
    }
}
