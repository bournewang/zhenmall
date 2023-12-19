<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Goods;
use App\Models\Store;
use App\Models\Cart;

class CartController extends ApiBaseController
{
    protected function getCart()
    {
        // $this
        if (!$cart = $this->user->cart) {
            $cart = Cart::create([
                'store_id' => $this->user->store_id,
                'user_id' => $this->user->id,
                'total_quantity' => 0,
                'total_price' => 0,
            ]);
        }
        return $cart;
    }
    
    /**
     * Get cart info api
     * @OA\Get(
     *  path="/api/cart",
     *  tags={"Cart"},     
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function show()
    {
        if ($cart = $this->user->cart) {
            return $this->sendResponse($cart->detail());
        }
        return $this->sendResponse([]);
    }
    
    /**
     * Add to cart api
     * @OA\Post(
     *  path="/api/cart/{goods_id}",
     *  tags={"Cart"},     
     *  @OA\Parameter(name="goods_id",in="path",required=true,explode=true,@OA\Schema(type="integer"),description="goods id"),
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="quantity",
     *                   description="quantity",
     *                   type="integer"
     *               )
     *           )
     *       )
     *   ),     
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function add($goods_id, Request $request)
    {
        if ($cart = $this->getCart()) {
            $cart->add(Goods::find($goods_id), $request->input('quantity', 1));
            return $this->sendResponse($cart->detail(), 'add to cart success');
        }
    }
    
    /**
     * Update cart item api
     *
     * @OA\Put(
     *  path="/api/cart/{goods_id}",
     *  tags={"Cart"},     
     *  @OA\Parameter(name="goods_id",in="path",required=true,explode=true,@OA\Schema(type="integer"),description="goods id"),
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="quantity",
     *                   description="quantity",
     *                   type="integer"
     *               )
     *           )
     *       )
     *   ),    
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function update($goods_id, Request $request)
    {
        if ($cart = $this->getCart()) {
            $cart->change(Goods::find($goods_id), $request->input('quantity', 1));
            return $this->sendResponse($cart->detail());
        }
    }
    
    /**
     * Delete cart item api
     *
     * @OA\Delete(
     *  path="/api/cart/{goods_id}",
     *  tags={"Cart"},
     *  @OA\Parameter(name="goods_id",in="path",required=true,explode=true,@OA\Schema(type="integer"),description="goods id"),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function delete($goods_id, Request $request)
    {
        if ($cart = $this->getCart()) {
            $cart->remove(Goods::find($goods_id));
            return $this->sendResponse($cart->detail());
        }
    }
}