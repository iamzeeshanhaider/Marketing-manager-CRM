<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    protected $fillable = ['name', 'guard_name', 'model_type'];

    public static function defaultAdminPermissions()
    {
        return ['manage_leads', 'manage_companies', 'manage_departments', 'manage_agents', 'manage_marketers', 'manage_managers'];
    }

    public static function defaultManagerPermissions()
    {
        return ['manage_companies', 'manage_departments', 'manage_agents', 'manage_marketers', 'manage_employees', 'manage_leads'];
    }

    public static function defaultAgentPermissions()
    {
        return ['view_leads', 'update_leads', 'delete_leads'];
    }

    public static function defaultMarketerPermissions()
    {
        return ['marketing_campaign', 'view_leads'];
    }

    public static function defaultDataCollectorPermissions()
    {
        return ['manage_leads', 'view_leads', 'update_leads'];
    }

    public function scopeSearch(Builder $builder, Collection $data)
    {
        if ($data->has('search') && $data->get('search')) {
            $builder->where('name', 'LIKE', '%' . $data->get('search') . '%');
        }
    }
}
