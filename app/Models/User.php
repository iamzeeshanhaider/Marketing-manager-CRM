<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Role;
use App\Enums\GeneralStatus;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'city',
        'country',
        'gender',
        'ethnicity',
        'designation',
        'dob',
        'employment_status',
        'avatar',
        'last_login',
        'last_login_ip_address',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login' => 'datetime',
        'status' => GeneralStatus::class
    ];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_employees', 'user_id', 'company_id');
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'company_employees', 'user_id', 'department_id');
    }

    public function setAvatarAttribute($value)
    {
        $this->attributes['avatar'] = uploadOrUpdateFile($this->avatar, $value, \constPaths::UserAvatar);
    }

    public function getAvatar()
    {
        return asset(!empty($this->image) ? \constPaths::UserAvatar . $this->image : \constPaths::DefaultAvatar);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'agent_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(LeadConversation::class);
    }

    public function scopeHasStatus(Builder $builder, $status)
    {
        if (in_array($status, GeneralStatus::getKeys())) {
            $status = GeneralStatus::fromKey($status);
        }
        $builder->where('status', $status);
    }

    public function scopeSearch(Builder $builder, Collection $data): void
    {
        if ($data->has('search') && $data->get('search')) {
            $search = $data->get('search');
            $builder->where('full_name', 'LIKE', '%' . $search . '%')
                ->orWhere('email', 'LIKE', '%' . $search . '%');
        }

        $builder->when($data->get('company'), function ($query) use ($data) {
            $query->belongsToCompany($data->get('company'));
        });

        $builder->when($data->get('department'), function ($query) use ($data) {
            $query->belongsToDepartment($data->get('department'));
        });
    }

    public function scopeBelongsToCompany(Builder $builder, $companyId)
    {
        $builder->whereHas('companies', function ($query) use ($companyId) {
            $query->where('companies.id', $companyId);
        });
    }

    public function scopeBelongsToDepartment(Builder $builder, $departmentId)
    {
        $builder->whereHas('departments', function ($query) use ($departmentId) {
            $query->where('departments.id', $departmentId);
        });
    }

    public function isAgent()
    {
        return in_array('Agent', $this->getRoleNames()->toArray());
    }

    public function isAdmin()
    {
        return in_array('Admin', $this->getRoleNames()->toArray());
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'model_has_permissions', 'model_id', 'permission_id');
    }
    public function accessToken()
    {
        return $this->hasOne(AccessToken::class);
    }
}
