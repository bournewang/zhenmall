<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\City;
use App\Models\District;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
//        District::truncate();
//        City::truncate();
//        Province::truncate();
        $locations = $this->cityLocations();
        $i = 0;
        $fp = fopen('./database/china-area.v4.csv', 'r');
        while ($row = fgetcsv($fp)) {
            //echo $row[0] . ", ".$row[1]."\n";

            $code = $row[0];
            $name = $row[1];
            if (!$code || !$name) continue; // skip empty line

            // if province, skip
            if (substr($code, 2) == '0000') {

                echo "create province: ## $name \n";
                Province::create(['code' => $code, 'name' => $name]);

                // if ($i++ > 2)break;
            //  if city
            } elseif (substr($code, 4) == '00') {
                $province_code = substr($code, 0, 2) . "0000";
                if (!$province = Province::where('code', $province_code)->first()) {
                    echo "ERROR: province $province_code not found\n";
                    continue;
                }
                // if ($i++ > 10)continue;
                City::create([
                    'province_id' => $province->id,
                    'code' => $code,
                    'name' => $name,
                    'lng' => isset($locations[$name]['lng']) ? $locations[$name]['lng'] : null,
                    'lat' => isset($locations[$name]['lat']) ? $locations[$name]['lat'] : null,
                ]);
                // echo "create city: #### $name \n";
            //  if district
            } else {
                // $province_code = substr($code, 0, 2) . "0000";
                // if (!$province = Province::where('code', $province_code)->first()) {
                //     echo "ERROR: province $province_code not found\n";
                //     continue;
                // }
                $city_code = substr($code, 0, 4) . "00";
                if (!$city = City::where('code', $city_code)->first()) {
                    echo "ERROR: city $city_code not found\n";
                    continue;
                }
                District::create(['city_id' => $city->id, 'code' => $code, 'name' => $name]);
                #echo "create district: ###### $name \n";
            }
        }
        fclose($fp);
    }

    private function cityLocations()
    {
        return json_decode(file_get_contents('./database/city_geo_list_utf8.json'), 1);
    }
}
