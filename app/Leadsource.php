<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{
    protected $fillable = [
        'name', 
        'created_by',
    ];


    protected $hidden = [

    ];
}
