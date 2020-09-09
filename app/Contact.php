<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Eventable;
use App\Traits\Taggable;

use App\Traits\Actionable;

class Contact extends Model
{
    use NullableFields, Eventable, Taggable, Actionable;

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
                $contact->created_by = $user->created_by;
            }
        });

        static::deleting(function ($contact) {

            $contact->tags()->detach();
            $contact->events()->detach();

            $contact->activities()->delete();
        });
    }

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

    public static function createContact($post)
    {
        if(isset($post['client_id']) && !is_numeric($post['client_id'])) {

            if(!\Auth::user()->checkClientLimit()) return null;

            //new client
            $client = Client::create(['name' => $post['client_id']]);
            $post['client_id'] = $client->id;
        }

        $contact                = Contact::make($post);
        $contact->save();

        $contact->syncTags(isset($post['tags'])?$post['tags']:[]);

        Activity::createContact($contact);

        return $contact;
    }

    public function updateContact($post)
    {
        $this->update($post);
        $this->syncTags(isset($post['tags'])?$post['tags']:[]);

        Activity::updateContact($this);
    }

    public static function contactsByUserType()
    {
        if(\Auth::user()->type == 'company')
        {
            return Contact::with(['client', 'tags'])
                   ->where('created_by','=',\Auth::user()->created_by)
                   ->orderBy('name', 'asc');
        }else
        {
            return Contact::with(['client', 'tags'])
                    ->where(function ($query)  {
                        $query->where('user_id', \Auth::user()->id);
                    })
                   ->where('created_by','=',\Auth::user()->created_by)
                   ->orderBy('name', 'asc');
        }
    }

}
