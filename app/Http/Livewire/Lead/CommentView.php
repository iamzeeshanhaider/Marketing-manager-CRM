<?php

namespace App\Http\Livewire\Lead;

use App\Enums\CampaignTypes;
use App\Http\Livewire\Lead\collection;
use App\Http\Livewire\Lead\redirect;
use App\Http\Resources\LeadConversationResource;
use App\Models\Lead;
use Livewire\Component;

class CommentView extends Component
{
    public $conversations;
    public $perPage = 10;
    public $page = 1;
    public $lead;
    public $comment;
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
        $query = $this->lead->conversations()->conversationType(CampaignTypes::Comment)->latest();
        $offset = ($this->page - 1) * $this->perPage;
        $newConversations = $query->skip($offset)->take($this->perPage)->get();
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
     * Save comment
     *
     * @return redirect
     */
    public function saveComment()
    {

        $validatedData = $this->validate([
            'comment' => 'required|max:250',
        ]);

        $this->loading = true;
        try {
            // save data to conversations
            $this->lead->conversations()->create(LeadConversationResource::sanitizeResponse(CampaignTypes::Comment, $validatedData));
            $status = 'success';
            $message = 'Operation Successful';
            $this->reset(['comment', 'loading', 'show_form']);
        } catch (\Throwable $th) {
            $status = 'danger';
            $message = $th->getMessage() . ' (Code: ' . $th->getCode() . ', File: ' . $th->getFile() . ', Line: ' . $th->getLine() . ')';
            $this->reset(['loading']);
        }

        // Set the alert message
        $this->emit('displayAlert', $status, $message);
        $this->loadConversations();
    }


    public function render()
    {
        return view('livewire.lead.comment-view');
    }
}
