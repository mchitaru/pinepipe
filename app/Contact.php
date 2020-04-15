<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Eventable;

class Contact extends Model
{
    use NullableFields, Eventable;

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
        'created_by',
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

    public static function createContact($post)
    {
        if(\Auth::user()->type != 'company')
        {
            $post['user_id'] = \Auth::user()->id;
        }

        $contact                = Contact::make($post);
        $contact->created_by    = \Auth::user()->creatorId();
        $contact->save();

        return $contact;
    }

    public function updateContact($post)
    {
        $this->update($post);
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
