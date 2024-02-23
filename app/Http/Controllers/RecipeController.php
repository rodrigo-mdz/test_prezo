<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;


class RecipeController extends Controller
{
    public function createRecipe(Request $request)
    {
        $recipeData = $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'lines' => 'array',
            'lines.*.name' => 'string', 
            'lines.*.quantity_brut' => 'numeric',
            'lines.*.quantity_net' => 'numeric',
            'lines.*.price_unit' => 'numeric',
        ]);

        $recipe = Recipe::create([
            'name' => $recipeData['name'],
            'price' => $recipeData['price'],
        ]);

        foreach ($recipeData['lines'] as $lineData) {
            // Si la línea es una receta, calcular su costo y asignarlo a la línea
            if (isset($lineData['recipe_id'])) {
                $subRecipe = Recipe::find($lineData['recipe_id']);
                $lineData['cost'] = $subRecipe->calculateCost();
            }

            $recipe->lines()->create($lineData);
        }

        // Calcular el costo total de la receta 
        $cost = $recipe->calculateCost();
        $recipe->cost = $cost;
        $recipe->save();

        return response()->json(['message' => 'Recipe created successfully']);
    }

    public function getSortedRecipes()
{
    $recipes = Recipe::with('lines')->get();
    $sortedRecipes = $recipes->sortBy(function ($recipe) {
        return $recipe->calculateCost();
    })->values();

    $sortedRecipes = $sortedRecipes->reject(function ($recipe) {
        return $recipe->cost === null || $recipe->cost == 0;
    });

    return response()->json($sortedRecipes);
}


public function getMostProfitableRecipe()
{
    $recipes = Recipe::with('lines')->get();

    // Filtrar recetas que tengan costo y precio
    $validRecipes = $recipes->filter(function ($recipe) {
        return $recipe->cost !== null && $recipe->price !== null;
    });

    // Calcular rentabilidad para cada receta y obtener la más rentable
    $mostProfitableRecipe = $validRecipes->reduce(function ($carry, $recipe) {
        $profitability = $recipe->price - $recipe->cost;

        // Si $carry es null o la rentabilidad actual es mayor que la almacenada, actualizar
        if ($carry === null || $profitability > $carry['profitability']) {
            return [
                'recipe' => $recipe,
                'profitability' => $profitability,
            ];
        }

        return $carry;
    }, null);

    // Verificar si se encontró la receta más rentable
    if ($mostProfitableRecipe !== null) {
        return response()->json([
            'message' => 'Most profitable recipe found.',
            'recipe_info' => [
                'id' => $mostProfitableRecipe['recipe']->id,
                'name' => $mostProfitableRecipe['recipe']->name,
                'price' => $mostProfitableRecipe['recipe']->price,
            ],
            'cost' => $mostProfitableRecipe['recipe']->cost,
            'profitability' => $mostProfitableRecipe['profitability'],
        ]);
    } else {
        return response()->json([
            'message' => 'Unable to determine most profitable recipe.',
            'recipe_info' => null,
            'cost' => null,
            'profitability' => null,
        ], 500);
    }
}

    

    public function getLeastProfitableRecipe()
    {
        $recipes = Recipe::with('lines')->get();
    
        // Filtrar recetas que tengan costo y precio
        $validRecipes = $recipes->filter(function ($recipe) {
            return $recipe->cost !== null && $recipe->price !== null;
        });
    
        // Calcular rentabilidad para cada receta y obtener la menos rentable
        $leastProfitableRecipe = $validRecipes->reduce(function ($carry, $recipe) {
            $profitability = $recipe->price - $recipe->cost;
    
            // Si $carry es null o la rentabilidad actual es menor que la almacenada, actualizar
            if ($carry === null || $profitability < $carry['profitability']) {
                return [
                    'recipe' => $recipe,
                    'profitability' => $profitability,
                ];
            }
    
            return $carry;
        }, null);
    
        // Verificar si se encontró la receta menos rentable
        if ($leastProfitableRecipe !== null) {
            return response()->json([
                'message' => 'Least profitable recipe found.',
                'recipe_info' => [
                    'id' => $leastProfitableRecipe['recipe']->id,
                    'name' => $leastProfitableRecipe['recipe']->name,
                    'price' => $leastProfitableRecipe['recipe']->price,
                ],
                'cost' => $leastProfitableRecipe['recipe']->cost,
                'profitability' => $leastProfitableRecipe['profitability'],
            ]);
        } else {
            return response()->json([
                'message' => 'Unable to determine least profitable recipe.',
                'recipe_info' => null,
                'cost' => null,
                'profitability' => null,
            ], 500);
        }
    }
    
    
}
