<?php
namespace App\Helpers;
use App\Models\BalanceLog;
use App\Models\QuotaLog;
use App\Models\RedPacket;
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;

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
    static public function send($order)
    {
        if ($order->profit_splited) {
            throw new \Exception("already split profit");
        }
        // categories need to send red packet
        if (!$setting = Setting::first()){
            throw new \Exception("no settings found");
        }
        $catIds = [$setting->level_1_cat_id, $setting->level_2_cat_id];
        $owner = User::find($order->user_id);
        foreach ($order->goods->whereIn("category_id", $catIds) as $goods) {
            // get config
            $update_level = null;
            \Log::debug("goods id: $goods->id, category id: ".$goods->category_id);
            if ($goods->category_id == $setting->level_1_cat_id) {
                $rewards_days = $setting->level_1_rewards_days;
                $rewards_quota= $setting->level_1_rewards_quota;
                $rewards_referer = $setting->level_1_rewards_referer;
                $common_wealth_level = $setting->level_1_common_wealth_level;
                $common_wealth_min = $setting->level_1_common_wealth_min;
                $common_wealth_max = $setting->level_1_common_wealth_max;
                // update owner level
                if ($owner->level < 1) {
                    $update_level = 1;
                }
            }else{
                $rewards_days = $setting->level_2_rewards_days;
                $rewards_quota= $setting->level_2_rewards_quota;
                $rewards_referer = $setting->level_2_rewards_referer;
                $common_wealth_level = $setting->level_2_common_wealth_level;
                $common_wealth_min = $setting->level_2_common_wealth_min;
                $common_wealth_max = $setting->level_2_common_wealth_max;
                if ($owner->level < 2) {
                    $update_level = 2;
                }
            }
            \Log::debug("\n rewards_days: $rewards_days;\n rewards_quota: $rewards_quota;\n rewards_referer: $rewards_referer;\n common_wealth_level: $common_wealth_level;\n common_wealth_min: $common_wealth_min;\n common_wealth_max: $common_wealth_max");
            // update owner's rewards_expires_at and quota
            $expires_at = ($owner->rewards_expires_at ? Carbon::parse($owner->rewards_expires_at) : Carbon::today())
                ->addDays($rewards_days)->toDateString();
            $log = QuotaLogHelper::create($owner, $rewards_quota, "下单");
            $owner_data = [
                'rewards_expires_at' => $expires_at,
                'quota' => $log->balance
            ];
            if ($update_level) {
                $owner_data['level'] = $update_level;
            }
            $owner->update($owner_data);
            \Log::debug("update owner: ".json_encode($owner_data));
            // \Log::debug("update rewards_expires_at to $expires_at, rewards quota $rewards_quota to ".$log->balance);
            $level = 1;
            if (!$referer = User::find($owner->referer_id)) {
                \Log::debug("no referer");
                continue;
            }
            // direct referer have redpacket
            $balance_log = BalanceLogHelper::deposit($referer, $rewards_referer, "直推下单奖励");
            $referer->update(['balance' => $balance_log->balance]);
            \Log::debug("rewards $rewards_referer to direct referer $referer->id, balance {$balance_log->balance}");

            // common wealth pool
            \Log::debug("common wealth level: ".$common_wealth_level);
            do {
                $amount = rand($common_wealth_min, $common_wealth_max);
                // $balance = $referer->balance + $amount;
                \Log::debug("create redpacket for user $referer->id amount: $amount");
                self::create($referer, $amount);

                // send red packet for referer
                if (!$referer = User::find($referer->referer_id)) {
                    \Log::debug("no referer, break");
                    break;
                }
                // \Log::debug("level $level");
            } while ($level++ < $common_wealth_level);
        }

        $order->update(['profit_splited' => true]);
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
