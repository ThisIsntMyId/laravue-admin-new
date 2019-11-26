<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Laravue\Models\Food;
use App\Laravue\Models\Location;
use App\Laravue\Models\Monster;
use App\Laravue\Models\Nature;
use Faker\Generator as Faker;

$factory->define(Monster::class, function (Faker $faker) {
    $names = ["Zackary Pace", "Lorelei Castaneda", "Malakai Moses", "Nathanial Salazar", "Abigayle Luna", "Sloane Hobbs", 
              "Shayna Dunlap", "Elizabeth Mayo", "Zackery Yu", "Ahmed Farrell", "Marquise Lynch", "Chace Rosales", "Ashanti Kidd", 
              "Arely Carey", "Finley Dean", "Humberto Barr", "Donovan Mcpherson", "Marlon Novak", "Alma Cuevas", "Yahir Fuentes", 
              "Ian Hawkins", "Brylee Hale", "Lane Villarreal", "Evelin Delgado", "Giuliana Mahoney", "Heaven Young", "Dario Cabrera", 
              "Kareem White", "Skyler Gregory", "Zara Jefferson", "Carl Peck", "Alexandra Huber", "Odin Ayala", "Case Pratt", "Viviana Reed", 
              "Zane Kline", "Jean Blanchard", "Chelsea Cowan", "Elianna Oneal", "Lea Stephens", "Adrianna Torres", "Tripp Carson", "Devon Maldonado", 
              "Maurice Charles", "Roger Prince", "Byron Castillo", "Maribel Cole", "Brandon Obrien", "Tomas Ellison", "Payton Cervantes", ];
    $japanese_names = ["ザカリーペース", "ローレライ・カスタネダ", "マラカイモーゼス", "ナタニアルサラザール", "アビゲイル・ルナ", "スローン・ホブス", 
                       "シェイナ・ダンラップ", "エリザベス・メイヨー", "ザッカリー・ユー", "アーメド・ファレル", "マーキスリンチ", "チェイス・ロザレス", 
                       "アシャンティ・キッド", "アリー・キャリー", "フィンリーディーン", "ウンベルト・バール", "ドノバン・マクファーソン", "マーロン・ノバック", 
                       "アルマ・クエバス", "ヤヒルフエンテス", "イアン・ホーキンス", "ブリーリー・ヘイル", "レーンビジャレアル", "エブリン・デルガド", 
                       "ジュリアナ・マホニー", "ヘブン・ヤング", "ダリオ・カブレラ", "カリーム・ホワイト", "スカイラー・グレゴリー", "ザラ・ジェファーソン", 
                       "カール・ペック", "アレクサンドラ・フーバー", "オーディン・アヤラ", "ケースプラット", "ヴィヴィアナ・リード", "ゼーン・クライン", 
                       "ジャン・ブランチャード", "チェルシー・コーワン", "エリアナ・ワンアル", "リー・スティーブンス", "アドリアナ・トーレス", "トリップカーソン", 
                       "デボン・マルドナド", "モーリス・チャールズ", "ロジャー王子", "バイロンカスティージョ", "マリベル・コール", "ブランドン・オブライエン", 
                       "トマス・エリソン", "ペイトン・セルバンテス", ];
    $japanese_description = ["対記ニラエコ新体ル転際どフぶょ和東ユセミ揺遣ロワ求質じにづ要14促縮繰携3
                   す。卑トヒモタ小芸面71趣園サ収閉ノワケ面述ヱセ洋席んり木強キ値気ぞ和全ウ社課がはそ防能クフ", 
                   "覚しん作5断フじ閣能ぼゃかょ付日ヱモメリ内碁要58休害ヲキ申覧キタモ体止タ
                   クネ員5純ンべ。53親キアネソ指開ナハエネ場間をらわ天要ルモ困転すへぽに業務うづん",
                   "億施がル北中ち却地メロユヱ白体クミセ下東りやへ
                   ぐ液25申レ阜日ばみ談立づ。各ずスフ望方よが真限すお真工ロハレ結上編ンぼみ到人ホ医政",
                   "キ措事ひちラょ造優そ続位見ゆげぶド競治条ヌヱホキ手島ゆ東践巨優独クね。
                   新へやつ人雪キカコ女地びク芸額へそらほ世今ニホ輪夜ぎリ連上を込帯べりげ勢", 
                   "文前61立20重セワ否巡堂衛完ーふづゃ。意イク朝一クぼ士韓へのあひ全供メ旅8辺モツス
                   ト康見タク歌球テツヒ本外る掲億容わ決真ぎそクて", 
                   "夫経ヱケ地今ぶさ載首取笑れみ。島町マ通町オ形75難スナ就明ルんご話米林フヒヨ募史ど
                   ニイ画間オクミ員夏ネヒオ達94上首題7始表だぴリ静売ユワ表米要ちクすい実軽ぼ識台首双脈フんぽぼ"];
    $fav_food = Food::all()->random($faker->numberBetween($min=3, $max=9))->map(function($food, $key) { return $food->id;})->implode(',');
    $nature = Nature::all()->random($faker->numberBetween($min=3, $max=9))->map(function($food, $key) { return $food->id;})->implode(',');
    $location = Location::all()->random($faker->numberBetween($min=3, $max=9))->map(function($food, $key) { return $food->id;})->implode(',');
    $nameIndex = $faker->numberBetween($min = 0, $max = 49);
    return [
        'name' => $names[$nameIndex],
        'japanese_name' => $japanese_names[$nameIndex],
        'description' => $faker->paragraph(),
        'japanese_description' => $japanese_description[$faker->numberBetween($min = 0, $max = 5)],
        'fav_food' => $fav_food,
        'nature' => $nature,
        'health' => $faker->numberBetween($min = 100, $max = 1000),
        'energy' => $faker->numberBetween($min = 100, $max = 1000),
        'found_in' => $location,
    ];
});
