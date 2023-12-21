<?php
namespace App\Helpers;
use App\Models\BalanceLog;
use App\Models\User;

class RedPacketHelper
{
    static public function sendRedPackets($order)
    {
        // categories need to send red packet
        $catIds = array_keys(config("referer.category"));
        foreach ($order->goods->whereIn("category_id", $catIds) as $goods) {
            $config = config("referer.category.".$goods->category_id);
            \Log::debug("send packets, category id: ".$goods->category_id.", level: ".$config['level']);
            $level = 1;
            if (!$user = User::find($order->user->referer_id)) {
                \Log::debug("no referer");
                continue;
            }
            do {
                $amount = rand($config['range']['min'], $config['range']['max']);
                $balance = $user->balance + $amount;
                \Log::debug("set redpacket for $user->id amount: $amount, balance: $balance");
                BalanceLog::create([
                    'store_id' => $order->store_id,
                    'user_id' => $user->id,
                    'type' => 'deposit',
                    'amount' => $amount,
                    'balance' => $balance,
                    'comment' => "下单红包",
                    'open' => false
                ]);
                $user->update(['balance' => $balance]);

                // send red packet for referer
                if (!$user = User::find($user->referer_id)) {
                    \Log::debug("no referer, break");
                    break;
                }
                // \Log::debug("level $level");
            } while ($level++ < $config['level']);
        }

    }
}
// 50 => [
//     // "category_id" => 50,
//     "level" => 5,
//     "type" => "red_packet",
//     "amount" => "random",
//     "range" => [
//         "min" => 10,
//         "max" => 20
//     ]
// ]
