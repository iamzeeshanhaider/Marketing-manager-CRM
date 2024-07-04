<?php

namespace App\Models;

use App\Enums\GeneralStatus;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class Company extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'logo', 'status', 'email', 'allowed_users'];

    protected $cast = [
        'status' => GeneralStatus::class
    ];

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_employees', 'company_id', 'user_id');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function leadStatus(): HasMany
    {
        return $this->hasMany(LeadStatus::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function getLogo()
    {
        return asset(!empty($this->logo) ? \constPaths::CompanyLogo . $this->logo : \constPaths::Default);
    }

    public function setLogoAttribute($value)
    {
        $this->attributes['logo'] = uploadOrUpdateFile($this->logo, $value, \constPaths::CompanyLogo);
    }

    public function scopeHasStatus(Builder $builder, $status)
    {
        if (in_array($status, GeneralStatus::getKeys())) {
            $status = GeneralStatus::fromKey($status);
        }
        $builder->where('status', $status);
    }

    public function setting(): HasOne
    {
        return $this->hasOne(CompanySettings::class);
    }

    public function scopeSearch(Builder $builder, Collection $data): void
    {
        if ($data->has('search') && $data->get('search')) {
            $search = $data->get('search');
            $builder->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%');
        }

        if ($data->has('status') && $data->get('status')) {
            $builder->hasStatus($data->get('status'));
        }
    }
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function calendar()
    {
        return $this->hasOne(CompanyCalender::class, 'company_id');
    }
}
