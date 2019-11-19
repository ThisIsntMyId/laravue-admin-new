<?php

use App\Laravue\Models\Category;
use Illuminate\Database\Seeder;

class CategoriessTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = collect(['Red', 'Blue', 'Green', 'Black', 'White', 'Orange', 'Yellow', 'Pink', 'Lime', 'Magenta', 'Peach', 'Purple', 'Brown', 'Aqua', 'Grey']);
        $categories->each(function ($category, $key) {
            Category::create(['name' => $category]);
        });
    }
}
