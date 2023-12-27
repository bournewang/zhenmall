<?php
namespace App\Helpers;
use Carbon\Carbon;
use App\Models\QuotaLog;

class QuotaLogHelper
{
    static public function create($user, $amount, $comment = null)
    {
        return QuotaLog::create([
            'store_id' => $user->store_id,
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $amount,
            'balance' => $user->quota + $amount,
            'comment' => $comment,
            // 'open' => true
        ]);
    }
}
