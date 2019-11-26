<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonstersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monsters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');                 //multilang
            $table->string('japanese_name');
            $table->text('description');          //miltilang
            $table->text('japanese_description');
            $table->string('fav_food');             //multiselect                      => comes from food table
            $table->string('nature');               //multiselect dragselect           => comes from nature table
                                                    // => the first and the last values represets most and least accurate natures exhibit by monster
            $table->integer('health');
            $table->string('energy');
            $table->string('found_in');             //multiselect dragselect multilang => comes from location table
            // => the first and the last values repesents the most and the least likly hood of finding the monster in the given area
            $table->timestamps();
        });

        // Full Text Index
        DB::statement('ALTER TABLE monsters ADD FULLTEXT fulltextsearch (name, japanese_name, description, japanese_description)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monsters');
    }
}
