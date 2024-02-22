<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;

class OtherRecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recipe = Recipe::create([
            'name' => 'OtraReceta',
            'price' => 15,
        ]);

        $ingredients = [
            [
                'ingredient_name' => 'IngredienteX',
                'quantity_brut' => 500,
                'quantity_net' => 100,
                'price_unit' => 2,
            ],
            
        ];

        foreach ($ingredients as $ingredient) {
            $recipe->lines()->create($ingredient);
        }
    
    }
}
