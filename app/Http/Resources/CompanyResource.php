<?php

namespace App\Http\Resources;

use App\Enums\GeneralStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'email' => $this->email,
        ];
    }

    public static function sanitizeResponse($request)
    {
        if (!$request) {
            return null;
        }

        return [
            'name' => $request['name'],
            'email' => $request['email'],
            'logo' => $request['logo'],
            'allowed_users' => $request['allowed_users'],
            'status' => $request['status'] ? GeneralStatus::fromKey($request['status']) : GeneralStatus::Active(),
        ];
    }
}
