<?php

namespace App\Http;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Helpers 
{
    public static function storePublicFile(UploadedFile $file)
    {
        $path = $file->store('avatar/'.\Auth::user()->creatorId(), 'public');

        return $path;
    }

    public static function storePrivateFile(UploadedFile $file)
    {
        $path = $file->store('upload/'.\Auth::user()->creatorId(), 'local');

        return $path;
    }
}