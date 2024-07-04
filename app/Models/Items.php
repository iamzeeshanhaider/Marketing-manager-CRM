<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class Items extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price', 'description', 'company_id'];

    public function scopeSearch(Builder $builder, Collection $data)
    {
        if ($data->has('search') && $data->get('search')) {
            $builder->where('name', 'LIKE', '%' . $data->get('search') . '%');
        }
    }

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


    public function leadinvoices()
    {
        return $this->belongsToMany(LeadConversation::class, 'invoice_items', 'item_id', 'invoice_id')->withTimestamps();
    }

    public function setCOmpanyIdAttribute($value)
    {
        $this->attributes['company_id'] = \App\Models\Company::first()->id;
    }
}
