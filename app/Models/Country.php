<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class Country extends Model
{
    protected $table = 'countries';
    protected $fillable = ['name', 'phonecode', 'capital', 'currency', 'region', 'subregion', 'flag', 'iso2', 'iso3'];

    public static function asSelectArray()
    {
        return self::pluck('name', 'id')->toArray();
    }

    public function scopeSearch(Builder $builder, Collection $data): void
    {
        if ($data->has('search') && $data->get('search')) {
            $builder->where('name', 'LIKE', '%' . $data->get('search') . '%');
        }
    }
}
