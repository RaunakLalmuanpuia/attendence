<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = [
            ['id'=>1,'code'=>'01','name' => 'Aizawl'],
            ['id'=>2,'code'=>'02','name' => 'Lunglei'],
            ['id'=>3,'code'=>'03','name' => 'Siaha'],
            ['id'=>4,'code'=>'04','name' => 'Champhai'],
            ['id'=>5,'code'=>'05','name' => 'Kolasib'],
            ['id'=>6,'code'=>'06','name' => 'Serchhip'],
            ['id'=>7,'code'=>'07','name' => 'Lawngtlai'],
            ['id'=>8,'code'=>'08','name' => 'Mamit'],
            ['id'=>9,'code'=>'09','name' => 'Khawzawl'],
            ['id'=>10,'code'=>'10','name' => 'Saitual'],
            ['id'=>11,'code'=>'11','name' => 'Hnahthial'],
        ];

        District::query()->upsert($districts, ['id']);
    }
}
