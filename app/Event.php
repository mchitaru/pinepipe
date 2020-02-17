<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class Event extends Model
{
    use NullableFields;

    protected $fillable = [
        'active',
        'name',
        'category_id',
        'start',
        'end',
        'busy',
        'notes',
        'user_id',
        'created_by',
    ];

    protected $nullable = [
        'notes'
	];

    public static $SEED = 100;

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo('App\EventCategory');
    }

    public static function createEvent($post)
    {
        $post['user_id']   = \Auth::user()->id;
        $post['active']    = true;
        $post['busy']      = true;

        $event                = Event::make($post);
        $event->created_by    = \Auth::user()->creatorId();
        $event->save();

        // Activity::createContact($contact);

        return $event;
    }

    public function updateEvent($post)
    {
        $this->update($post);
    }

    public function detachEvent()
    {
    }
}
