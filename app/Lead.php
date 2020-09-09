<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;
use App\Traits\Eventable;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Traits\Taggable;
use App\Traits\Categorizable;

use App\Traits\Actionable;
use App\Traits\Notable;
use App\Traits\Stageable;

use App\Scopes\TenantScope;

class Lead extends Model implements HasMedia
{
    use NullableFields, Eventable, HasMediaTrait, Actionable, Notable, Taggable, Categorizable, Stageable;

    protected $fillable = [
        'name',
        'price',
        'stage_id',
        'order',
        'client_id',
        'contact_id',
        'source_id',
        'category_id',
        'user_id',
        'created_by',
        'archived',
    ];

    protected $nullable = [
        'price',
        'client_id',
        'category_id',
        'contact_id',
	];


    public static $SEED = 10;

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new TenantScope);

        static::creating(function ($lead) {
            if ($user = \Auth::user()) {
                $lead->user_id = $user->id;
                $lead->created_by = $user->created_by;
            }
        });

        static::deleting(function ($lead) {

            $lead->removeProjectLead();

            $lead->tags()->detach();
            $lead->events()->detach();

            $lead->notes()->each(function($note) {
                $note->delete();
            });

            $lead->activities()->delete();
        });
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }

    public function projects()
    {
        return $this->hasMany('App\Project', 'lead_id', 'id');
    }
    
    public function removeProjectLead()
    {
        return Project::where('lead_id','=', $this->id)->update(array('lead_id' => 0));
    }

    public static function createLead($post)
    {
        if(isset($post['client_id']) && !is_numeric($post['client_id'])) {

            if(!\Auth::user()->checkClientLimit()) return null;

            //new client
            $client = Client::create(['name' => $post['client_id']]);
            $post['client_id'] = $client->id;
        }

        if(isset($post['contact_id']) && !is_numeric($post['contact_id'])) {

            //new contact
            $contact = Contact::create(['name' => $post['contact_id'],
                                        'client_id' => $post['client_id']]);

            $post['contact_id'] = $contact->id;
        }

        if(isset($post['category_id']) && !is_numeric($post['category_id'])) {

            //new category
            $category = Category::create(['name' => $post['category_id'],
                                            'class' => Lead::class]);
            $post['category_id'] = $category->id;
        }

        $stage = Stage::find($post['stage_id']);

        $post['order']   = $stage->leads->count();

        if(\Auth::user()->type != 'company')
        {
            $post['user_id'] = \Auth::user()->id;
        }

        $lead = Lead::create($post);

        Activity::createLead($lead);

        return $lead;
    }

    public function updateLead($post)
    {
        if(isset($post['client_id']) && !is_numeric($post['client_id'])) {

            //new client
            $client = Client::create(['name' => $post['client_id']]);
            $post['client_id'] = $client->id;
        }

        if(isset($post['contact_id']) && !is_numeric($post['contact_id'])) {

            //new contact
            $contact = Contact::create(['name' => $post['contact_id'],
                                        'client_id' => $post['client_id']]);

            $post['contact_id'] = $contact->id;
        }

        if(isset($post['category_id']) && !is_numeric($post['category_id'])) {

            //new category
            $category = Category::create(['name' => $post['category_id'],
                                            'class' => Lead::class]);
            $post['category_id'] = $category->id;
        }

        $this->update($post);

        Activity::updateLead($this);
    }

    public function updateOrder($stage, $order)
    {
        $updated = ($this->stage_id != $stage);

        $this->order = $order;
        $this->stage_id = $stage;
        $this->save();

        if($updated){

            Activity::updateLead($this);
        }

        return $updated;
    }

    static function translateStatus($status)
    {
        switch($status)
        {
            case 1: return __('archived');
            default: return __('active');
        }
    }

    public static $status = [
        'active',
        'archived'
    ];
}
