<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\BalanceLog;
use Carbon\Carbon;
use DB;

class UserHelper
{
    static public function qrcode($user)
    {
        $mpp = \EasyWeChat::miniProgram();
        $response = $mpp->app_code->getUnlimit("from=".$user->id, ['path' => '/pages/goods/list']);

        // 保存小程序码到文件
        $filename = null;
        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            $path  = "user/$user->id.jpg";
            $filename = $response->save(\Storage::disk('public')->path("user", "$user->id.jpg"));
            if (file_exists(\Storage::disk('public')->path($path))) {
                return $path;
            }
            // \Log::debug("save to $filename");
            // return $filename;
        }
        return null;
    }

    static public function team($user)
    {
        return cache1(tag_user($user), tag_user($user).".team", function()use($user){
            $team = DB::table('relation')->where('path', 'like', "%,$user->id,%");
            $yesterday_members = User::whereIn('id', $team->pluck('user_id'))->whereBetween("created_at", [Carbon::today()->subDay(1), Carbon::today()])->count();
            $team_members = $team->count();
            $direct_members = $user->juniors->count();
            $yesterday_income = $user->balanceLogs()->whereBetween("created_at", [Carbon::today()->subDay(1), Carbon::today()])->where('type', BalanceLog::DEPOSIT)->sum('amount');
            return [
                'team_members' => $team_members,
                'direct_members' => $direct_members,
                'yesterday_members' => $yesterday_members,
                'yesterday_income' => $yesterday_income,
            ];
        }, 3600);
    }

    static public function directRange()
    {
        return cache1("direct-members", "direct-members-range", function(){
            return DB::table('users as u1')
                ->join("users as u2", "u1.referer_id", "=", "u2.id")
                ->selectRaw("count(u1.id) as num, u1.referer_id, u2.nickname, u2.mobile")
                ->whereNotNull('u1.referer_id')
                ->groupBy("u1.referer_id")
                ->orderByDesc("num")
                ->limit(10)
                ->get()
                ->toArray();
            }, 3600 * 24);
    }

}
