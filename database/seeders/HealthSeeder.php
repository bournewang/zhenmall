<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class HealthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('seed.health') as $data){
            \App\Models\Health::create($data);
        }
    }
}