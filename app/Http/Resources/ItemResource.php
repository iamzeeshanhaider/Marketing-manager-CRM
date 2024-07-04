<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'price' => $this->price,
            'description' => $this->description,
        ];
    }

    public static function sanitizeResponse($request): array
    {
        if (!$request) {
            return null;
        }

        return [
            'name' => $request['name'],
            'price' => $request['price'],
            'description' => $request['description'],
        ];
    }
}
