<?php

namespace App\Http\Livewire\Lead;

use App\Enums\CampaignTypes;
use App\Http\Livewire\Lead\collection;
use App\Http\Livewire\Lead\redirect;
use App\Http\Resources\LeadConversationResource;
use App\Mail\LeadEmail;
use App\Models\Lead;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class EmailView extends Component
{
    public $conversations;
    public $perPage = 10;
    public $page = 1;
    public $lead;
    public $content, $subject;
    public $alertStatus = '';
    public $alertMessage = '';
    public $show_form = false;
    public $hasMore = false;
    public $loading = false;

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
        $query = $this->lead->conversations()->conversationType(CampaignTypes::Email)->latest();

        $offset = ($this->page - 1) * $this->perPage;
        $newConversations = $query->offset($offset)->limit($this->perPage)->get();

        $this->hasMore = $query->skip($offset + $this->perPage)->take(1)->exists();

        if ($this->page === 1) {
            $this->conversations = $newConversations;
        } else {
            $this->conversations = $this->conversations->concat($newConversations);
        }
    }

    public function toggleForm()
    {
        $this->show_form = !$this->show_form;
    }

    public function loadMore()
    {
        $this->page++;
        $this->loadConversations();
    }

    /**
     * Send SMS using vonage
     *
     * @return redirect
     */
    public function sendEmail()
    {
        $validatedData = $this->validate([
            'subject' => 'required|min:5|max:50',
            'content' => 'required'
        ]);

        $this->loading = true;

        try {
            // Send personal email
            // TODO: replicate implementation from email campaign
            Mail::to($this->lead)->send((new LeadEmail($validatedData)));
            // save data to conversations
            $this->lead->conversations()->create(LeadConversationResource::sanitizeResponse(CampaignTypes::Email, $validatedData));

            $status = 'success';
            $message = 'Operation Successful';
            $this->reset(['subject', 'content', 'loading', 'show_form']);
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
        return view('livewire.lead.email-view');
    }
}
