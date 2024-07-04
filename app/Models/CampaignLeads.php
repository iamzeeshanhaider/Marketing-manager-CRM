<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignLeads extends Model
{
    use HasFactory;

    protected $table = 'campaign_leads';
    protected $fillable = ['campaign_id', 'lead_id', 'is_sent'];

    /**
     * Get the campaign that owns the CampaignLeads
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the lead that owns the CampaignLeads
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
