<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebHookController extends Controller
{
    public function handle_vonage_webhook(Request $request, $action)
    {
        \Log::info('Web Hook Request' . $request);
        \Log::info('Web Hook Action' . $action);
    }
}
