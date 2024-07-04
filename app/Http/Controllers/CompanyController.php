<?php

namespace App\Http\Controllers;

use App\Enums\GeneralStatus;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::query()->withCount(['employees', 'departments'])->get();
        return view('company.index', compact('companies'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = null;
        return view('company.create', compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'status' => 'required|' . new EnumValue(GeneralStatus::class),
            'logo' => 'nullable|image',
        ]);

        try {
            DB::beginTransaction();

            Company::create(CompanyResource::sanitizeResponse($request));

            DB::commit();

            return redirect()->route('companies.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     *
     * @param Company $company
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return view('company.show', compact('company'));
    }

    /**
     *
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     */
    public function edit(Company $company)
    {
        return view('company.create', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Company $company
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'status' => 'required|' . new EnumValue(GeneralStatus::class),
            'logo' => 'nullable|image',
        ]);

        try {
            DB::beginTransaction();

            $company->update(CompanyResource::sanitizeResponse($request));

            DB::commit();

            return redirect()->route('companies.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     */
    public function destroy(Company $company)
    {
        if (!count($company->employees)) {
            $company->departments()->delete();
            $company->delete();
            return redirect()->back()->with('success', 'Operation Successful');
        } else {
            return redirect()->back()->with('error', 'Unable to perform operation');
        }
    }
}
