<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Recipe;
use App\Models\IngredientRecipe;
use App\Models\Unit;

class Ingredient extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'unit_id'
    ];

    /*public function recipe(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class)->using(IngredientRecipe::class);
    }*/

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function recipes()
{
    return $this->belongsToMany(Recipe::class)
        ->using(IngredientRecipe::class)
        ->withPivot('quantity');
        
}
}
