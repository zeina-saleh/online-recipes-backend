<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Recipe;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Recipe_Image;
use App\Models\Cuisine;
use App\Models\Ingredient;
use App\Models\Unit;
use App\Models\IngredientRecipe;
use GuzzleHttp\Promise\Create;

class RecipesController extends Controller
{

    function getRecipe($recipeId = null)
    {

        if ($recipeId) {
            $recipe = Recipe::with(['comments.user', 'likes.user', 'images', 'cuisine', 'ingredients.unit'])
                ->find($recipeId);

            $getRecipe = [
                "id" => $recipe->id,
                "cuisine" => $recipe->cuisine->name,
                "title" => $recipe->title,
                "description" => $recipe->description,
                "directions" => $recipe->directions,
                "created_at" => $recipe->created_at,
                "updated_at" => $recipe->updated_at,
                "comments" => $recipe->comments->map(function ($comment) {
                    return [
                        "id" => $comment->id,
                        "user_id" => $comment->user_id,
                        "recipe_id" => $comment->recipe_id,
                        "text" => $comment->text,
                        "created_at" => $comment->created_at,
                        "updated_at" => $comment->updated_at,
                        "username" => $comment->user->name,
                    ];
                }),
                "likes" => $recipe->likes->map(function ($like) {
                    return [
                        "id" => $like->id,
                        "user_id" => $like->user_id,
                        "created_at" => $like->created_at,
                        "username" => $like->user->name,
                    ];
                }),
                "images" => $recipe->images,
                "ingredients" => $recipe->ingredients->map(function ($ingredient) {
                    return [
                        "id" => $ingredient->id,
                        "name" => $ingredient->name,
                        "quantity" => $ingredient->pivot->quantity,
                        "unit" => $ingredient->unit->name,
                        "unit_id" => $ingredient->pivot->unit_id,
                    ];
                })
            ];

            return response()->json($getRecipe);
        } else {
            $recipes = Recipe::withCount('likes')
                ->with(['images' => function ($query) {
                    $query->select('recipe_id', 'image_url');
                }, 'cuisine'])
                ->get()
                ->map(function ($recipe) {
                    return [
                        "id" => $recipe->id,
                        "cuisine" => $recipe->cuisine->name,
                        "title" => $recipe->title,
                        "description" => $recipe->description,
                        "likes_count" => $recipe->likes_count,
                        "images" => $recipe->images->map(function ($image) {
                            return [
                                "image_url" => $image->image_url,
                            ];
                        }),
                    ];
                });

            return response()->json($recipes);
        }
    }
    public function likeRecipe(Request $request, $recipeId)
    {
        $user = Auth::user();
        $recipe = Recipe::findOrFail($recipeId);

        $existingLike = Like::where('user_id', $user->id)
            ->where('recipe_id', $recipe->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json(['message' => 'Like removed']);
        }

        $like = new Like();
        $like->user_id = $user->id;
        $like->recipe_id = $recipe->id;
        $like->save();

        return response()->json(['message' => 'Recipe liked']);
    }

    public function addComment(Request $request, $recipeId)
    {
        $user = Auth::user();
        $recipe = Recipe::findOrFail($recipeId);

        $this->validate($request, [
            'text' => 'required|string',
        ]);

        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->recipe_id = $recipe->id;
        $comment->text = $request->input('text');
        $comment->save();

        $commentWithUser = Comment::with('user')->find($comment->id);

        return response()->json([
            'message' => 'Comment added',
            'comment' => $commentWithUser,
        ]);
    }
}
