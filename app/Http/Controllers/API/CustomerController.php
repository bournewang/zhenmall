<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;

class CustomerController extends ApiBaseController
{
    /**
     * Customer list api 获取门店下的顾客，店长查看该店所有顾客，店员可查看自己开发的顾客
     *
     * @OA\Get(
     *  path="/api/customers",
     *  tags={"Store"},
     *  @OA\Parameter(name="perpage",       in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
     *  @OA\Parameter(name="page",          in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),  
     *  @OA\Parameter(name="k",          in="query",required=false,explode=true,@OA\Schema(type="string"),description="keywords of name/mobile"),  
     *  @OA\Response(response=200,description="successful operation")
     * )
     */    
    public function index(Request $request)
    {
        if ($this->user->type != User::MANAGER) {
            return $this->sendError("只有店长可查看");
        }
        $users = Customer::where('type', User::CUSTOMER)->where('store_id', $this->user->store_id);
        if ($key = $request->input('k')) {
            $users->whereRaw("(name like '%$key%' or nickname like '%$key%' or mobile like '%$key%')");
        }
        return $this->sendResponse($this->paginateInfo($users, $request));
    }
    
}