<?php

namespace App\Http\Livewire\Lead;

use App\Enums\CampaignTypes;
use App\Http\Livewire\Lead\collection;
use App\Http\Livewire\Lead\redirect;
use App\Http\Resources\LeadConversationResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Items;
use App\Models\Lead;
use App\Models\LeadConversation;
use Exception;
use Livewire\Component;
use Illuminate\Http\Request;

class InvoiceView extends Component
{
    public $conversations;
    public $perPage = 10;
    public $invoiceData;
    public $invoiceDataItems;
    public $invoiceDataItemsIds;
    public $hiddenFieldDAta;
    public $page = 1;
    public $lead;
    public $invoice_id = null;
    public $showInvoice = false;
    public $content;
    public  $subject;
    public $items;
    public $itemsArray = [];
    public $items_Data = '';
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
        $this->invoiceData = null;
        $this->invoiceDataItemsIds = null;
        $this->invoiceDataItems = null;
        $this->hiddenFieldDAta = null;
        $this->items = Items::all();
        $this->loadConversations();
    }
    private function loadConversations()
    {
        $query = $this->lead->conversations()->conversationType(CampaignTypes::Invoice)->latest();
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


    public function editInvoice($invoiceId)
    {
        $this->invoiceData = LeadConversation::find($invoiceId);
        $this->invoiceDataItems = $this->invoiceData->items()->withPivot('quantity', 'discount')->get();

        foreach ($this->invoiceDataItems as $inItem) {
            $emptyObject = new \stdClass();
            $emptyObject->price =  $inItem->price;
            $emptyObject->itemId =  $inItem->id;
            $emptyObject->name =  $inItem->name;
            $emptyObject->quantity = $inItem->pivot->quantity;
            $emptyObject->discount = $inItem->pivot->discount;
            $this->hiddenFieldDAta[] = $emptyObject;
        }
        $this->hiddenFieldDAta = json_encode($this->hiddenFieldDAta);
        $this->invoiceDataItemsIds = $this->invoiceData->items->pluck('id')->toArray();
        $this->showInvoice = true;
    }

    public function hideInvoiceForm()
    {
        $this->showInvoice = !$this->showInvoice;
        $this->reset(['invoiceData', 'invoiceDataItems']);
    }




    public function deleteInvoice($invoiceId)
    {
        try {
            $invoicedel = LeadConversation::find($invoiceId);
            if ($invoicedel->invoice) {
                Storage::delete($invoicedel->invoice);
            }
            $invoicedel->items()->delete();
            $invoicedel->delete();
            $status = 'success';
            $message = 'Operation Successful';
            $this->reset(['items', 'loading', 'show_form']);
        } catch (\Throwable $th) {
            $status = 'danger';
            $message = $th->getMessage() . ' (Code: ' . $th->getCode() . ', File: ' . $th->getFile() . ', Line: ' . $th->getLine() . ')';
            $this->reset(['loading']);
        }
        $this->emit('displayAlert', $status, $message);
        $this->loadConversations();
    }


    public function loadMore()
    {
        $this->page++;
        $this->loadConversations();
    }

    /**
     * Save invoice
     *
     * @return redirect
     */
    public function saveInvoice(Request $request)
    {

        $formData = $request->all();
        dd($formData);
        // Dump and die to inspect the raw form data

        $validatedData = $this->validate([
            'content' => 'nullable|max:250',
            'subject' => 'nullable',
            // 'items' => 'nullable|array',
            'items_Data' => 'required|array',
        ]);
        dd($validatedData);
        $this->loading = true;
        try {
            // save data to conversations
            $this->lead->conversations()->create(LeadConversationResource::sanitizeResponse(CampaignTypes::Invoice, $validatedData));

            $status = 'success';
            $message = 'Operation Successful';
            $this->reset(['invoice', 'items', 'loading', 'show_form']);
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
        return view('livewire.lead.invoice-view');
    }
}
