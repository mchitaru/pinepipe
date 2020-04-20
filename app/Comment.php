<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Taggable;

class Comment extends Model
{
    use Taggable;
    
    protected $fillable = [
        'comment', 
        'user_id',
        'created_by',
    ];

    
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

}
