<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\RecipeLine;
class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $recipe = Recipe::create([
            'name' => 'Cubalibre',
            'price' => 10,
        ]);

        $ingredients = [
            ['CocaCola', 1000, 200, 1],
            ['Ron', 1000, 200, 3],
            ['Hielo', 2, 2, 0.05],
        ];

        foreach ($ingredients as $ingredient) {
            $recipe->lines()->create([
                'ingredient_name' => $ingredient[0],
                'quantity_brut' => $ingredient[1],
                'quantity_net' => $ingredient[2],
                'price_unit' => $ingredient[3],
            ]);
        }
    }
}
