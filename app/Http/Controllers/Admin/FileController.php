<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FileController extends Controller
{
    public function upload(Request $request) {
        $request->validate([
            'file' => 'required|file',
        ]);

        //$secure_url = Cloudinary::upload($request->file('file')->getRealPath())->getSecurePath();

        $folder = config('cloudinary.upload_preset') . config('constants.EDITOR_PATH');
        try {
            $secure_url = cloudinary()->upload($request->file('file')->getRealPath(),['folder' => $folder])->getSecurePath();
        } catch (\Exception $e) {

        }
        
        if( $secure_url ) {
            return response()->json(['location' => $secure_url]);
        } else {
            return response()->json(['location' => '']);
        }
    }
}