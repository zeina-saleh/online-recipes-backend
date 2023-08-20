<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUpload extends Controller
{
    public function uploadImage(Request $request)
    {
        if ($request->has('images')) {
            $imageDataArray = $request->input('images'); // Array of base64 image data

            foreach ($imageDataArray as $index => $imageData) {
                $imageName = time() . "_$index.png"; // Create unique names for images
                $path = storage_path('app/public/images/' . $imageName);

                // Decode and save each image
                $decodedImageData = base64_decode($imageData);
                file_put_contents($path, $decodedImageData);
            }

            return response()->json(['message' => 'Images uploaded successfully']);
        } else {
            return response()->json(['message' => 'No images found'], 400);
        }
    }
}
