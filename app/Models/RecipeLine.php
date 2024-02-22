<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeLine extends Model
{
    use HasFactory;
    protected $fillable = ['ingredient_name', 'quantity_brut', 'quantity_net', 'price_unit'];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function calculateCost()
    {
        return $this->quantity_brut * $this->price_unit;
    }
}