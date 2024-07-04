<?php

namespace App\Http\Resources;

use App\Enums\LeadSource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Queue\NullQueue;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    public static function sanitizeResponse($request): ?array
    {
        if (!$request) {
            return null;
        }

        return [
            'full_name' => $request['full_name'],
            'email' => $request['email'],
            'email_status' => $request['email_status'],
            'tel' => $request['tel'],
            'tel_status' => $request['tel_status'],

            'address' => $request['address'],
            'state' => $request['state'],
            'city' => $request['city'],
            'postcode' => $request['postcode'],
            'country' => $request['country'],

            'website' => $request['website'],
            'lead_industry' => $request['lead_industry'],
            'lead_type' => $request['lead_type'],
            'date_of_birth' => $request['date_of_birth'],

            'client_subject' => $request['client_subject'],
            'source' => $request['source'] ? LeadSource::fromKey($request['source']) : null,

            'company_id' => $request['company_id'],
            'agent_id' => $request['agent_id'],
            'status' =>  intval($request['status']),
        ];
    }
}
