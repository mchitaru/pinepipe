<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Eventable;
use App\Traits\Taggable;

class Contact extends Model
{
    use NullableFields, Eventable, Taggable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'company',
        'job',
        'website',
        'birthday',
        'notes',
        'client_id',
        'user_id',
        'created_by'
    ];

    protected $nullable = [
        'email',
        'phone',
        'address',
        'company',
        'job',
        'website',
        'birthday',
        'notes',
        'client_id',
	];

    public static $SEED = 10;

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function client()
    {
        return $this->hasOne('App\Client', 'id', 'client_id');
    }

    public function leads()
    {
        return $this->hasMany('App\Lead', 'contact_id', 'id');
    }

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($contact) {
            if ($user = \Auth::user()) {
                $contact->user_id = $user->id;
                $contact->created_by = $user->creatorId();
            }
        });
    }

    public static function createContact($post)
    {
        $contact                = Contact::make($post);
        $contact->save();

        $contact->syncTags(isset($post['tags'])?$post['tags']:[]);

        return $contact;
    }

    public function updateContact($post)
    {
        $this->update($post);
        $this->syncTags(isset($post['tags'])?$post['tags']:[]);
    }

    public function detachContact()
    {
    }

    public static function contactsByUserType()
    {
        if(\Auth::user()->type == 'company')
        {
            return Contact::with('client')
                   ->where('created_by','=',\Auth::user()->creatorId());
        }else
        {
            return Contact::with('client')
                    ->where(function ($query)  {
                        $query->where('user_id', \Auth::user()->id);
                    })
                   ->where('created_by','=',\Auth::user()->creatorId());
        }
    }

}
