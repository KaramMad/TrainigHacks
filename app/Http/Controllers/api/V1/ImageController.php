<?php

namespace App\Http\Controllers\api\V1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
class ImageController extends Controller
{
    public static function store($file,$directory){
        $destpath = public_path("Uploads/$directory/");
        $extension=$file->getClientOriginalExtension();
        $fileName=$directory."/".time() . '.' . $extension;
        $file->move($destpath, $fileName);
        return $fileName;
    }

    public static function update($file,$old_file,$directory){
        $path = public_path("Uploads/$directory/$old_file");
        if (File::exists($path)) {
            self::destroy($old_file);
        }
        $extension = $file->getClientOriginalExtension();
        $fileName=$directory."/".time() . '.' . $extension;
        $file->move("Uploads/$directory/", $fileName);
        return $fileName;
    }

    public static function destroy($file): void
    {
        $path = public_path("Uploads/$file");
        if (File::exists($path)) {
            File::delete($path);
        }
    }
}
