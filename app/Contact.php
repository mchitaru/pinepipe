<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Eventable;
use App\Traits\Taggable;

use App\Traits\Actionable;

use App\Scopes\CompanyTenantScope;

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

        static::addGlobalScope(new CompanyTenantScope);

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

            if(\Auth::user()->hasMaxClients()) return null;

            //new client
            $client = Client::createClient(['name' => $post['client_id']]);
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

    public static function filterContacts($order = 'name', $dir = 'asc', $filter = '')
    {
        return Contact::with(['client', 'tags'])
                        ->where(function ($query) use ($filter) {
                            $query->where('name','like','%'.$filter.'%')
                                    ->orWhere('email','like','%'.$filter.'%')
                                    ->orWhere('phone','like','%'.$filter.'%')
                                    ->orWhereHas('client', function ($query) use($filter) {
        
                                        $query->where('name','like','%'.$filter.'%');
                                    })    
                                    ->orWhereHas('tags', function ($query) use($filter)
                                    {
                                        $query->where('tags.name','like','%'.$filter.'%');

                                    });
                        })
                        ->orderBy($order?$order:'name', $dir?$dir:'asc');
    }

}
