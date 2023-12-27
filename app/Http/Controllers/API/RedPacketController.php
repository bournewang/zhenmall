<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\RedPacket;
use App\Helpers\RedPacketHelper;

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
        $data = $this->user->redPackets()->where('open', false)->pluck("amount", "id")->toArray();
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
        if (!$red_packet = RedPacket::find($id)) {
            return $this->sendError("The red packet is not exist!");
        }
        if ($red_packet->open) {
            return $this->sendError("The red packet is already open!");
        }

        RedPacketHelper::open($this->user, $red_packet);
        return $this->sendResponse(['balance' => $this->user->balance]);
    }
}
