<?php

use App\Laravue\Models\Card;
use Illuminate\Database\Seeder;

class CardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 50;
        factory(Card::class,$count)->create();
    }
}
