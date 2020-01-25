<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $fillable = [
        'name', 
        'created_by'
    ];


    protected $hidden = [

    ];

    public function products()
    {
        return $this->belongsToMany('App\Product', 'unit_id', 'id')->first();
    }
}
