<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadStatusRequest;
use App\Http\Resources\LeadStatusResource;
use App\Models\Company;
use App\Models\LeadStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadStatusController extends Controller
{
    /**
     * Display a listing of the lead statuses.
     *
     * @param Request $request
     * @return Illuminate\View\View
     */
    public function index(Request $request)
    {

        $companyId = (int) \App\Models\Company::first()->id;

        // dd($companyId);
        $leadStatuses = LeadStatus::where('company_id', $companyId)
            ->search($request->collect())
            ->latest()
            ->get();
        // dd($leadStatuses);
        return view('company.leads.status.index', compact('leadStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $company = $request->has('company') ? Company::find($request->input('company')) : null;
        $status = null;

        return view('company.leads.status.create', compact(['company', 'status']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeadStatusRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            LeadStatus::create(LeadStatusResource::sanitizeResponse($request));

            DB::commit();

            return redirect()->route('lead_status.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, LeadStatus $status)
    {
        $company = $request->has('company') ? Company::find($request->input('company')) : $status->company;
        return view('company.leads.status.create', compact(['company', 'status']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeadStatus $status): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $status->update(LeadStatusResource::sanitizeResponse($request));

            DB::commit();

            return redirect()->route('lead_status.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeadStatus $status)
    {
        if (!count($status->leads)) {
            $status->delete();
            return redirect()->back()->with('success', 'Operation Successful');
        } else {
            return redirect()->back()->with('error', 'Unable to perform operation');
        }
    }
}
