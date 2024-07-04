<?php

namespace App\Http\Livewire\Lead;

use App\Enums\CampaignTypes;
use App\Http\Livewire\Lead\collection;
use App\Http\Livewire\Lead\redirect;
use App\Http\Requests\SendSMSRequest;
use App\Models\Lead;
use App\Services\VonageCallService;
use Livewire\Component;

class CallView extends Component
{
    protected $callService;
    public $conversations;
    public $perPage = 10;
    public $page = 1;
    public $lead;
    public $message;
    public $alertStatus = '';
    public $alertMessage = '';
    public $hasMore = false;
    public $loading = false;

    public function boot(VonageCallService $callService)
    {
        $this->callService = $callService;
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
        $query = $this->lead->conversations()->conversationType(CampaignTypes::Call)->latest();

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
    public function startCall()
    {
        $this->loading = true;
        $response = $this->callService->startCall($this->lead);

        // Set the alert message
        $this->emit('displayAlert', $response['success'] ? 'success' : 'danger', $response['message']);
        $this->loading = true;

        $this->loadConversations();
    }

    public function render()
    {
        return view('livewire.lead.call-view');
    }
}

