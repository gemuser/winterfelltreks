<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Upload an image for the CMS editor.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|file|image|mimes:jpeg,png,jpg,webp,svg,gif|max:12288',
        ]);

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = ($safeName ?: 'image') . '-' . time() . '.' . $extension;

        $destinationPath = public_path('images/uploads');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);

        return response()->json([
            'success' => true,
            'url' => '/images/uploads/' . $filename,
            'name' => $file->getClientOriginalName(),
        ]);
    }
}

