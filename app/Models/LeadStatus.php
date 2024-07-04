<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class LeadStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function defaultColorCodes()
    {
        return [
            '#7D84EB', '#AB47BC', '#40ADF4', '#FFA264',
            '#FF6479', '#48D7A4', '#EF5350', '#EC407A',
            '#7E57C2', '#5C6BC0', '#42A5F5', '#26C6DA',
            '#26A69A', '#FFA726', '#8D6E63', '#BDBDBD'
        ];
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'status');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeHasStatus(Builder $builder, $status)
    {
        $builder->where('status', $status);
    }

    /**
     * Apply search criteria to the query.
     *
     * @param array $data
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $builder, Collection $data)
    {
        $search = $data->get('search');

        $builder->when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        });

        $builder->when($data->get('company'), function ($query) use ($data) {
            $query->belongsToCompany($data->get('company'));
        });
    }

    /**
     * Scope a query to only include lead statuses associated with a specific company.
     *
     * @param Illuminate\Database\Eloquent\Builder $query
     * @param Company|null $company
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeBelongsToCompany(Builder $builder, $companyId)
    {
        $builder->where('company_id', $companyId);
    }
}
