<?php

namespace App\Models;

use App\Enums\CampaignTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Lead extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $cast = [
        'source' => LeadSource::class,
        'data_array' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function leadStatus(): BelongsTo
    {
        return $this->belongsTo(LeadStatus::class, 'status');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(LeadConversation::class);
    }

    public function scopeConversationType($query, $type)
    {
        return $query->whereHas('conversations', function ($query) use ($type) {
            $query->where('type', CampaignTypes::getKey($type));
        });
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'lead_conversations', 'lead_id', 'campaign_id');
    }

    public function isEmailSent()
    {
        foreach ($this->campaigns as $campaign) {
            if ($campaign->pivot->is_sent == 1) {
                return true;
            }
        }
        return false;
    }

    public function assignAgent(User $user)
    {
        $this->agent_id = $user->id;
        $this->save();
    }

    public function scopeHasStatus(Builder $builder, $status)
    {
        $builder->where('status', $status);
    }


    public function scopeFilter($query, $filters, $company)
    {

        return $query->when(isset($filters['status']), function ($query) use ($filters) {
            return $query->where('status', $filters['status']);
        }, function ($query) {
            return $query->whereNotIn('status', [9, 16, 6, 7]);
        })
            ->when($company, function ($query, $company) {
                return $query->belongsToCompany($company);
            });
    }

    public function scopeBelongsToCompany($query, $company)
    {

        // Assuming 'company_id' is the foreign key in the leads table
        return $query->where('company_id', $company->id);
    }


    // For sending sms
    public function routeNotificationForNexmo($notification)
    {
        return $this->tel;
    }
}
