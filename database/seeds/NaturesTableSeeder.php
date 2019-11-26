<?php

use App\Laravue\Models\Nature;
use Illuminate\Database\Seeder;

class NaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $natures = ["Adventurous", "Helpful", "Affable", "Humble", "Capable", "Imaginative", "Charming", 
                    "Impartial", "Confident", "Independent", "Conscientious", "Keen", "Cultured", "Meticulous", "Dependable", 
                    "Observant", "Discreet", "Optimistic", "Dutiful", "Persistent", "Encouraging", "Precise", "Exuberant", 
                    "Reliable", "Fair", "Sociable", "Fearless", "Trusting", "Gregarious", "Valiant", "Arrogant", 
                    "Quarrelsome", "Boorish", "Rude", "Bossy", "Sarcastic", "Conceited", "Self-centered", "Cowardly", 
                    "Slovenly", "Dishonest", "Sneaky", "Finicky", "Stingy", "Impulsive", "Sullen", "Lazy", 
                    "Surly", "Malicious", "Thoughtless", "Obnoxious", "Unfriendly", "Picky", "Unruly", "Pompous", 
                    "Vulgar", ];
        
        foreach($natures as $nature)
        {
            Nature::create(['name' => $nature]);
        }
    }
}
