<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class Contact extends Model
{
    use NullableFields;

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

    public static $SEED = 1000;

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
        $post['user_id']   = \Auth::user()->id;

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

}
