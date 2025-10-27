<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time() . '.' . $request->upload->extension();
        $request->upload->storeAs('public/uploads/content', $imageName);

        return response()->json([
            'url' => asset('storage/uploads/content/' . $imageName)
        ]);
    }
}
