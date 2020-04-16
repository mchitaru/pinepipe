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
        $post['created_by'] = \Auth::user()->creatorId();

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

    public function detachNote()
    {
    }

}
