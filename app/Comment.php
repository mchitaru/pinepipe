<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'comment', 
        'task_id', 
        'created_by',
    ];

    
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

}
