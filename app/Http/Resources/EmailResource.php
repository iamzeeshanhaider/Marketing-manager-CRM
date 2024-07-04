<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'type' => $this->type,
            'body' => $this->body,
        ];
    }
    public static function sanitizeResponse($request): array
    {
        if (!$request) {
            return null;
        }

        return [
            'title' => $request['title'],
            'type' => $request['type'],
            'body' => $request['body'],
        ];
    }
}
