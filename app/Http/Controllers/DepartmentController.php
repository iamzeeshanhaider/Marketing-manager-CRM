<?php

namespace App\Http\Controllers;

use App\Enums\GeneralStatus;
use App\Http\Resources\DepartmentResource;
use App\Models\Company;
use App\Models\Department;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     */
    public function index(Company $company)
    {
        $departments = $company->departments()->with(['company', 'employees'])->withCount(['employees'])->get();
        return view('company.department.index', compact(['company', 'departments']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     */
    public function create(Company $company)
    {
        $department = null;
        return view('company.department.create', compact(['company', 'department']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Company $company
     *
     */
    public function store(Request $request, Company $company): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|' . new EnumValue(GeneralStatus::class),
        ]);

        try {
            DB::beginTransaction();

            Department::create(DepartmentResource::sanitizeResponse($request, $company));

            DB::commit();

            return redirect()->route('department.index', $company->id)->with('success', 'Operation Successful');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Company $company
     * @param Department $department
     */
    public function show(Company $company, Department $department)
    {
        return view('company.department.show', compact(['company', 'department']));
    }

    /**
     *
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     * @param Department $department
     */
    public function edit(Company $company, Department $department)
    {
        return view('company.department.create', compact(['company', 'department']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Company $company
     * @param Department $department
     */
    public function update(Request $request, Company $company, Department $department): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|' . new EnumValue(GeneralStatus::class),
        ]);

        try {
            DB::beginTransaction();

            $department->update(DepartmentResource::sanitizeResponse($request, $company));

            DB::commit();

            return redirect()->route('department.index', $company->id)->with('success', 'Operation Successful');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @param Department $department
     */
    public function destroy(Company $company, Department $department)
    {
        if (!count($department->employees)) {
            $department->delete();
            return redirect()->back()->with('success', 'Operation Successful');
        } else {
            return redirect()->back()->with('error', 'Unable to perform operation');
        }
    }
}
