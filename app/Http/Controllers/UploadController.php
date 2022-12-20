<?php

namespace App\Http\Controllers;

use Dcblogdev\Dropbox\Facades\Dropbox;
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

        // $path = Storage::disk('s3')->put('images', $request->image,  'public');
        // $path = Storage::disk('s3')->url($path);

        //create upload image to dropbox

        $image = $request->file('image');

        // $file = Dropbox::files()->createFolder('imagetb');
        // $file = Dropbox::files()->upload($imageName, $image);
        $file = Dropbox::files()->listContents($path = '');
        $file = Dropbox::files()->search('vps');

        /* Store $imageName name in DATABASE from HERE */

        $json = [
            'success' => true,
            'message' => 'Image uploaded successfully',
            'image' => $file
        ];
        return response()->json($json);
    }
}
