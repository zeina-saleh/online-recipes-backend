<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUpload extends Controller
{
    public function uploadImage(Request $request)
    {
        /*$request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // adjust the allowed file types and max size as needed
        ]);*/

        if ($request->has('image')) {
            $imageData = base64_decode($request->input('image'));
            $imageName = time() . '.png';
            $path = storage_path('app/public/images/' . $imageName);
            file_put_contents($path, $imageData);
            return response()->json(['message' => 'Image uploaded successfully']);
        } else {
            return response()->json(['message' => 'Image not found'], 400);
        }
    }
}
