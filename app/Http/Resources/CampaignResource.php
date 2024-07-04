<?php

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'lead_status_id' => $this->lead_status_id,
            'name' => $this->name,
            'email_status' => $this->email_status,
            'type' => $this->type,
            'email_content' => $this->email_content,
        ];
    }

    /**
     * Sanitize the request data
     *
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public static function sanitizeResponse($request)
    {
        if (!$request) {
            return null;
        }

        return [
            'company_id' => $request['company_id'],
            'lead_status_id' => $request['lead_status_id'],
            'name' => $request['name'],
            'email_status' => $request['email_status'],
            'type' => $request['type'],
            'email_content' => $request['email_content'],
        ];
    }
}
