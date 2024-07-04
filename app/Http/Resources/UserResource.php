<?php

namespace App\Http\Resources;

use App\Enums\GeneralStatus;
use App\Mail\AccountCreated;
use App\Models\Company;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $role = "";
        if ($this->isAgent())
            $role = " (" . $this->getRoleNames()->first() . ")";

        return [
            'id' => $this->id,
            'name' => $this->name  . $role,
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
            'image' => $request['avatar'],
            'status' => $request['status'] ? GeneralStatus::fromKey($request['status']) : GeneralStatus::Active(),
        ];
    }

    public static function syncRoleAndCompany($request, User $employee)
    {

        $employee->companies()->sync([
            $request['company_id'] ?? null => ['department_id' => $request['department_id'] ?? null],
        ]);

        $roles = Role::whereIn('id', array($request['role_id']))->get();
        $employee->syncRoles($roles);
    }

    public static function updatePasswordAndNotify($request, User $employee): void
    {
        $company = Company::find($request['company_id']);

        $default_password = $request->get('password') ?? "password";

        $employee->update(['password' => Hash::make($default_password)]);

        // Send email
        if ($request->has('notify')) {
            Mail::to($employee)->send(
                (new AccountCreated([
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'default_password' => $default_password,
                    'company_name' => $company->name,
                    'company_email' => $company->email ?? '',
                ]))->afterCommit()
            );
        }
    }
}
