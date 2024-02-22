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
            'lines.*.ingredient_name' => 'string',
            'lines.*.quantity_brut' => 'numeric',
            'lines.*.quantity_net' => 'numeric',
            'lines.*.price_unit' => 'numeric',
        ]);

        $recipe = Recipe::create([
            'name' => $recipeData['name'],
            'price' => $recipeData['price'],
        ]);

        foreach ($recipeData['lines'] as $lineData) {
            $recipe->lines()->create($lineData);
        }

       // Crear receta y Calcular el costo 
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
    
        // Ordenar las recetas por costo en orden ascendente
        $sortedRecipes = $recipes->sortBy(function ($recipe) {
            return $recipe->calculateCost();
        });
    
        // Tomar la primera receta (la más rentable)
        $mostProfitableRecipe = $sortedRecipes->first();
    
        // Verificar si $mostProfitableRecipe es un objeto válido
        if ($mostProfitableRecipe instanceof Recipe) {
            // Crear una respuesta JSON con información adicional
            return response()->json([
                'message' => 'Most profitable recipe found.',
                'recipe_info' => [
                    'id' => $mostProfitableRecipe->id,
                    'name' => $mostProfitableRecipe->name,
                    'price' => $mostProfitableRecipe->price,
                ],
                'cost' => $mostProfitableRecipe->calculateCost(),
            ]);
        } else {
            // Manejar caso en el que no se puede determinar la receta más rentable
            return response()->json([
                'message' => 'Unable to determine most profitable recipe.',
                'recipe_info' => null,
                'cost' => null,
            ], 500);
        }
    }
    

    public function getLeastProfitableRecipe()
    {
        
        $recipes = Recipe::with('lines')->get();
    
        
       // Ordenar las recetas por costo en orden descendente
        $sortedRecipes =  $recipes->sortByDesc(function ($recipe) {
            return $recipe->calculateCost();
        });
    
        // Tomar la primera receta (la menos rentable)
        $leastProfitableRecipe = $sortedRecipes->first();
    
        // Verificar si $leastProfitableRecipe es un objeto válido
        if ($leastProfitableRecipe instanceof Recipe) {
            // Crear una respuesta JSON con información adicional
            return response()->json([
                'message' => 'Least profitable recipe found.',
                'recipe_info' => [
                    'id' => $leastProfitableRecipe->id,
                    'name' => $leastProfitableRecipe->name,
                    'price' => $leastProfitableRecipe->price,
                ],
                'cost' => $leastProfitableRecipe->calculateCost(),
            ]);
        } else {
            // Manejar caso en el que no se puede determinar la receta menos rentable
            return response()->json([
                'message' => 'Unable to determine least profitable recipe.',
                'recipe_info' => null,
                'cost' => null,
            ], 500);
        }
    }
    
}
