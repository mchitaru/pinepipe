<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'title', 
        'text', 
        'user_id',
        'created_by',
    ];

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($note) {
            if ($user = \Auth::user()) {
                $note->user_id = $user->id;
                $note->created_by = $user->created_by;
            }
        });

        static::deleting(function ($note) {

        });
    }
    
    public function notable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public static function createNote($post)
    {
        $post['user_id']    = \Auth::user()->id;
        $post['created_by'] = \Auth::user()->created_by;

        if(isset($post['lead_id'])){

            $lead = Lead::find($post['lead_id']);
            $note = $lead->notes()->create($post);
            
            Activity::createLeadNote($lead, $note);

        }else {
            $note = Note::create($post);
        }

        return $note;
    }

    public function updateNote($post)
    {
        $this->update($post);
    }

}
