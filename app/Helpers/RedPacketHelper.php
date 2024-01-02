<?php
namespace App\Helpers;
use App\Models\BalanceLog;
use App\Models\QuotaLog;
use App\Models\RedPacket;
use App\Models\User;

class RedPacketHelper
{
    static public function create($user, $amount, $type=null)
    {
        return RedPacket::create([
            'store_id' => $user->store_id,
            'user_id' => $user->id,
            'amount' => $amount,
            'open' => 0,
            'type' => $type
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

    // number： redpackets Number
    // total_amount: total amount of all red packets
    // minium unit is 1 cent, 100 = 1 RMB
    // algorithm: m1 = rand(1, (m/n)*2)
    static public function randomPackets($number, $total_amount, $min)
    {
        $array = [];
        // $total_amount *= 100;
        while ($number) {
            if ($number > 1) {
                $m1 = rand($min, (int)($total_amount / $number) * 2);
            }else{ // last one
                $m1 = $total_amount;
            }
            $array[] = $m1; //round($m1/100, 2);
            $total_amount -= $m1;
            $number--;
        }
        return $array;
    }

    static public function registerPacket($user)
    {
        $min = 2 * 100; // 2
        $max = 20 * 100; // 10
        $amount = round(rand($min, $max)/100, 2);
        \Log::channel('money')->debug("create redpacket for user {$user->id} amount: $amount");
        self::create($user, $amount, RedPacket::TYPE_REGISTER);
    }
}
