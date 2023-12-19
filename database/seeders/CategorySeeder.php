<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if ($data = (config('seed')['category'] ?? null)) {
            foreach ($data as $item) {
                $c = Category::create($item);
                echo "create category $c->name \n";
            }
        }
    }
}
