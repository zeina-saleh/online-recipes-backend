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

        return response()->json([
            'recipe' => [
                "id" => $recipe->id,
                "title" => $recipe->title,
                "description" => $recipe->description,
                "directions" => $recipe->directions,
            ],
            'comments' => $recipe->comments,
            'likes' => $recipe->likes,
            'images' => $recipe->images,
            'cuisine' => $recipe->cuisine,
            'ingredients' => $recipe->ingredients,
        ]);
    }
}
