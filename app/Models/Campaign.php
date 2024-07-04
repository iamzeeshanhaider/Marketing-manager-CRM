<?php

namespace App\Models;

use App\Enums\EmailStatus;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Campaign extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'company_id', 'lead_status_id', 'email_status', 'type', 'email_content', 'lead_type', 'country'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'lead_status_id' => 'integer',
    ];

    /**
     * Get the company that owns the Campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the leadStatus that owns the Campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class);
    }

    function campaignConversations()
    {
        return $this->hasMany(LeadConversation::class, 'campaign_id');
    }

    /**
     * Get all of the leads for the Campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function leads(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class, 'lead_conversations', 'campaign_id', 'lead_id');
    }

    public function emailCampaignLeads()
    {
        return $this->belongsToMany(Lead::class, 'campaign_leads', 'campaign_id', 'lead_id')
            ->withPivot('is_sent')
            ->withTimestamps();
    }

    public function scopeHasStatus(Builder $builder, $status)
    {
        if (in_array($status, EmailStatus::getKeys())) {
            $status = EmailStatus::fromKey($status);
        }
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
