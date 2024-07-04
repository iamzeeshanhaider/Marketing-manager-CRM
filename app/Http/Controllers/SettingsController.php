<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use App\Models\SettingsModel;
use constPaths;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    function settings()
    {
        $settings = SettingsModel::first();
        return view('company.leads.updateSettings', compact('settings'));
    }
    function updateSettings(SettingsRequest $request)
    {
        $inputs =  $request->validated();
        if ($request->hasFile('LEADS_SAMPLE')) {
            $oldFile = SettingsModel::where('id', 1)->value('LEADS_SAMPLE');
            $file = $request->file('LEADS_SAMPLE');
            $inputs['LEADS_SAMPLE'] = uploadOrUpdateFile($file, $oldFile, constPaths::LEADS);
        }

        $settings = SettingsModel::updateOrCreate(
            ['company_id' => \App\Models\Company::first()->id],
            $inputs
        );
        Artisan::call('set:general-settings');
        Artisan::call('optimize:clear');
        return redirect()->back()->with('success', 'Settings Updated Successfully');
    }
}
