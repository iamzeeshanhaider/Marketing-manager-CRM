<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'color_code' => $this->color_code,
        ];
    }

    public static function sanitizeResponse($request): array
    {
        if (!$request) { return null;}

        return [
            'name' => $request['name'],
            'color_code' => $request['color_code'],
            'company_id' => $request['company_id'],
        ];
    }
}
