<?php

namespace App\Http;

use App\User;
use App\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public static function buildClientAvatar(Client $client, $size = 36, $class = 'avatar')
    {
        return "<img data-filter-by='alt' width=".$size." height=".$size." alt='".$client->name."'".
                    (empty($client->avatar) ? (" class='".$class."' avatar='".$client->name."'") : 
                                            (" class='".$class."' src='".Storage::url($client->avatar)."'"))."/>";
    }

    public static function getProgressColor($progress)
    {
        $color = '';

        if($progress<=15){
            $color='bg-danger';
        }else if ($progress > 15 && $progress <= 33) {
            $color='bg-warning';
        } else if ($progress > 33 && $progress <= 70) {
            $color='bg-primary';
        } else {
            $color='bg-success';
        }    

        return $color;
    }

    static function fragment($route, $fragment) 
    {
        return route($route) . "/#{$fragment}";
    }
}