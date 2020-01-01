<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'address',
        'company',
        'job',
        'website',
        'birthday',
        'notes',
        'created_by',
    ];
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'owner')->first();
    }
}
