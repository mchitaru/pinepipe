<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Eventable;
use App\Traits\Taggable;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media as BaseMedia;
use Spatie\Image\Manipulations;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Client extends Model implements HasMedia
{
    use NullableFields, Eventable, Taggable, HasMediaTrait;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'website',
        'tax',
        'user_id',
        'created_by'
    ];

    protected $nullable = [
        'email',
        'phone',
        'address',
        'website',
        'tax',
    ];

    public static $SEED = 2;


    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            if ($user = \Auth::user()) {
                $client->user_id = $user->id;
                $client->created_by = $user->creatorId();
            }
        });

        static::deleting(function ($client) {

            $client->projects()->each(function($project) {
                $project->delete();
             });

            $client->leads()->each(function($lead) {
                $lead->delete();
            });

            $client->users()->each(function($user) {
                $user->forceDelete();
            });

            $client->tags()->detach();
            $client->events()->detach();

            Contact::where('client_id', '=', $client->id)->update(array('client_id' => null));
        });
    }

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

    public function registerMediaConversions(BaseMedia $media = null)
    {
        $this->addMediaConversion('thumb')
              ->fit(Manipulations::FIT_FILL, 60, 60);
    }

    public static function createClient($post)
    {
        $client = Client::make($post);
        $client->save();

        // $user = User::create($request->all());
        // $role_r = Role::findByName('client');
        // $user->assignRole($role_r);

        return $client;
    }

    public function updateClient($post)
    {
        $this->update($post);
        $this->save();
    }

}
