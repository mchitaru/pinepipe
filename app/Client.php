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

use App\Traits\Actionable;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

use App\Scopes\CollaboratorTenantScope;

class Client extends Model implements HasMedia
{
    use NullableFields, Eventable, Taggable, HasMediaTrait, Actionable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'website',
        'tax',
        'registration',
        'user_id',
        'created_by',
        'archived'
    ];

    protected $nullable = [
        'email',
        'phone',
        'address',
        'website',
        'tax',
        'registration'
    ];

    public static $SEED = 2;


    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CollaboratorTenantScope);

        static::creating(function ($client) {
            if ($user = \Auth::user()) {
                $client->user_id = $user->id;
                $client->created_by = $user->created_by;
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

            $client->activities()->delete();
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

    public function invoices()
    {
        return $this->hasMany('App\Invoice', 'client_id', 'id');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact', 'client_id', 'id');
    }

    public function tasks()
    {
        return Task::whereHas('project', function ($query) {

                    $query->where('client_id', $this->id);
                });
    }

    public function registerMediaConversions(BaseMedia $media = null)
    {
        $this->addMediaConversion('thumb')
              ->fit(Manipulations::FIT_FILL, 60, 60)
              ->nonQueued();
    }

    public static function createClient($post)
    {
        $client = Client::make($post);
        $client->save();

        Activity::createClient($client);

        return $client;
    }

    public function updateClient($post)
    {        
        if(isset($post['archived'])){

            foreach($this->projects as $project){
                
                $project->archived = 1;
                $project->save();
            }

            foreach($this->leads as $lead){
                
                $lead->archived = 1;
                $lead->save();
            }

        }

        $this->update($post);
        $this->save();

        Activity::updateClient($this);
    }

    //used for filters
    public static $status = [
        'active',
        'archived'
    ];
}
