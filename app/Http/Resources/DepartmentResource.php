<?php

namespace App\Http\Resources;

use App\Enums\GeneralStatus;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
        ];
    }

    public static function sanitizeResponse($request, Company $company): array
    {
        if (!$request) { return null;}

        return [
            'name' => $request['name'],
            'company_id' => $company->id,
            'status' => $request['status'] ? GeneralStatus::fromKey($request['status']) : GeneralStatus::Active(),
        ];
    }

}
