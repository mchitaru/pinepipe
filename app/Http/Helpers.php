<?php

namespace App\Http;

use App\User;
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
        $path = $file->store('users/'.\Auth::user()->creatorId(), 'local');

        return $path;
    }

    public static function buildAvatar(User $user, $size = 36, $class = 'avatar')
    {
        return "<img data-filter-by='alt' width=".$size." height=".$size." alt='".$user->name."'".
                    (empty($user->avatar) ? (" class='".$class."' avatar='".$user->name."'") : 
                                            (" class='".$class."' src='".Storage::url($user->avatar)."'"))."/>";
    }
}