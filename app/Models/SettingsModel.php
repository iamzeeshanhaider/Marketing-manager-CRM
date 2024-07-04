<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SettingsModel extends Model
{
    use HasFactory;
    protected $fillable = ['company_id', 'MAILGUN_DOMAIN', 'MAILGUN_SECRET', 'MAIL_MAILER', 'MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'TWILIO_ACCOUNT_SID', 'TWILIO_AUTH_TOKEN', 'TWILIO_SMS_FROM', 'VONAGE_KEY', 'VONAGE_SECRET', 'VONAGE_NUMBER', 'MAIL_FROM_ADDRESS', 'LEADS_SAMPLE'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->company_id = \App\Models\Company::first()->id;
        });
        static::updating(function ($model) {
            $model->company_id = \App\Models\Company::first()->id;
        });
        static::addGlobalScope('company', function (Builder $builder) {
            $builder->where('company_id', '=', \App\Models\Company::first()->id);
        });
    }
}
