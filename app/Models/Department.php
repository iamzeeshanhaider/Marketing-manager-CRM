<?php

namespace App\Models;

use App\Enums\GeneralStatus;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Department extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_employees', 'department_id', 'user_id');
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
            $builder->where('name', 'LIKE', '%' . $data->get('search') . '%');
        }

        if ($data->has('status') && $data->get('status')) {
            $builder->hasStatus($data->get('status'));
        }
    }
}
