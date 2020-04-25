<?php

use App\User;
use App\Client;
use App\Project;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Helpers
{
    public static function localToUTC($timestamp)
    {
        return Carbon::parse($timestamp, \Auth::user()->timezone)->tz('UTC');
    }

    public static function utcToLocal($timestamp)
    {
        return Carbon::parse($timestamp, 'UTC')->tz(\Auth::user()->timezone);
    }

    public static function storePublicFile(UploadedFile $file)
    {
        $path = $file->store('avatar/'.\Auth::user()->creatorId(), 'public');

        return $path;
    }

    public static function buildAvatar($name, $avatar, $size = 36, $class = 'avatar')
    {
        return "<img data-filter-by='alt' width=".$size." height=".$size.
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

    public static function getPriorityBadge($priority)
    {
        switch($priority) {
            case 2:
                return '<span class="badge badge-success">'.Project::$priority[$priority].'</span>';
            case 1:
                return '<span class="badge badge-warning">'.Project::$priority[$priority].'</span>';
            default:
                return '<span class="badge badge-danger">' .Project::$priority[$priority].'</span>';
        }
    }

    public static function showDateForHumans($date, $caption = '')
    {
        if($date == null){
            return '---';
        }

        $date = \Helpers::utcToLocal($date);

        $color = ($date->startOfDay() < now(\Auth::user()->timezone)->startOfDay())?'text-danger':'';

        if($date->isYesterday()){

            $diff = __('yesterday');

        }else if($date->isToday()){

            $diff = __('today');

        }else if($date->isTomorrow()){

            $diff = __('tomorrow');

        }else{

            $diff = $date->diffForHumans();
        }

        $text = $caption.' '.$diff;

        return '<span class="text-small '.$color.'">'.$text.'</span>';
    }

    public static function showTimeForHumans($time)
    {
        if($time == null){
            return '---';
        }

        $time = \Helpers::utcToLocal($time);

        $color = ($time < now(\Auth::user()->timezone))?'text-danger':'';
        $text = $time->format('M d, H:m');

        return '<span class="text-small '.$color.'">'.$text.'</span>';
    }

    public static function showTimespan($start, $end)
    {
        return '<span class="text-small font-italic">('.Carbon::parse($start)->timespan(Carbon::parse($end)).')</span>';
    }

    static function fragment($route, $fragment)
    {
        return route($route) . "/#{$fragment}";
    }
}
