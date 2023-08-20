<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Recipe_Image;
use App\Models\Cuisine;
use App\Models\Ingredient;
use App\Models\Unit;
use App\Models\IngredientRecipe;

class RecipesController extends Controller
{
    function getRecipe($recipeId)
    {

        $recipe = Recipe::with(['comments', 'likes', 'images', 'cuisine', 'ingredients.unit'])
            ->find($recipeId);

        // Now you can access the related data
        $recipeTitle = $recipe->title;
        $recipeComments = $recipe->comments;
        $recipeLikes = $recipe->likes;
        $recipeImages = $recipe->images;
        $recipeCuisine = $recipe->cuisine;

        // Accessing ingredients, quantities, and units
        $recipeIngredients = $recipe->ingredients;

        foreach ($recipeIngredients as $ingredient) {
            $ingredientName = $ingredient->name;
            $pivotData = $ingredient->pivot;
            $quantity = $pivotData->quantity;
            $unit = $ingredient->unit->name; // Access the unit name
            // Access other properties as needed
        }

        return response()->json([
            'recipe' => $recipe,
        ]);
    }
}
