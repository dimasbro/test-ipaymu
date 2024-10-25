<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpg,png,pdf,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return $validator->errors()->all();
        }

        $path = $request->file('file')->store('uploads', 'public');

        $url = Storage::url($path);

        return response()->json(['url' => $url], 201);
    }

    public function download($filename)
    {
        $path = storage_path('app/public/uploads/' . $filename);

        if (!file_exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->download($path);
    }
}
