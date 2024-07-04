<?php

namespace App\Http\Livewire\Lead;

use App\Enums\LeadSource;
use App\Http\Livewire\Lead\collection;
use App\Models\Lead;
use BenSampo\Enum\Rules\EnumValue;
use Livewire\Component;

class Profile extends Component
{
    public $lead;
    public $editing = false;
    public $company, $agent, $lead_status;

    /**
     *  function
     *
     * @return collection
     */
    public function mount(Lead $lead)
    {
        $this->lead = $lead;
        $properties = [
            'full_name', 'email', 'tel', 'address', 'state', 'city',
            'postcode', 'country', 'source', 'company_id', 'status', 'agent_id', 'qualification', 'work_experience', 'data_array'
        ];

        foreach ($properties as $property) {
            $this->$property = $lead->$property ?? '';
        }
        $this->company = $lead->company_id ? $lead->company : null;
        $this->agent = $lead->agent_id ? $lead->agent : null;
        $this->lead_status = $lead->status ? $lead->lead_status : null;
    }

    public function updateLead()
    {
        $validatedData = $this->validate([
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'tel' => 'required_if:email,==,null',

            'address' => 'nullable|string',
            'state' => 'nullable|string',
            'city' => 'nullable|string',
            'postcode' => 'nullable|string',
            'country' => 'nullable|string',

            'source' => 'nullable|' . new EnumValue(LeadSource::class),

            // 'company_id' => 'required|exists:companies,id',
            // 'agent_id' => 'nullable|exists:users,id',
            // 'status' => 'required|exists:lead_statuses,id',
        ]);

        $this->lead->update($validatedData);
        $this->editing = false;
        $this->emit('displayAlert', 'success', 'Lead Information Updated Successfully');
    }

    public function enableEditing()
    {
        $this->editing = true;
    }

    public function render()
    {
        return view('livewire.lead.profile');
    }
}
