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
    ];

    protected $nullable = [
        'price',
        'client_id',
        'category_id',
        'contact_id',
	];


    public static $SEED = 10;

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

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($lead) {
            if ($user = \Auth::user()) {
                $lead->user_id = $user->id;
                $lead->created_by = $user->creatorId();
            }
        });
    }

    public function removeProjectLead()
    {
        return Project::where('lead_id','=',$this->id)->update(array('lead_id' => 0));
    }

    public static function createLead($post)
    {
        $stage = Stage::find($post['stage_id']);

        $post['order']   = $stage->leads->count();

        if(\Auth::user()->type != 'company')
        {
            $post['user_id'] = \Auth::user()->id;
        }

        $lead                = Lead::make($post);
        $lead->created_by    = \Auth::user()->creatorId();
        $lead->save();

        Activity::createLead($lead);

        return $lead;
    }

    public function updateLead($post)
    {
        $this->update($post);

        Activity::updateLead($this);
    }

    public function updateOrder($stage, $order)
    {
        $updated = ($this->order != $order || $this->stage_id != $stage);

        if($updated){

            $this->order = $order;
            $this->stage_id = $stage;
            $this->save();

            Activity::updateLead($this);
        }

        return $updated;
    }

    public function detachLead()
    {
        $this->removeProjectLead();

        $this->activities()->delete();
    }

}
