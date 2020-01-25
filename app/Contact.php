<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'company',
        'job',
        'website',
        'birthday',
        'notes',
        'user_id',
        'created_by',
    ];

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function owner()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }
}
