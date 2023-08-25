<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecipeImage;
use App\Models\Recipe;

class ImageUpload extends Controller
{
    public function uploadImage(Request $request)
    {
        $recipe_id = request('recipeId');
        if ($request->has('images')) {
            $imageDataArray = $request->input('images'); // Array of base64 image data

            foreach ($imageDataArray as $index => $imageData) {
                $imageName = time() . "_$index.png"; // Create unique names for images
                $path = storage_path('app/public/images/' . $imageName);

                // Decode and save each image
                $decodedImageData = base64_decode($imageData);
                file_put_contents($path, $decodedImageData);

                $image = new RecipeImage();
                $image->image_url = $path;
                $image->recipe_id->$recipe_id;
                $image->save();
                $uploadedImagePaths[] = $path; // Store the path for response
        }

        return response()->json([
            'message' => 'Images uploaded successfully',
            'uploaded_paths' => $uploadedImagePaths,
        ]);
    } else {
        return response()->json(['message' => 'No images found'], 400);
    }
}
public function addRecipe(Request $request)
{
    try {

    $validatedData = $request->validate([
        'cuisine_id' => 'required',
        'title' => 'required',
        'description' => 'required',
        'directions' => 'required',
        'images.*' => 'required|string', 
        'ingredients' => 'required|array|min:1',
        'ingredients.*.id' => 'required|exists:ingredients,id',
        'ingredients.*.quantity' => 'required|numeric',
    ]);

    $recipe = new Recipe();
    $recipe->cuisine_id = $validatedData['cuisine_id'];
    $recipe->title = $validatedData['title'];
    $recipe->description = $validatedData['description'];
    $recipe->directions = $validatedData['directions'];
    $recipe->save();

    if ($request->has('images')) {
        $imageDataArray = $request->input('images'); 
        foreach ($imageDataArray as $index => $imageData) {
            $imageName = time() . "_$index.png"; 
            $path = storage_path('app/public/images/' . $imageName);
            $decodedImageData = base64_decode($imageData);
            file_put_contents($path, $decodedImageData);
            $image = new RecipeImage();
            $image->image_url = $path;
            $image->recipe_id=$recipe->id;
            $image->save();
            $uploadedImagePaths[] = $path;
        }
    }

    foreach ($validatedData['ingredients'] as $ingredient) {
        $recipe->ingredients()->attach($ingredient['id'], ['quantity' => $ingredient['quantity']]);
    }
    $response = [
        'message' => 'Recipe added successfully',
        'recipe' => [
            'id' => $recipe->id,
            'cuisine' => $recipe->cuisine,
            'title' => $recipe->title,
            'description' => $recipe->description,
            'images' => $recipe->images,
            'ingredients' => $recipe->ingredients,
        ],
    ];

    return response()->json(['message' => 'Recipe added successfully'], 200);
} catch (\Exception $e) {
    return response()->json(['error' => $e->getMessage()], 500);
}}
}
