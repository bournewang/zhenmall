<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends ApiBaseController
{
    /**
     * Banner list api
     *
     * @OA\Get(
     *  path="/api/banners",
     *  tags={"Banner"},
     *  @OA\Response(response=200,description="successful operation")
     * )
     */   
    public function index(Request $request)
    {
        $data = [];
        foreach (Banner::where('status', 1)->get() as $banner) {
            $data[] = $banner->detail();
        }
        return $this->sendResponse($data);
    }
}
