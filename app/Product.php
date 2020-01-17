<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'unit',
        'description',
    ];


    protected $hidden = [

    ];

    public function unit()
    {
        return $this->hasOne('App\ProductUnit', 'id', 'unit')->first();
    }

}
