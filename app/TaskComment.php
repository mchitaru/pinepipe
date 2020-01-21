<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $fillable = [
        'comment', 'task_id','created_by', 'user_type',
    ];


    protected $hidden = [

    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function comment_user(){
       return User::where('id','=',$this->created_by)->first();
    }
    public function user(){
        return $this->hasOne('App\User','id','created_by');
    }

}
