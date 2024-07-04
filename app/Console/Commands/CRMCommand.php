<?php

namespace App\Console\Commands;

use App\Mail\MarketingMail;
use App\Models\CampaignLeads;
use App\Services\VonageSMSService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CRMCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crm-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run campaign cron';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        return $this->activateCampaign();
    }


    /**
     * Activate campaign
     */
    public function activateCampaign()
    {
        $campaignLeads = CampaignLeads::with(['campaign', 'lead'])
            ->where('is_sent', false)
            ->skip(0)
            ->take(40)
            ->get()
            ->groupBy('campaign_id');

        foreach ($campaignLeads as $campaignId => $leads) {
            $campaign = $leads->first()->campaign;
            $content = $campaign->email_content;
            $leadIds = $leads->pluck('lead.id')->all();
            $leadEmails = $leads->pluck('lead.email')->all();
            $this->emailCampaign($leadIds, $leadEmails, $content);
        }
        return Command::SUCCESS;
    }



    /**
     * Send email campaign
     * @param $content
     * @param $campaignLead
     */
    public function emailCampaign($leadIds, $leadEmails, $content)
    {
        if (Mail::to($leadEmails)->send(new MarketingMail($content))) {
            $this->updateCampaignLeadStatus($leadIds);
        } else {
            Log::error('Email not sent');
        }
        return true;
    }

    /**
     * Send sms campaign
     * @param $content
     * @param $campaignLead
     */
    public function smsCampaign($content, $campaignLead)
    {
        $data = ['content' => $content];
        $vonage = new VonageSMSService();
        $response = $vonage->sendSMS($campaignLead->lead, $data);
        if ($response['success'] === true) {
            $this->updateCampaignLeadStatus($campaignLead);
        }

        return true;
    }

    /**
     * Update lead status
     * @param $campaignLead
     */
    public function updateCampaignLeadStatus($leadIds)
    {
        CampaignLeads::whereIn('lead_id', $leadIds)->update(['is_sent' => true]);
        return true;
    }
}
