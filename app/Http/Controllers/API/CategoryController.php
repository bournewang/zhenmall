<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends ApiBaseController
{
    /**
     * Category list api
     *
     * @OA\Get(
     *  path="/api/categories",
     *  tags={"Category and Goods"},
     *  @OA\Response(response=200,description="successful operation")
     * )
     */   
    public function index(Request $request)
    {
        $data = [];
        foreach (Category::where('status', '!=', (new Category)->off_shelf)->get() as $category) {
            $data[] = $category->info();
        }
        return $this->sendResponse($data);
    }
}
