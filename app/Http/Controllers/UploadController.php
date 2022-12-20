<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ImageKit\ImageKit;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // $path = Storage::disk('s3')->put('images', $request->image,  'public');
        // $path = Storage::disk('s3')->url($path);

        $image = $request->file('image');
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('img'), $imageName);

        $public_key = env('IMAGEKIT_KEY');
        $your_private_key = env('IMAGEKIT_PRIVATE_KEY');
        $url_end_point = env('IMAGEKIT_ENDPOINT');

        $imageKit = new ImageKit(
            $public_key,
            $your_private_key,
            $url_end_point
        );

        // Upload Image - Binary
        $uploadFile = $imageKit->uploadFile([
            "file" => fopen(public_path('img') . '/' . $imageName, "r"),
            "fileName" => $imageName
        ]);

        $file = $uploadFile;

        $json = [
            'success' => true,
            'message' => 'Image uploaded successfully',
            'image' => $file->result->url
        ];
        return response()->json($json);
    }
}
