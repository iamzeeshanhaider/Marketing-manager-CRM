<?php

namespace App\Http\Resources;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionupdateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'guard_name' => 'web',
        ];
    }

    public static function sanitizeResponse($request): array
    {
        if (!$request) {
            return null;
        }

        return [
            'name' => $request['name'],
            'guard_name' => 'web',
        ];
    }
}
