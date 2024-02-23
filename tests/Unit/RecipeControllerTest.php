<?php

namespace Tests\Unit;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Recipe;



class RecipeControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;
    /**
     * A basic test .
     *
     * @return void
     */
    public function testCreateRecipe()
    {
        $postData = [
            'name' => 'Bolognesa',
            'price' => 12.99,
            'lines' => [
                [
                    'ingredient_name' => 'Carne picada',
                    'quantity_brut' => 1.8,
                    'quantity_net' => 0.8,
                    'price_unit' => 1.67,
                ],
               
            ],
        ];

        $response = $this->json('POST', '/api/recipes', $postData);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Recipe created successfully']);

    }
    public function testGetSortedRecipes()
    {
        $response = $this->get('/api/recipes');
        $response->assertStatus(200)->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'price',
                'lines' => [
                    '*' => ['id', 'ingredient_name', 'quantity_brut', 'quantity_net', 'price_unit'],
                ],
                'cost',
            ],
        ]);
    }
    public function testGetMostProfitableRecipe()
    {
        // Crear algunas recetas de prueba
        Recipe::factory()->create(['name' => 'Recipe1', 'price' => 10]);
        Recipe::factory()->create(['name' => 'Recipe2', 'price' => 15]);
    
        $response = $this->get('/api/recipes/most-profitable');
    
        $response->assertStatus(200)->assertJsonStructure([
            'message',
            'recipe_info' => ['id', 'name', 'price'],
            'cost',
        ]);
    
        $responseData = $response->json();
    
        // Verificar que la respuesta contiene la información esperada
        $this->assertEquals('Most profitable recipe found.', $responseData['message']);
        $this->assertArrayHasKey('recipe_info', $responseData);
        $this->assertArrayHasKey('cost', $responseData);
    
        // Si se ha encontrado la receta más rentable, verificar su estructura
        if ($responseData['recipe_info']) {
            $this->assertArrayHasKey('id', $responseData['recipe_info']);
            $this->assertArrayHasKey('name', $responseData['recipe_info']);
            $this->assertArrayHasKey('price', $responseData['recipe_info']);
        } else {
            // Manejar caso en el que no se puede determinar la receta más rentable
            $this->assertNull($responseData['cost']);
        }
    }
    public function testGetLeastProfitableRecipe()
    {
        // Crear algunas recetas de prueba
        Recipe::factory()->create(['name' => 'Recipe3', 'price' => 20]);
        Recipe::factory()->create(['name' => 'Recipe4', 'price' => 12]);
    
        $response = $this->get('/api/recipes/least-profitable');
    
        $response->assertStatus(200)->assertJsonStructure([
            'message',
            'recipe_info' => ['id', 'name', 'price'],
            'cost',
        ]);
    
        $responseData = $response->json();
    
        // Verificar que la respuesta contiene la información esperada
        $this->assertEquals('Least profitable recipe found.', $responseData['message']);
        $this->assertArrayHasKey('recipe_info', $responseData);
        $this->assertArrayHasKey('cost', $responseData);
    
        // Si se ha encontrado la receta menos rentable, verificar su estructura
        if ($responseData['recipe_info']) {
            $this->assertArrayHasKey('id', $responseData['recipe_info']);
            $this->assertArrayHasKey('name', $responseData['recipe_info']);
            $this->assertArrayHasKey('price', $responseData['recipe_info']);
        } else {
            // Manejar caso en el que no se puede determinar la receta menos rentable
            $this->assertNull($responseData['cost']);
        }
    }
}
