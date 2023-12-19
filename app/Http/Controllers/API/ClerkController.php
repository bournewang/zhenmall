<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Store;
use App\Models\Goods;

class ClerkController extends ApiBaseController
{
    /**
     * Clerk list api 店员列表,查询店长所在店铺的店员
     *
     * @OA\Get(
     *  path="/api/clerks",
     *  tags={"Store"},
     *  @OA\Parameter(name="perpage",       in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
     *  @OA\Parameter(name="page",          in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),  
     *  @OA\Parameter(name="k",          in="query",required=false,explode=true,@OA\Schema(type="string"),description="keywords of name/mobile"),  
     *  @OA\Response(response=200,description="successful operation")
     * )
     */    
    public function index(Request $request)
    {
        $users = $this->user->store->clerks();
        if ($key = $request->input('k')) {
            $users->whereRaw("(name like '%$key%' or nickname like '%$key%' or mobile like '%$key%')");
        }
        return $this->sendResponse($this->paginateInfo($users, $request));
    }
}