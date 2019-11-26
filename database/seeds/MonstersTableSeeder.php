<?php

use App\Laravue\Models\Monster;
use Illuminate\Database\Seeder;

class MonstersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 50;
        factory(Monster::class, $count)->create();
    }
}
