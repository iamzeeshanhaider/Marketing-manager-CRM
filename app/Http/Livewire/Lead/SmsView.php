<?php

namespace App\Http\Livewire\Lead;

use App\Enums\CampaignTypes;
use App\Http\Livewire\Lead\collection;
use App\Http\Livewire\Lead\redirect;
use App\Http\Requests\SendSMSRequest;
use App\Models\Lead;
use App\Services\VonageSMSService;
use Livewire\Component;

class SmsView extends Component
{
    protected $smsService;
    public $conversations;
    public $perPage = 10;
    public $page = 1;
    public $lead;
    public $content;
    public $hasMore = false;
    public $loading = false;

    public function boot(VonageSMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     *  function
     *
     * @return collection
     */
    public function mount(Lead $lead)
    {
        $this->lead = $lead;
        $this->loadConversations();
    }

    private function loadConversations()
    {
        $query = $this->lead->conversations()->conversationType(CampaignTypes::SMS)->latest();

        $offset = ($this->page - 1) * $this->perPage;
        $newConversations = $query->offset($offset)->limit($this->perPage)->get();

        $this->hasMore = $query->skip($offset + $this->perPage)->take(1)->exists();

        if ($this->page === 1) {
            $this->conversations = $newConversations;
        } else {
            $this->conversations = $this->conversations->concat($newConversations);
        }
    }

    public function loadMore()
    {
        $this->page++;
        $this->loadConversations();
    }

    /**
     * Send SMS using vonage
     *
     * @param SendSMSRequest $request
     * @return redirect
     */
    public function sendSMS()
    {
        $this->loading = true;

        $validatedData = $this->validate([
            'content' => 'required|max:150'
        ]);

        try {
            // send sms and save to database
            $response = $this->smsService->sendSMS($this->lead, $validatedData);

            $status =  $response['success'] ? 'success' : 'danger';
            $message = $response['message'];
            $this->reset(['content', 'loading']);
        } catch (\Throwable $th) {
            $status = 'danger';
            $message = $th->getMessage();
            $this->reset(['loading']);
        }

        // Set the alert message
        $this->emit('displayAlert', $status, $message);
        $this->loadConversations();

    }

    public function render()
    {
        return view('livewire.lead.sms-view');
    }
}
