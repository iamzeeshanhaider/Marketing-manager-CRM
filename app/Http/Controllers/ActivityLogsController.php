<?php

namespace App\Http\Controllers;
use App\Models\ActivityLog;
use App\Models\Company;
use Illuminate\Http\Request;

class ActivityLogsController extends Controller
{

    public function index()
    {
        $logs  = ActivityLog::get();

        return view('activity_logs.index', compact(['logs']));
        //return view('activity_logs.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Model\ActivityLog  $logs
     * @return \Illuminate\Http\Response
     */

    public function destroy(ActivityLog $log): RedirectResponse
    {
        if (!count($log->conversations)) {
            $log->delete();
            return redirect()->back()->with('success', 'Operation Successful');
        } else {
            return redirect()->back()->with('error', 'Unable to perform operation');
        }
    }

}
