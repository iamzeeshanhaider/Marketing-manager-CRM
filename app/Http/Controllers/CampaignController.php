<?php

namespace App\Http\Controllers;

use App\Enums\CampaignTypes;
use App\Enums\EmailStatus;
use App\Enums\LeadType;
use App\Http\Resources\LeadConversationResource;
use App\Models\Campaign;
use App\Models\Company;
use App\Models\Lead;
use App\Services\VonageSMSService;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $campaigns = Campaign::query()->search($request->collect())->with(['company', 'leadStatus'])->orderBy('id', 'Desc')->get();
        return view('marketer.campaign.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $campaign = null;
        return view('marketer.campaign.create', compact('campaign'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string',
            'company_id'        => 'required|exists:companies,id',
            'lead_status_id'    => 'required|exists:lead_statuses,id',
            'type'              => 'required|' . new EnumValue(CampaignTypes::class),
            'email_status'      => 'nullable|' . new EnumValue(EmailStatus::class),
            'email_content'     => 'required|string',
            'country'           => 'required',
            'lead_type'         => 'required|' . new EnumValue(LeadType::class),
        ]);

        try {
            DB::beginTransaction();
            $campaign = Campaign::create($request->all());
            $countryId = $campaign->country;
            $leads = Lead::where([
                'company_id' => $campaign->company_id, 'status' => $campaign->lead_status_id,
                'lead_type' => $campaign->lead_type, 'email_status' => $campaign->email_status
            ])
                ->when($countryId < 500, function ($query) use ($countryId) {
                    return $query->where('country', $countryId);
                })
                ->get();
            foreach ($leads as $lead) {
                $lead->conversations()->create(LeadConversationResource::sanitizeResponse(CampaignTypes::fromValue($request['type']), $campaign));
            }

            $campaign->emailCampaignLeads()->attach($leads->pluck('id')->toArray());

            DB::commit();

            return redirect()->route('campaign.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Campaign $campaign)
    {
        return view('marketer.campaign.show', compact('campaign'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        return view('marketer.campaign.create', compact('campaign'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $request->validate([
            'name'              => 'required|string',
            'company_id'        => 'required|exists:companies,id',
            'lead_status_id'    => 'required|exists:lead_statuses,id',
            'type'              => 'required|string',
            'email_status'      => 'nullable|' . new EnumValue(EmailStatus::class),
            'email_content'     => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $campaign->update($request->all());

            DB::commit();

            return redirect()->route('campaign.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        try {
            DB::beginTransaction();

            $campaignConversations = $campaign->campaignConversations;
            foreach ($campaignConversations as $conversation) {
                $conversation->delete();
            }
            $campaign->emailCampaignLeads()->detach();
            $campaign->delete();

            DB::commit();
            return redirect()->route('campaign.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Test SMS view
     */
    public function testSms(Request $request)
    {
        $response = null;
        $request = $request->all();
        if ($request) {
            $phoneNumber = $request['phone_number'];
            $message = $request['sms_text'];

            $vonage = new VonageSMSService();
            $response = $vonage->sendTestSMS($phoneNumber, $message);
        }

        return view('marketer.sms.test-sms', compact('response'));
    }
}
