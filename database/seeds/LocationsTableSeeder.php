<?php

use App\Laravue\Models\Location;
use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = ["San José (or San Jose)", "San Antonio", "Santa Maria (or Santa María)", "Santa Rosa", 
                 "San Pedro", "San Juan", "San Francisco", "San Miguel", "San Isidro", "Esperanza (or La Esperanza)", 
                 "Santa Cruz", "San Rafael", "Santa Rita", "Santa Ana", "Buenavista", "Aleksandrovka", "Buena Vista", 
                 "San Vicente", "Hoseynabad", "Gradina", "Mikhaylovka", "San Luis", "Buenos Aires", "Ivanovka", "Aliabad", 
                 "Kamenka", "Quebrada Honda", "El Carmen", "San Martin", "San Pablo", "Ojo de Agua", "San Lorenzo", "Santo Domingo", 
                 "Nikolayevka", "La Laguna", "Berezovka", "Santa Barbara", "El Porvenir", "Santiago", "Santa Isabel", "Union", 
                 "Santa Teresa", "Cerro Negro", "Santa Lucia", "La Palma", "Cerro Colorado", "Santa Elena", "San Agustin", "Las Delicias",];

        $japanese_name = ["サンノゼ（またはサンノゼ）", "サンアントニオ", "サンタマリア（またはサンタマリア）", "サンタローザ", "サンペドロ", 
                          "サンファン", "サンフランシスコ", "サンミゲル", "サンイシドロ", "Hope（またはLa Esperanza）", "サンタクルーズ", 
                          "サンラファエル", "サンタリタ", "サンタアナ", "ブエナビスタ", "アレクサンドロフカ", "良い眺め", "セントビンセント", 
                          "ホセイナバード", "グラディナ", "ミハイロフカ", "セントルイス", "ブエノスアイレス", "イワノフカ", "`アリアバード", 
                          "カメンカ", "ケブラーダホンダ", "エルカルメン", "サンマルティン", "セントポール", "ウォーターアイ", "サンロレンツォ", 
                          "サントドミンゴ", "ニコラエフカ", "ラグーン", "ベレゾフカ", "サンタバーバラ", "未来", "サンティアゴ", "サンタイザベル", 
                          "連合", "サンタテレサ", "セロネグロ", "セントルシア", "ラパルマ", "コロラドコロラド", "セントヘレナ", "サンアグスティン", "喜び",];

        $locations = array_combine($name, $japanese_name);

        foreach($locations as $n => $jn)
        {
            // Location::create(['name' => $n, 'japanese_name' => $jn]);
            Location::create(['name' => "{\"en\": \"$n\", \"ja\": \"$jn\"}"]);
        }
    }
}
