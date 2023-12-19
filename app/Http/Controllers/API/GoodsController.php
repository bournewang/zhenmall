<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Goods;
use App\Models\Store;
use App\Models\Category;

class GoodsController extends ApiBaseController
{
    /**
     * Goods list api
     *
     * @OA\Get(
     *  path="/api/goods",
     *  tags={"Category and Goods"},
     *  @OA\Parameter(name="category_id",   in="query",required=false,explode=true,@OA\Schema(type="integer"),description="category id"),
     *  @OA\Parameter(name="perpage",       in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
     *  @OA\Parameter(name="page",          in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),  
     *  @OA\Parameter(name="k",             in="query",required=false,explode=true,@OA\Schema(type="string"), description="key words"),
     *  @OA\Parameter(name="recommend",     in="query",required=false,explode=true,@OA\Schema(type="integer"),description="recommend"),
     *  @OA\Response(response=200,description="successful operation")
     * )
     */    
    public function index(Request $request)
    {
        $g = new Goods;
        $goods = Goods::where('status', '!=', $g->off_shelf);
        if ($cat = Category::find($request->input('category_id'))) {
            $ids = $cat->children()->pluck('id')->all();
            $ids[] = $cat->id;
            $goods = $goods->whereIn('category_id', $ids);
        }
        if ($key = $request->input('k')) {
            $goods = $goods->where('name', 'like', "%$key%");
        }
        if ($recommend = $request->input('recommend')) {
            $goods = $goods->where('status', $g->recommend);
        }
        
        $total = $goods->count();
        $perpage = $request->input('perpage', 20);
        $data = [
            'total' => $total,
            'pages' => ceil($total/$perpage),
            'page' => $request->input('page', 1),
            'items' => []
        ];
        $goods = $goods->paginate($perpage);
        foreach ($goods as $good) {
            $data['items'][] = $good->info();
        }
        return $this->sendResponse($data);
    }
    
    /**
     * Goods detail api
     *
     * @OA\Get(
     *  path="/api/goods/{id}",
     *  tags={"Category and Goods"},
     *  @OA\Parameter(name="id",   in="path",required=false,explode=true,@OA\Schema(type="integer"),description="goods id"),
     *  @OA\Response(response=200,description="successful operation"),
     * )
     */
    public function detail($id, Request $request)
    {
        $data = [];
        if ($goods = Goods::find($id)) {
            $data = $goods->detail();
        }
        if ($this->user) {
            $data['faved'] = !!$this->user->likes()->find($id);
        }
        return $this->sendResponse($data);
    }
    
    /**
     * Goods like api
     *
     * @OA\Post(
     *  path="/api/goods/{id}/like",
     *  tags={"Category and Goods"},
     *  @OA\Parameter(name="id",   in="path",required=false,explode=true,@OA\Schema(type="integer"),description="goods id"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function like($id)
    {
        if (!$goods = Goods::find($id)) {
            // $data = $goods->
        }
        
        $this->user->likes()->syncWithoutDetaching($id);
        $this->user->save();
        
        return $this->sendResponse(null);
    }
    
    /**
     * Goods dislike api
     *
     * @OA\Delete(
     *  path="/api/goods/{id}/like",
     *  tags={"Category and Goods"},
     *  @OA\Parameter(name="id",   in="path",required=false,explode=true,@OA\Schema(type="integer"),description="goods id"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function dislike($id)
    {
        if (!$goods = Goods::find($id)) {
        }
        
        $this->user->likes()->detach($id);
        $this->user->save();
        
        return $this->sendResponse(null);
    }
}
