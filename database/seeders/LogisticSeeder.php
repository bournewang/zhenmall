<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Logistic;

class LogisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = json_decode(file_get_contents("./database/express.json"));
        $i=30;
        foreach ($data->showapi_res_body->expressList as $item){
            Logistic::create([
                'name' => $item->expName,
                'code' => $item->simpleName,
                'img'  => $item->imgUrl,
                'phone' => $item->phone,
                'url' => $item->url,
                'note' => $item->note,
                'sort' => $i++
            ]);
            echo "create logistic ".$item->expName."\n";
            // $i++;
        }
        $sorts = [
            '中通快递' => 1,
            '韵达快运' => 2,
            '圆通速递' => 3,
            '顺丰速运' => 4,
            '申通快递' => 5,
        ];
        foreach ($sorts as $name => $s) {
            if ($l = Logistic::where('name', $name)->first()) {
                $l->update(['sort' => $s]);
            }
        }
        echo "----------\n";
        $i-=30;
        echo "create $i logistics\n";
    }
}
