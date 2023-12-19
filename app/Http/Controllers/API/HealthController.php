<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\Health;

class HealthController extends ApiBaseController
{
    /**
     * Health list api 健康咨询记录
     *
     * @OA\Get(
     *  path="/api/health",
     *  tags={"User"},
     *  @OA\Parameter(name="perpage",       in="query",required=false,explode=true,@OA\Schema(type="integer"),description="items per page"),
     *  @OA\Parameter(name="page",          in="query",required=false,explode=true,@OA\Schema(type="integer"),description="page num"),
     *  @OA\Response(response=200,description="successful operation")
     * )
     */
    public function index(Request $request)
    {
        $records = $this->user->healths();
        $total = $records->count();
        $perpage = $request->input('perpage', 20);
        $data = [
            'titles' => ["detail" => __('Title'), 'status_label' => __('Status'), 'date' => __('Date')],
            'total' => $total,
            'pages' => ceil($total/$perpage),
            'page' => $request->input('page', 1),
            'items' => []
        ];
        $records = $records->paginate($perpage);
        foreach ($records as $record) {
            $info = $record->info();
            $data['items'][] = [
                'id' => $record->id,
                'detail' => mb_strlen($record->detail) > 10 ? mb_substr($record->detail, 0, 10) . "..." : $record->detail,
                'status_label' => $info['status_label'] ??null,
                'date' => $record->created_at ? $record->created_at->toDateString() : null
            ];
        }
        return $this->sendResponse($data);
    }

    /**
     * Health detail api 健康咨询记录详情
     *
     * @OA\Get(
     *  path="/api/health/{id}",
     *  tags={"User"},
     *  @OA\Parameter(name="id",       in="path",required=false,explode=true,@OA\Schema(type="integer"),description="health record id"),
     *  @OA\Response(response=200,description="successful operation")
     * )
     */
    public function show($id)
    {
        if (!$health = Health::find($id)) {
            return $this->sendError("no health record found");
        }
        return $this->sendResponse($health->detail());
    }

    /**
     * Create a health 提交一个健康咨询
     *
     * @OA\Post(
     *   path="/api/health",
     *   tags={"User"},
     *   @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(property="detail", type="string", description="健康状况描述及问题"),
     *               @OA\Property(property="imgs[]", type="array", description="img urls, 病例及症状", collectionFormat="multi", @OA\Items(type="string")),
     *           )
     *       )
     *   ),
     *  @OA\Response(response=200,description="successful operation"),
     *  security={{ "api_key":{} }}
     * )
     */
    public function create(Request $request)
    {
        $health = Health::create([
            'store_id' => $this->user->store_id,
            'user_id' => $this->user->id,
            'detail' => $request->input('detail'),
            'suggestion' => null,
        ]);
        if ($array = $request->input('imgs')) {
            foreach ($array as $img){
                $path = public_path(str_replace(config('app.url'), '', $img));
                $health->addMedia($path)->toMediaCollection('main');
            }
        }
        return $this->sendResponse($health->detail());
    }

}
