<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Store;
use App\Models\Goods;

class ReviewController extends ApiBaseController
{
    /**
     * Review list api
     *
     * @OA\Get(
     *  path="/api/reviews",
     *  tags={"Order"},
     *  @OA\Parameter(name="goods_id",   in="query",required=false,explode=true,@OA\Schema(type="integer"),description="goods id"),
     *  @OA\Parameter(name="perpage",       in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
     *  @OA\Parameter(name="page",          in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),  
     *  @OA\Response(response=200,description="successful operation")
     * )
     */    
    public function index(Request $request)
    {
        $reviews = Review::where('id', '>', 0);
        if ($goods = Goods::find($request->input('goods_id'))) {
            // find orders with goods
            $ids = $goods->orders->pluck('id')->all();
            $reviews = $reviews->whereIn('order_id', $ids);
        }
        
        $total = $reviews->count();
        $perpage = $request->input('perpage', 20);
        $data = [
            'total' => $total,
            // 'avg_rating' => round((float)$reviews->sum('rating') / $total, 2),
            'pages' => ceil($total/$perpage),
            'page' => $request->input('page', 1),
            'items' => []
        ];
        $reviews = $reviews->paginate($perpage);
        foreach ($reviews as $review) {
            $data['items'][] = $review->detail();
        }
        return $this->sendResponse($data);
    }
    
    /**
     * Review detail api
     *
     * @OA\Get(
     *  path="/api/reviews/{id}",
     *  tags={"Order"},
     *  @OA\Parameter(name="id",   in="path",required=false,explode=true,@OA\Schema(type="integer"),description="reviews id"),
     *  @OA\Response(response=200,description="successful operation"),
     * )
     */
    public function detail($id, Request $request)
    {
        if ($reviews = Review::find($id)) {
            return $this->sendResponse($reviews->detail());
        }
    }
}
