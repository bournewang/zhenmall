<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;

class FileController extends ApiBaseController
{
    /**
     * 上传文件/图片
     *
     * @OA\Post(
     *   path="/api/file",
     *   tags={"文件上传"},
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="img", type="string", format="binary")
     *           )
     *       )
     *   ),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
     public function upload(Request $request)
     {
         if (!$request->hasFile('img')) {
             return $this->sendError("没有选择文件");
         }
         $dir = "users/".$this->user->id;
         $path = $request->img->store("public/$dir");
         $url = url('storage/'.$dir .'/'.pathinfo($path, PATHINFO_BASENAME));
         
         return $this->sendResponse(['url' => $url]);
     }
 }