<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Http\Resources\LeadResource;
use App\Models\Company;
use App\Models\Lead;
use App\Models\User;
use App\Services\VonageCallService;
use App\Traits\MailgunEmailValidation;
use ErrorException;
use Exception;
use constPaths;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Country;
use App\Models\SettingsModel;
use Illuminate\Support\Facades\File;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\View
     */

    public function index(Request $request)
    {
        $companyId = $request->input('company');
        $agent_id = $request->input('agent_id');
        $company = $companyId && !$agent_id ? Company::find($companyId) : null; // Retrieve the Company instance based on the ID
        $companies = Company::all();
        $status = $request->get('status') ?? null; // used for filtering

        $leads = Lead::with(['company', 'agent', 'leadStatus'])
            ->when($request->has('agent_id'), function ($query) use ($request) {
                $query->where('agent_id', $request->input('agent_id'));
            }, function ($query) use ($status) {
                if (!$status)
                    $query->whereNull('agent_id');
            })
            ->filter($request->collect(), $company)
            ->latest()
            ->get();

        if ($request->ajax()) {

            return $this->getDatatable($leads);
        }
        return view('company.leads.index', compact(['company', 'leads', 'status', 'companies']));
    }



    function allLeads(Request $request)
    {
        $companyId = $request->input('company');
        $company = $companyId ? Company::find($companyId) : null;
        $companies = Company::all();
        $status = $request->get('status') ?? null; // used for filtering
        $leads = Lead::with(['company', 'agent', 'leadStatus'])
            ->latest()
            ->where('company_id', \App\Models\Company::first()->id)
            ->get();
        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        })
            ->when($companyId, function ($query) use ($companyId) {
                return $query->whereHas('companies', function ($subquery) use ($companyId) {
                    $subquery->where('company_id', $companyId);
                });
            })
            ->pluck('name', 'id')->toArray();
        if ($request->ajax()) {

            return $this->getDatatable($leads);
        }

        return view('company.leads.allLeads', compact(['company', 'leads', 'status', 'users', 'companies']));
    }

    private function getDataTable($data)
    {
        return DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('', function ($row) {
                return '<input type="checkbox" class="lead_checkbox" value="' . $row->id . '">';
            })
            ->addColumn('company', function ($row) {
                return view('company.leads.partials.table_data.company_details', ['company' => $row->company, 'agent' => $row->agent]);
            })
            ->addColumn('details', function ($row) {
                return view('company.leads.partials.table_data.lead_details', ['lead' => $row]);
            })
            ->addColumn('created_at', function ($row) {
                return   $row->created_at->format('F j, Y');
            })
            ->addColumn('lead_status', function ($row) {
                return view('company.leads.partials.table_data.lead_status_details', ['leadStatus' => $row->leadStatus, 'source' => $row->source]);
            })

            ->addColumn('action', function ($row) {
                return view('company.leads.partials.table_data.actions_dropdown', ['row' => $row]);
            })
            ->rawColumns(['', 'company', 'details', 'created_at', 'lead_status', 'action'])
            ->make(true);
    }

    /**
     * Fetch all leads using lead datatable
     */
    public function getAll(Request $request)
    {
        $company = $request->has('company') ? Company::find($request->input('company')) : null; // used for filtering
        $status = $request->get('status') ?? null; // used for filtering
        $assigned = $request->get('assigned') ?? null; // used for filtering

        // retrieve all leads and account for filter options if available
        $leads = Lead::when($status, function ($query) use ($status) {
            return $query->hasStatus($status);
        })
            ->when($assigned, function ($query) {
                return $query->whereHas('agent', function ($query) {
                    $query->where('agent_id', '!=', null);
                });
            })
            ->when($company, function ($query, $company) {
                return $query->whereHas('company', function ($query) use ($company) {
                    $query->where('companies.id', $company->id);
                });
            })
            ->search($request->collect())
            ->latest()
            ->get();

        $users = array('Select Agent');
        $users = $users + User::whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        })->pluck('name', 'id')->toArray();

        return view('company.leads.index', compact(['company', 'leads', 'status', 'users']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     */
    public function create(Request $request)
    {

        $company = $request->has('company') ? Company::find($request->input('company')) : null;
        $countries = Country::all();
        $lead = null;

        if ($request->has('action') && $request->get('action') === 'upload') {
            return view('company.leads.upload', compact(['company']));
        }
        $selectedCompany =  Company::find(\App\Models\Company::first()->id);
        return view('company.leads.create', compact(['company', 'lead', 'countries', 'selectedCompany']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeadRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $lead = Lead::create(LeadResource::sanitizeResponse($request));
            if ($request->get('agent_id')) {
                $lead->assignAgent(User::find($request->get('agent_id')));
            }

            DB::commit();
            return redirect()->route('leads.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Lead $lead)
    {
        $view = $request->get('view') ?? 'overview';
        $company = $request->has('company') ? Company::find($request->input('company')) : $lead->company;

        return view('company.leads.show', compact(['company', 'lead', 'view']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Lead $lead)
    {
        $company = $request->has('company') ? Company::find($request->input('company')) : $lead->company;
        $selectedCompany =  Company::find(\App\Models\Company::first()->id);
        return view('company.leads.create', compact(['company', 'lead', 'selectedCompany']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeadRequest $request, Lead $lead): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $lead->update(LeadResource::sanitizeResponse($request));

            if ($request->has('agent_id')) {
                $lead->assignAgent(User::find($request->get('agent_id')));
            }

            DB::commit();

            return redirect()->route('leads.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function contactUsLeadform(Request $request)
    {
        $client = array(
            "company_id" => "Company ID",
            "lead_source" => "Lead Source",
            "email_status" => "Email Status",
            "client_name" => "Client Name",
            "client_email" => "Client Email",
            "client_phone" => "Client Phone",
            "client_subject" => "Client Subject",
            "client_message" => "Client Message",
            "data_array" => "Data Array",

        );
        $validator = Validator::make(
            $request->all(),
            [
                "company_id" => "required|numeric",
                "lead_source" => "required|string",
                "email_status" => "required|string",
                "client_name" => "string",
                "client_email" => "required|email",
                "client_phone" => "string",
                "client_subject" => "string",
                "client_message" => "string",
                "data_array" => "string",
            ]
        );

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            try {
                $companycheck = Company::where('id', '=', $request->input('company_id'))->first();
                $leadcheck = Lead::where('email', '=', $request->input('client_email'))
                    ->where('company_id', '=', $request->input('company_id'))
                    ->first();

                if (isset($companycheck)) {

                    if (isset($leadcheck)) {
                        if ($request->input('email_status') == "Unsubscribe" || $request->input('email_status') == "Active") {
                            $leadid = Lead::find($leadcheck->id);
                            $leadid->email_status = $request->input('email_status');
                            $leadid->client_message = $request->input('client_message');
                            $leadid->save();
                            DB::commit();
                            return json_encode(1);
                        } else {
                            //Email is already subscribed
                            return json_encode("Email is already subscribed");
                        }
                    } else {
                        $v = new MailgunEmailValidation();
                        if ($v->mailgunValidate($request->input('client_email')) == true) {
                            DB::beginTransaction();
                            $lead = new Lead();
                            $lead->company_id = $request->input('company_id');
                            $lead->source = $request->input('lead_source');
                            $lead->email_status = $request->input('email_status');
                            $lead->full_name = $request->input('client_name');
                            $lead->email = $request->input('client_email');
                            $lead->tel = $request->input('client_phone');
                            $lead->client_subject = $request->input('client_subject');
                            $lead->client_message = $request->input('client_message');
                            $lead->data_array = $request->input('data_array');
                            $lead->status = 1;
                            $lead->save();

                            DB::commit();
                            return json_encode(1);
                        } else {
                            //Email is not valid
                            return json_encode("Email is not valid");
                        }
                    }
                } else {
                    //company ID not available
                    return json_encode("Company is not valid");
                }
            } catch (Exception | QueryException | ErrorException $e) {
                Session::flash('systemError', 'Caught Exception ' . $e->getCode() . '. Please contact system administrator.');
                return Redirect::back()->withErrors($validator)->withInput();
            }
        }
    }

    /**
     * Assign an agent to a lead
     */
    public function assignLead(Request $request): JsonResponse
    {
        $leads = $request->get('lead_ids');
        $agent = User::find($request->get('agent_id'));
        $leadsassign = Lead::whereIn('id', $leads)
            ->get();

        // Assign the agent to leads without an agent
        $leadsassign->each(function ($lead) use ($agent) {
            $lead->assignAgent($agent);
        });

        return response()->json(['success' => true, 'message' => 'Lead assigned Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead): RedirectResponse
    {
        if (!count($lead->conversations)) {
            $lead->delete();
            return redirect()->back()->with('success', 'Operation Successful');
        } else {
            return redirect()->back()->with('error', 'Unable to perform operation');
        }
    }

    public function upload(Request $request)
    {

        return view('company.leads.upload');
    }

    public function uploadLeads()
    {
        $file = SettingsModel::where('id', 1)->value('LEADS_SAMPLE');
        $sampleFile = constPaths::LEADS . $file;
        return view('company.leads.upload-leads', ['sampleFile' => $sampleFile]);
    }

    public function importExcel(Request $request)
    {
        try {
            $filename = null;
            $extensions = array('csv', 'CSV');

            if ($request->file('csv_file') == NULL) {
                Session::flash('error', "File is not valid. Please upload a valid CSV.");
                return redirect()->back();
            }

            if (!$request->file('csv_file')->isValid()) {
                return Redirect::back()->with('error', 'File is not valid. Please upload a valid CSV.');
            }

            if (!in_array($request->file('csv_file')->getClientOriginalExtension(), $extensions)) {
                return Redirect::back()->with('error', 'File is not valid. Please upload a valid CSV.');
            }

            $unique_name = md5($request->file('csv_file')->getClientOriginalName() . time());
            $filename = $unique_name . '.' . $request->file('csv_file')->getClientOriginalExtension();
            $request->file('csv_file')->move('assets/lead-uploaded', $filename);

            $file = public_path("assets/lead-uploaded/$filename");
            $rows = $this->csvToArray($file);
            $company_id = $request->get('company_id');
            $status = $request->get('status');


            foreach ($rows as $row) {
                if ($company_id != null) {
                    $row['company_id'] = $company_id;
                }

                if ($status != null) {
                    $row['status'] = $status;
                }

                $lead = $this->createLeadFromCsvRow($row);
                if ($lead) {
                    continue;
                }
            }

            Session::flash('success', "Updated Successfully");
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('error', "File is not valid. Please upload a valid CSV. Columns not matched.");
            return Redirect::back();
        }
    }

    // This method will return an array of data from a CSV file
    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 100000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                } else {
                    if (count($header) == count($row)) {
                        $data[] = array_combine($header, $row);
                    } else {
                        return false;
                    }
                }
            }
            fclose($handle);
        }
        return $data;
    }

    // Create lead from csv row
    public function createLeadFromCsvRow($row)
    {
        DB::beginTransaction();
        $lead = new Lead();

        $lead->agent_id = null;
        $lead->status = @$row['status'];
        $lead->tel_status = "Open";
        $lead->company_id = @$row['company_id'];
        $lead->last_email = null;

        $lead->email_status = @$row['email_status'];
        $lead->full_name = $row['name'];
        $lead->tel = $row['phonenumber'];
        $lead->email = $row['email'];
        $lead->address = $row['address'];
        $lead->state = $row['state'];
        $lead->city = $row['city'];
        $lead->postcode = $row['postcode'];
        $lead->country = $row['country'];
        $lead->qualification = @$row['qualification'];
        $lead->work_experience = @$row['work_experience'];
        $lead->save();
        DB::commit();

        return $lead;
    }

    public function vonageCall(Request $request)
    {
        $callService = new VonageCallService();
        $response = $callService->startCall($lead);

        // Set the alert message
        $this->emit('displayAlert', $response['success'] ? 'success' : 'danger', $response['message']);
        $this->loading = true;

        $this->loadConversations();
    }


    function companyAgents(Request $request)
    {

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        })
            ->when($request->has('company_id'), function ($query) use ($request) {
                return $query->whereHas('companies', function ($subquery) use ($request) {
                    $subquery->where('company_id', $request->input('company_id'));
                });
            })
            ->pluck('name', 'id')->toArray();
        if ($users) {
            return response()->json(['success' => true, 'users' => $users]);
        }
        return response()->json(['success' => true, 'message' => 'No Agent found']);
    }
}
