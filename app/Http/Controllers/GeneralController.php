<?php

namespace App\Http\Controllers;

use Winter\LaravelConfigWriter\ArrayFile;
use App\Enums\CampaignTypes;
use App\Enums\GeneralStatus;
use App\Enums\MeetingType;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\LeadStatusResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\PermissionSelectResource;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\Department;
use App\Models\Country;
use App\Models\InvoiceItems;
use App\Models\Items;
use App\Models\Lead;
use App\Models\LeadConversation;
use App\Models\LeadStatus;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Artisan;
use App\Mail\InvoiceEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    /**
     * Display a listing of all companys.
     */
    public function getCompanyList(Request $request): JsonResponse
    {
        $companies = Company::with(['employees:id,name', 'departments:id,name'])
            ->hasStatus(GeneralStatus::Active())
            ->search($request->collect())
            ->get();
        return response()->json(CompanyResource::collection($companies));
    }

    /**
     * Display a listing of all departments for a company.
     */
    public function getDepartmentList(Request $request, Company $company): JsonResponse
    {
        $departments = Department::where('company_id', $company->id)
            ->hasStatus(GeneralStatus::Active())
            ->search($request->collect())
            ->get();

        return response()->json(DepartmentResource::collection($departments));
    }

    /**
     * Display a listing of all roles
     */
    public function getRoleList(Request $request): JsonResponse
    {
        $roles = Role::search($request->collect())->get();
        return response()->json(RoleResource::collection($roles));
    }


    public function getCountryList(Request $request): JsonResponse
    {
        $countries = Country::search($request->collect())->orderBy('id', 'desc')->get();
        return response()->json(CountryResource::collection($countries));
    }

    public function getPermissionsList(Request $request): JsonResponse
    {
        $search = $request->query();
        $permissions = Permission::search($request->collect())->get();

        return response()->json(PermissionSelectResource::collection($permissions));
    }
    public function getItems(Request $request): JsonResponse
    {
        $items = Items::search($request->collect())->get();


        $itemData1 = [
            'name' => 'Item 1',
            'id' => '1',
            'price' => 100
        ];

        $itemData2 = [
            'name' => 'Item 2',
            'id' => '2',
            'price' => 200
        ];

        $itemData3 = [
            'name' => 'Item 3',
            'id' => '3',
            'price' => 300
        ];

        $itemData4 = [
            'name' => 'Item 4',
            'id' => '4',
            'price' => 400
        ];

        $itemsData = [$itemData1, $itemData2, $itemData3, $itemData4];

        // Transform each item data into a PermissionSelectResource instance
        $itemsResources = collect($itemsData)->map(function ($itemData) {
            return new PermissionSelectResource($itemData);
        });

        // Return the collection of item resources as a JSON response
        return response()->json($itemsResources);



        // return response()->json(PermissionSelectResource::collection($items));
    }

    function getuserPermissionsList(Request $request): JsonResponse
    {
        $user = $request->has('user') ? User::find($request['user']) : null;
        $permissions = $user->permissions;
        return response()->json(PermissionSelectResource::collection($permissions));
    }


    /**
     * Display a listing of all lead status
     */
    public function getLeadStatusList(Request $request): JsonResponse
    {
        $company = $request->has('company') ? Company::find($request['company']) : null;

        $status = LeadStatus::search($request->collect())->when($company, function ($query, $company) {
            return $query->whereHas('company', function ($query) use ($company) {
                $query->where('companies.id', $company->id);
            });
        })
            ->get();

        return response()->json(LeadStatusResource::collection($status));
    }

    /**
     * Display a listing of all users filter by role and company
     */
    public function getUserList(Request $request): JsonResponse
    {
        $company = $request->has('company') ? Company::find($request['company']) : null;
        $role = $request->has('role') ? Role::find($request['role']) : null;
        $agentRole = Role::where('name', 'Agent')->first();
        $users = User::search($request->collect())
            ->when($company, function ($query, $company) {
                return $query->whereHas('companies', function ($query) use ($company) {
                    $query->where('companies.id', $company->id);
                });
            })
            ->when($role, function ($query, $role) {
                return $query->whereHas('roles', function ($query) use ($role) {
                    $query->where('role.id', $role->id);
                });
            })
            ->whereHas('roles', function ($query) use ($agentRole) {
                $query->where('roles.id', $agentRole->id);
            })
            ->hasStatus(GeneralStatus::Active())
            ->get();
        return response()->json(UserResource::collection($users));
    }

    function saveInvoice(Request $request, Lead $lead)
    {
        try {
            $validatedData = $request->validate([
                'subject' => 'required',
                'itemsArray' => 'required|array',
                'content' => 'required|max:250',
                'items_Data' => 'required',
            ]);

            $data = [
                'subject' => $validatedData['subject'],
                'message' => $validatedData['content'],
                'agent_id' => auth()->user()->id,
                'type' => CampaignTypes::Invoice,
            ];


            $items = json_decode($validatedData['items_Data']);
            $invoice =   $lead->conversations()->create($data);
            foreach ($items as $item) {
                $invoiceItem = new InvoiceItems();
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->quantity = $item->quantity;
                $invoiceItem->discount = $item->discount;
                $invoiceItem->item_id = $item->itemId;
                $invoiceItem->save();
            }
            $this->createInvoice($invoice);
            return response()->json(['message' => 'Invoice saved successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }


    function createInvoice($invoice)
    {
        try {
            $lead = $invoice->lead;
            $items = $invoice->items;
            foreach ($items as $item) {
                $pivotData = $item->pivot;
                $item->quantity = $pivotData->quantity;
                $item->discount = $pivotData->discount;
            }
            $data = [
                'name' => $lead->full_name,
                'email' => $lead->email,
                'created_date' => $invoice->created_at,
                'items' => $items,
            ];
            $html = view('items.invoice', ['invoice' => $data])->render();
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);

            $dompdf->loadHtml($html);
            $dompdf->render();
            $fileName = 'invoice_' . $invoice->id . '.pdf';

            if (env('FILESYSTEM_DISK') == 's3') {
                Storage::disk('s3')->put('invoices/' . $fileName, $dompdf->output());
                $storagePath = Storage::disk('s3')->url('invoices/' . $fileName);
            } elseif (env('FILESYSTEM_DISK') == 'local') {
                $fileContent = $dompdf->output();
                $destinationDirectory = public_path() . '/invoices';
                if (!file_exists($destinationDirectory)) {
                    mkdir($destinationDirectory, 0777, true);
                }
                $randomName = uniqid() . '.pdf';
                file_put_contents($destinationDirectory . '/' . $randomName, $fileContent);
            }
            $invoice->invoice =  $randomName;
            $invoice->save();
        } catch (Exception $e) {
            return response()->json('error', $e->getMessage());
        }
    }

    function sendInvoice(LeadConversation $invoice)
    {
        try {
            $lead = $invoice->lead;
            $items = $invoice->items;
            foreach ($items as $item) {
                $pivotData = $item->pivot;
                $item->quantity = $pivotData->quantity;
                $item->discount = $pivotData->discount;
            }
            $data = [
                'name' => $lead->full_name,
                'email' => $lead->email,
                'created_date' => $invoice->created_at,
                'items' => $items,
            ];
            $html = view('items.invoice', ['invoice' => $data])->render();
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->render();
            Mail::to($invoice->lead->email)->send(new InvoiceEmail($dompdf, $invoice->lead));
            return response()->json(['message' => 'Invoice saved successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    function updateSelectedCompany(Request $request, Company $company)
    {
        if ($request->input('selectedCompany')) {
            $company = Company::find($request->input('selectedCompany'));
        }
        $calander = $company->calendar;
        session(['COMPANY_ID' => $company->id]);
        if (!$calander)
            return response()->json(['status' => 'calander_error', 'message' => "Set the calander Credentials"]);
        if ($calander->calendar_type == MeetingType::Zoom) {
            $this->update_config_value('zoom.client_id', $calander->client_id, 'services');
            $this->update_config_value('zoom.client_secret', $calander->client_secret, 'services');
            $this->update_config_value('zoom.redirect', $calander->redirect, 'services');
        } else if ($calander->calendar_type == MeetingType::Google) {
            $this->update_config_value('google.client_id', $calander->client_id, 'services');
            $this->update_config_value('google.client_secret', $calander->client_secret, 'services');
            $this->update_config_value('google.redirect', $calander->redirect, 'services');
        }
        $this->update_config_value('calender', $calander->calendar_type, 'services');
        return response()->json(['status' => 'success', 'url' => route('dashboard')], 200);
    }

    public function setEnvironmentValue($key, $value)
    {
        $envFilePath = base_path('.env');
        $envContent = file_get_contents($envFilePath);
        $pattern = "/^{$key}=(.*)$/m";
        $envContentUpdated = preg_replace($pattern, "{$key}={$value}", $envContent);
        file_put_contents($envFilePath, $envContentUpdated);
        return true;
    }

    function updateInvoice(Request $request,   LeadConversation $invoice)
    {
        try {
            $validatedData = $request->validate([
                'subject' => 'required',
                'itemsArray' => 'required|array',
                'content' => 'required|max:250',
                'items_Data' => 'required',
            ]);
            $invoice->subject = $validatedData['subject'];
            $invoice->message = $validatedData['content'];
            $invoice->agent_id = auth()->user()->id;
            $invoice->save();
            InvoiceItems::where('invoice_id', $invoice->id)->delete();
            $items = json_decode($validatedData['items_Data']);
            foreach ($items as $item) {
                $invoiceItem = new InvoiceItems();
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->quantity = $item->quantity;
                $invoiceItem->discount = $item->discount;
                $invoiceItem->item_id = $item->itemId;
                $invoiceItem->save();
            }
            $this->createInvoice($invoice);
            return response()->json(['message' => 'Invoice saved successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    public function showSelectionForm()
    {
        $companies = Company::active()->get();
        return view('company.dropdown', ['companies' => $companies]);
    }


    function update_config_value($key, $value, $file)
    {
        $filePath = base_path('config/' . $file . '.php');

        if (!file_exists($filePath)) {
            throw new Exception("Configuration file does not exist: " . $filePath);
        }
        $config = ArrayFile::open($filePath);
        $config->set($key, $value);
        $config->write();
    }
}
