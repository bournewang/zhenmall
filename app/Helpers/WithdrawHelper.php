<?php
namespace App\Helpers;
use App\Models\Withdraw;

class WithdrawHelper
{
    static public function create($user, $amount)
    {
        return Withdraw::create([
            'store_id'  => $user->store_id,
            'user_id'   => $user->id,
            'amount'    => $amount,
            'status'    => Withdraw::AUDITING
        ]);
    }
}
