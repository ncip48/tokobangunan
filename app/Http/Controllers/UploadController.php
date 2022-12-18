<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->image->extension();

        $path = Storage::disk('s3')->put('images', $request->image,  'public');
        $path = Storage::disk('s3')->url($path);

        /* Store $imageName name in DATABASE from HERE */

        $json = [
            'success' => true,
            'message' => 'Image uploaded successfully',
            'image' => $path
        ];
        return response()->json($json);
    }
}
