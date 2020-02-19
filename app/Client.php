<?php

namespace App;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

class Client extends Model
{
    use NullableFields;

    protected $fillable = [
        'name',
        'avatar',
        'email',
        'phone',
        'address',
        'website',
        'created_by',
    ];

    protected $nullable = [
        'email',
        'avatar',
        'phone',
        'address',
        'website',
    ];

    public static $SEED = 5;
    
    public function users()
    {
        return $this->hasMany('App\User', 'client_id', 'id');
    }

    public function leads()
    {
        return $this->hasMany('App\Lead', 'client_id', 'id');
    }

    public function projects()
    {
        return $this->hasMany('App\Project', 'client_id', 'id');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact', 'client_id', 'id');
    }

    public function tasks()
    {
        return $this->hasManyThrough('App\Task', 'App\Project', 'client_id', 'project_id', 'id');
    }

    public function invoices()
    {
        return $this->hasManyThrough('App\Invoice', 'App\Project', 'client_id', 'project_id', 'id');
    }

    public function expenses()
    {
        return $this->hasManyThrough('App\Expense', 'App\Project', 'client_id', 'project_id', 'id');
    }

    public static function createClient($post)
    {
        $client = Client::make($post);
        $client->created_by = \Auth::user()->creatorId();
        $client->save();

        // $user = User::create($request->all());
        // $role_r = Role::findByName('client');
        // $user->assignRole($role_r);

        return $client;
    }

    public function updateClient($post)
    {
        $this->update($post);

        if(isset($post['avatar']))
        {
            $path = Helpers::storePublicFile($post['avatar']);
            $this->avatar = $path;
        }

        $this->save();
    }

    public function detachClient()
    {
        Project::where('client_id', '=', $this->id)->update(array('client_id' => null));
        Lead::where('client_id', '=', $this->id)->update(array('client_id' => null));
        Contact::where('client_id', '=', $this->id)->update(array('client_id' => null));
        User::where('client_id', '=', $this->id)->update(array('client_id' => null));
    }
}
