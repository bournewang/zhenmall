<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Withdraw;
use App\Models\User;
use App\Helpers\QuotaLogHelper;

class FixQuotaInWithdraw extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:quota-in-withdraw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach(Withdraw::where('created_at', '>', '2024-01-01')->get() as $w)
        {
            $user = User::find($w->user_id);
            $log = QuotaLogHelper::create($user, -1 * $w->amount, "提现");
            echo "$w->id update user $w->user_id quota $user->quota - $w->amount to $log->balance\n";
            $user->update(['quota' => $log->balance]);
        }

        return 0;
    }
}
