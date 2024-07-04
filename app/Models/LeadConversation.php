<?php

namespace App\Models;

use App\Enums\CampaignTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadConversation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $cast = [
        'type' => CampaignTypes::class,
        'is_outbound' => 'boolean'
    ];

    public function scopeConversationType($query, $type)
    {
        $query->where('type', CampaignTypes::getKey($type));
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function items()
    {
        return $this->belongsToMany(Items::class, 'invoice_items', 'invoice_id', 'item_id')
            ->withPivot('quantity', 'discount')->withTimestamps();
    }
}
