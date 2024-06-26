<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media as BaseMedia;
use Spatie\Image\Manipulations;

use App\Scopes\CompanyTenantScope;

class CompanySettings extends Model implements HasMedia
{
    use NullableFields, HasMediaTrait;

    protected $fillable = [
        'name',
        'email',
        'address',
        'city',
        'state',
        'zipcode',
        'country',
        'phone',
        'website',
        'tax',
        'tax_payer',
        'registration',
        'bank',
        'iban',
        'invoice',
        'receipt',
        'currency',
        'created_by',
    ];

    protected $nullable = [
        'email',
        'address',
        'city',
        'state',
        'zipcode',
        'country',
        'phone',
        'website',
        'invoice',
        'receipt',
        'iban',
        'registration',
        'bank',
    ];

    /**
     * Boot events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyTenantScope);

        static::creating(function ($settings) {
            if ($user = \Auth::user()) {
                $settings->created_by = $user->created_by;
            }
        });

        static::deleting(function ($settings) {

        });
    }

    public function company()
    {
        return $this->belongsTo('App\User', 'id', 'created_by');
    }

    public function getFullAddress()
    {
        return $this->address.', '.$this->city.', '.$this->state.' - '.$this->zipcode.', '.$this->country;
    }

    public function registerMediaConversions(BaseMedia $media = null)
    {
        $this->addMediaConversion('thumb')
                ->fit(Manipulations::FIT_FILL, 60, 60)
                ->nonQueued();
    }

    public static function updateSettings($settings, $post)
    {
        if($settings && $settings->currency != $post['currency'] ||
            $settings == null && \Auth::user()->getDefaultCurrency() != $post['currency']){

            //the user changed the main currency -> update currency rate of invoices
            $invoices = \Auth::user()->companyInvoices()
                                        ->get();

            foreach($invoices as $invoice){

                if($invoice->rate){

                    $userCurrency = $post['currency'];
                    $invoiceCurrency = $invoice->currency;
            
                    $userRate = Currency::where('code', $userCurrency)->first()->rate;            
                    $invoiceRate = Currency::where('code', $invoiceCurrency)->first()->rate;
            
                    $invoice->rate = \Helpers::ceil((float)$userRate/(float)$invoiceRate, 4);
                    $invoice->save();
                }
            }
        }

        $settings = CompanySettings::updateOrCreate(['created_by' => \Auth::user()->created_by], $post);

        return $settings;
    }

}
