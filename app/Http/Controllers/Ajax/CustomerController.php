<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Goods;
use Auth;
class CustomerController extends AppBaseController
{
    public function index(Request $request)
    {
        $data = [];
        $user = Auth::user();
        
        foreach ($user->store->customers() as $item) {
            $data[] = ["id" => $item->id, "name" => ($item->name ?? $item->nickname) . $item->mobile];
        }
        return $this->sendResponse($data);
    }
}
