<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Labels extends Model
{
    protected $fillable = [
        'name','color','pipeline_id','created_by'
    ];

    public static $colors = [
        'bg-white bg-font-white',
        'bg-default bg-font-default',
        'bg-dark bg-font-dark',
        'bg-blue bg-font-blue',
        'bg-blue-madison bg-font-blue-madison',
        'bg-blue-chambray bg-font-blue-chambray',
        'bg-blue-ebonyclay bg-font-blue-ebonyclay',
        'bg-blue-hoki bg-font-blue-hoki',
        'bg-blue-steel bg-font-blue-steel',
        'bg-blue-soft bg-font-blue-soft',
        'bg-blue-dark bg-font-blue-dar',
        'bg-blue-sharp bg-font-blue-sharp',
        'bg-blue-oleo bg-font-blue-oleo',
        'bg-green bg-font-green',
        'bg-green-meadow bg-font-green-meadow',
        'bg-green-seagreen bg-font-green-seagreen',
        'bg-green-turquoise bg-font-green-turquoise',
        'bg-green-haze bg-font-green-haze',
        'bg-green-jungle bg-font-green-jungle',
        'bg-green-soft bg-font-green-soft',
        'bg-green-dark bg-font-green-dark',
        'bg-green-sharp bg-font-green-sharp',
        'bg-green-steel bg-font-green-steel',
        'bg-grey bg-font-grey',
        'bg-grey-steel bg-font-grey-steel',
        'bg-grey-cararra bg-font-grey-cararra',
        'bg-grey-gallery bg-font-grey-gallery',
        'bg-grey-cascade bg-font-grey-cascade',
        'bg-grey-silver bg-font-grey-silver',
        'bg-grey-salsa bg-font-grey-salsa',
        'bg-grey-salt bg-font-grey-salt',
        'bg-grey-mint bg-font-grey-mint',
        'bg-red bg-font-red',
        'bg-red-pink bg-font-red-pink',
        'bg-red-sunglo bg-font-red-sunglo',
        'bg-red-intense bg-font-red-intense',
        'bg-red-thunderbird bg-font-red-thunderbird',
        'bg-red-flamingo bg-font-red-flamingo',
        'bg-red-soft bg-font-red-soft',
        'bg-red-haze bg-font-red-haze',
        'bg-red-mint bg-font-red-mint',
        'bg-yellow bg-font-yellow',
        'bg-yellow-gold bg-font-yellow-gold',
        'bg-yellow-casablanca bg-font-yellow-casablanca',
        'bg-yellow-crusta bg-font-yellow-crusta',
        'bg-yellow-lemon bg-font-yellow-lemon',
        'bg-yellow-saffron bg-font-yellow-saffron',
        'bg-yellow-soft bg-font-yellow-soft',
        'bg-yellow-haze bg-font-yellow-haze',
        'bg-yellow-mint bg-font-yellow-mint',
        'bg-purple bg-font-purple',
        'bg-purple-plum bg-font-purple-plum',
        'bg-purple-medium bg-font-purple-medium',
        'bg-purple-studio bg-font-purple-studio',
        'bg-purple-wisteria bg-font-purple-wisteria',
        'bg-purple-seance bg-font-purple-seance',
        'bg-purple-intense bg-font-purple-intense',
        'bg-purple-sharp bg-font-purple-sharp',
        'bg-purple-soft bg-font-purple-soft',
    ];


}
