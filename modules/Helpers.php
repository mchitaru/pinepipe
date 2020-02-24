<?php

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

    public static function buildAvatar($name, $avatar, $size = 36, $class = 'avatar')
    {
        return "<img data-filter-by='alt' width=".$size." height=".$size." alt='".$name."'".
                    (empty($avatar) ? (" class='".$class."' avatar='".$name."'") : 
                                            (" class='".$class."' src='".Storage::url($avatar)."'"))."/>";
    }

    public static function buildUserAvatar(User $user, $size = 36, $class = 'avatar')
    {
        $name = null;
        $avatar = null;

        if($user){
            $name = $user->name;
            $avatar = $user->avatar;
        }

        return Helpers::buildAvatar($name, $avatar, $size, $class);
    }

    public static function buildClientAvatar(Client $client, $size = 36, $class = 'avatar')
    {
        $name = null;
        $avatar = null;

        if($client){
            $name = $client->name;
            $avatar = $client->avatar;
        }

        return Helpers::buildAvatar($name, $avatar, $size, $class);
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