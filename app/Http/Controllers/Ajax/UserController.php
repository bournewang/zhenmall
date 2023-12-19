<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Goods;
use Auth;
class UserController extends AppBaseController
{
    public function info(Request $request)
    {
        $user = Auth::user();
        return $this->sendResponse($user->detail());
    }
}
