<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\User;
use Log;

class SalesController extends AppBaseController
{
    public function relation($id)
    {
        if (!$user = User::find($id)) {
            echo "没有找到用户!!";
            return ;
        }
        return view('sales.relation', ['orgData' => $user->orgData()]);
    }
}