<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price'];

    public function lines()
    {
        return $this->hasMany(RecipeLine::class);
    }

    public function calculateCost()
    {
        return $this->lines->sum(function ($line) {
            return $line->calculateCost();
        });
    }
}
