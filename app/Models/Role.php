<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'roles';

    public static function defaultRoles()
    {
        return [
            'admin',
            'manager',
            'data_collector', // leads
            'agent', // leads
            'marketer', // leads, campaign
        ];
    }

    public function scopeSearch(Builder $builder, Collection $data): void
    {
        if ($data->has('search') && $data->get('search')) {
            $builder->where('name', 'LIKE', '%' . $data->get('search') . '%');
        }
    }

}
