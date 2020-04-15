<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $fillable = [
        'title', 
        'status',
        'created_by', 
    ];

    public function checklistable()
    {
        return $this->morphTo();
    }
}
