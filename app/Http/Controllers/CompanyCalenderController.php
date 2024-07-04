<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Enums\MeetingType;
use App\Services\FileUploadService;
use App\Models\CompanyCalender;
use Illuminate\Http\Request;
use Winter\LaravelConfigWriter\ArrayFile;
use Exception;

class CompanyCalenderController extends Controller
{
    protected $fileUploadService;
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }


    public function companyCalender()
    {
        $meetingTypes = MeetingType::toSelectArray();
        $company = Company::query()->withCount(['employees', 'departments'])->find(\App\Models\Company::first()->id);
        return view('company.calender', compact('company', 'meetingTypes'));
    }


    function UpdateMeetingType(Request $request)
    {
        try {
            $company_id = $request->input('company_id');
            $cc_email = $request->input('cc_email');
            $meeting_type = $request->input('meeting_type');
            $url  = $api_key = $client_secret = $client_id = "";
            if ($meeting_type == MeetingType::Google || $meeting_type == MeetingType::Zoom) {
                $client_secret = $request->input('client_secret');
                $client_id = $request->input('client_id');
                $redirect = $request->input('redirect');
            } else {
                $api_key = $request->input('api_key');
            }

            $existingRecord = CompanyCalender::where('company_id', $company_id)->first();
            if ($existingRecord) {
                $existingRecord->update(['calendar_type' => $meeting_type, 'cc_email' => $cc_email, 'client_secret' => $client_secret, 'client_id' => $client_id, 'api_key' => $api_key, 'redirect' => $redirect]);
            } else {
                CompanyCalender::create([
                    'company_id' => $company_id,
                    'calendar_type' => $meeting_type,
                    'client_secret' => $client_secret,
                    'cc_email' => $cc_email,
                    'client_id' => $client_id,
                    'api_key' => $api_key,
                    'redirect' => $redirect
                ]);
            }
            $this->update_config_value($meeting_type . '.client_secret', $client_secret, 'services');
            $this->update_config_value($meeting_type . '.client_id', $client_id, 'services');
            $this->update_config_value($meeting_type . '.redirect', $redirect, 'services');
            $this->update_config_value('calender', $meeting_type, 'services');
            return response()->json(['message' => 'Meeting Type Updated Successfully ', 'status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unexpected error: ' . $e->getMessage(), 'status' => 'error']);
        }
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
