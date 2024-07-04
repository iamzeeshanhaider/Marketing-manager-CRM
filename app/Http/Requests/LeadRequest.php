<?php

namespace App\Http\Requests;

use App\Enums\LeadType;
use App\Enums\CallStatus;
use App\Enums\LeadSource;
use App\Enums\EmailStatus;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole(['Admin', 'Manager', 'Agent', 'Data collector']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'email_status' => 'nullable|' . new EnumValue(EmailStatus::class),
            'tel' => 'required_if:email,==,null',
            'tel_status' => 'nullable|' . new EnumValue(CallStatus::class),

            'address' => 'nullable|string',
            'state' => 'nullable|string',
            'postcode' => 'nullable|string',
            'country' => 'required|string',
            'lead_type' => 'required|' . new EnumValue(LeadType::class),

            'client_subject' => 'nullable|string',
            'website' => 'nullable|url',
            'date_of_birth' => 'nullable|string',
            'lead_industry' => 'nullable|string',
            'client_message' => 'nullable|string',
            'source' => 'nullable|' . new EnumValue(LeadSource::class),

            'company_id' => 'required|exists:companies,id',
            'agent_id' => 'nullable|exists:users,id',
            'status' => 'required|exists:lead_statuses,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'company_id.required' => 'Company is required',
            'company_id.exist' => 'Company must be valid',
            'agent_id.required' => 'Company is required',
            'agent_id.exist' => 'Company must be valid',
        ];
    }
}
