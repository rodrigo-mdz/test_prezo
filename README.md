¡Hey 👋! 👨🏻‍💻

             ¡Revisa el contenido 👇!   
# test_prezo
# Recipe API

Este proyecto es una API simple para gestionar recetas y sus detalles. Proporciona endpoints para crear recetas, obtener recetas ordenadas por costo, obtener la receta más rentable y obtener la receta menos rentable.

## Requisitos

- PHP 7.4 o superior
- Composer
- Laravel 8
- Git

## Instalación

1. Clona el repositorio o descarga el codigo / branch master o feat-create-api-rest-laravel-recipes ,las dos ramas estan actualizadas: 
2.Accede al directorio del proyecto en la consola
3.composer install (instala dependencias) en la carpeta del proyecto .
4.Copia el archivo de configuración: cp .env.example .env Configura la base de datos en el archivo .env con tus credenciales.
5.php artisan key:generate
6.Ejecuta las migraciones y los seeders:php artisan migrate --seed
7.Inicia el servidor de desarrollo: php artisan serve
8.La API estará disponible en http://localhost:8000 o config de su php
Ejecutar pruebas unitarias
Este proyecto incluye pruebas unitarias basicas . Para ejecutarlas, utiliza el siguiente comando:
php artisan test


Endpoints:

Crea una nueva receta: 
tipo:POST 
end point : /api/recipes

ejemplo: POST | http://127.0.0.1:8000/api/recipes
Parámetros:

name: Nombre de la receta (cadena, obligatorio).
price: Precio de la receta (numérico, obligatorio).
lines: Líneas de la receta (array de objetos, opcional).

Ejemplo del json a enviar para crear receta:
{
    "name": "Nueva Receta_2",
    "price": 12.99,
    "lines": [
        {
            "ingredient_name": "Ingrediente 1",
            "quantity_brut": 200,
            "quantity_net": 180,
            "price_unit": 0.5
        },
        {
            "ingredient_name": "Ingrediente 2",
            "quantity_brut": 150,
            "quantity_net": 130,
            "price_unit": 1.0
        }
    ]
}



GET /api/recipes: Obtiene todas las recetas ordenadas por costo.

Respuesta: Lista de recetas con sus detalles y costo .
ejemplo:
[
  {
    "id": 1,
    "name": "Receta 1",
    "price": 10,
    "lines": [
      {
        "id": 1,
        "ingredient_name": "Ingrediente 1",
        "quantity_brut": 200,
        "quantity_net": 180,
        "price_unit": 2.5
      }
    ],
    "cost": 25.0
  },
  {
    "id": 2,
    "name": "Receta 2",
    "price": 15,
    "lines": [
      {
        "id": 2,
        "ingredient_name": "Ingrediente 2",
        "quantity_brut": 250,
        "quantity_net": 200,
        "price_unit": 3.0
      }
    ],
    "cost": 45.0
  }
]

GET /api/recipes/most-profitable: Obtiene la receta más rentable.

Respuesta: Detalles de la receta más rentable y su costo.
ejemplo de respuesta:
{
  "message": "Receta más rentable encontrada.",
  "recipe_info": {
    "id": 1,
    "name": "Receta 1",
    "price": 10
  },
  "cost": 25.0
}


GET /api/recipes/least-profitable: Obtiene la receta menos rentable.

Respuesta: Detalles de la receta menos rentable y su costo.
ejemplo de respuesta:
{
  "message": "Receta menos rentable encontrada.",
  "recipe_info": {
    "id": 2,
    "name": "Receta 2",
    "price": 15
  },
  "cost": 45.0
}
