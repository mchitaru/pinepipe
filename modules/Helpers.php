<?php

use App\User;
use App\Client;
use App\Project;
use App\Media;
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

    public static function ceil($value, $precision = 2)
    {
        $mult = pow(10, abs($precision));
         return $precision < 0 ? ceil($value / $mult) * $mult : ceil($value * $mult) / $mult;
    }

    public static function buildAvatar($name, $avatar, $size = 32, $class = 'avatar')
    {
        return "<img data-filter-by='alt' width=".$size." height=".$size.
                    (empty($avatar) ? (" class='".$class."' avatar='".$name."'") :
                                            (" class='".$class."' src='".$avatar."'"))."/>";
    }

    public static function buildUserAvatar(User $user, $size = 36, $class = 'avatar')
    {
        $name = null;
        $avatar = null;

        if($user){
            $name = $user->name;
            $avatar = $user->hasMedia('logos') ? $user->media('logos')->first()->getFullUrl('thumb') : null;
        }

        return Helpers::buildAvatar($name, $avatar, $size, $class);
    }

    public static function buildClientAvatar(Client $client, $size = 36, $class = 'avatar')
    {
        $name = null;
        $avatar = null;

        if($client){
            $name = $client->name;
            $avatar = $client->hasMedia('logos') ? $client->media('logos')->first()->getFullUrl('thumb') : null;
        }

        return Helpers::buildAvatar($name, $avatar, $size, $class);
    }

    public static function getProgressColor($progress)
    {
        $color = '';

        if($progress<=15){
            $color='danger';
        }else if ($progress > 15 && $progress <= 33) {
            $color='warning';
        } else if ($progress > 33 && $progress <= 70) {
            $color='primary';
        } else {
            $color='success';
        }

        return $color;
    }

    public static function getPriorityBadge($priority)
    {
        switch($priority) {
            case 2:
                return '<span class="badge badge-success"  title="'.__('Priority').'">'.Project::translatePriority($priority).'</span>';
            case 1:
                return '<span class="badge badge-warning"  title="'.__('Priority').'">'.Project::translatePriority($priority).'</span>';
            default:
                return '<span class="badge badge-danger"  title="'.__('Priority').'">' .Project::translatePriority($priority).'</span>';
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

        if($time->isYesterday()){

            $text = __('yesterday') . $time->format(', H:i');

        }else if($time->isToday()){

            $text = __('today') . $time->format(', H:i');

        }else if($time->isTomorrow()){

            $text = __('tomorrow') . $time->format(', H:i');

        }else{

            $text = $time->format('M d, H:i');
        }

        return '<span class="text-small '.$color.'">'.$text.'</span>';
    }

    public static function showTimespan($start, $end)
    {
        if($start < $end)
            return '<span class="text-small font-italic">('.Carbon::parse($start)->timespan(Carbon::parse($end)).')</span>';

        return '';
    }

    static function fragment($route, $fragment)
    {
        return route($route) . "/#{$fragment}";
    }

    static function countryToLocale($country)
    {
        switch($country)
        {
            case 'RO':  return 'ro';
            default:    return 'en';
        }
    }

    static function getCurrencySymbol($currency)
    {
        $formatter = new \NumberFormatter('en-US' . '@currency=' . $currency, \NumberFormatter::CURRENCY);
        return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }

    static function priceFormat($price, $currency, $precision = 2)
    {        
        $numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $numberFormatter->setAttribute(\NumberFormatter::FRACTION_DIGITS , $precision);
        
        return $numberFormatter->formatCurrency($price, $currency);
    }

    static function priceConvert($price, $rate, $precision = 2)
    {
        if(empty($rate))
            $rate = 1.0;

        return \Helpers::ceil($price / $rate, $precision);
    }
}
