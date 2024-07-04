<?php

namespace App\Http\Controllers;

use App\Enums\GeneralStatus;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use BenSampo\Enum\Rules\EnumValue;
use constPaths;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $company = Company::find(\App\Models\Company::first()->id);
        $employees =  $company->employees()->search($request->collect())
            ->get();
        return view('employee.index', compact(['employees']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $company = $request->has('company') ? Company::find($request->input('company')) : null;
        $employee = null;
        return view('employee.create', compact(['employee', 'company']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'avatar' => 'nullable|image',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'role_id' => 'nullable|exists:roles,id',
            'status' => 'required|' . new EnumValue(GeneralStatus::class),
        ]);

        try {
            DB::beginTransaction();

            // create employee
            $employee = User::create(UserResource::sanitizeResponse($request));
            if ($request->hasFile('avatar')) {
                $image  = uploadOrUpdateFile($request->file('avatar'), $employee->image, constPaths::UserAvatar);
                $employee->image =  $image;
                $employee->save();
            }
            // sync role, company and department
            UserResource::syncRoleAndCompany($request, $employee);
            $employee->permissions()->sync($request->input('permissions'));
            UserResource::updatePasswordAndNotify($request, $employee);

            DB::commit();

            return redirect()->route('employee.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param User $employee
     */
    public function show(User $employee)
    {
        return view('employee.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $employee
     */
    public function edit(Request $request, User $employee)
    {
        $company = $request->has('company') ? Company::find($request->input('company')) : null;
        return view('employee.create', compact(['employee', 'company']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param User $employee
     */
    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'avatar' => 'nullable|image',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'role_id' => 'nullable|exists:roles,id',
            'status' => 'required|' . new EnumValue(GeneralStatus::class),
        ]);

        if ($request->hasFile('avatar')) {
            $image  = uploadOrUpdateFile($request->file('avatar'), $employee->image, constPaths::UserAvatar);
            $employee->image =  $image;
            $employee->save();
            $request->merge(['avatar' => $image]);
        }
        try {
            DB::beginTransaction();

            $employee->update(UserResource::sanitizeResponse($request));
            $employee->permissions()->sync($request->input('permissions'));
            UserResource::syncRoleAndCompany($request, $employee);
            UserResource::updatePasswordAndNotify($request, $employee);
            DB::commit();
            return redirect()->route('employee.show', $employee->id)->with('success', 'Operation Successful');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $employee
     */
    public function destroy(User $employee)
    {
        if (!count($employee->companies)) {
            $employee->delete();
            return redirect()->back()->with('success', 'Operation Successful');
        } else {
            return redirect()->back()->with('error', 'Unable to perform operation');
        }
    }
}
