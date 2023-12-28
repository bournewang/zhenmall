<?php
namespace App\Helpers;
use App\Models\BalanceLog;
use App\Models\QuotaLog;
use App\Models\RedPacket;
use App\Models\User;

class RedPacketHelper
{
    static public function create($user, $amount)
    {
        return RedPacket::create([
            'store_id' => $user->store_id,
            'user_id' => $user->id,
            'amount' => $amount,
            'open' => 0
        ]);
    }
    static public function open($user, $red_packet)
    {
        // check ownership
        if ($user->id != $red_packet->user_id) {
            throw new ApiException("invalid operation, you are not the owner!");
        }

        // open red packet
        $red_packet->update(['open' => 1]);

        // insert cash to balance_log
        $log = BalanceLogHelper::deposit($user, $red_packet->amount, __("Red Packet"));

        // update owner's balance
        $user->update(['balance' => $log->balance]);
    }
}
