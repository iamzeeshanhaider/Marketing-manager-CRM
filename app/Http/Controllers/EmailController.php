<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmailResource;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    function index()
    {
        $emails = Email::all();
        return view('email.index', compact('emails'));
    }

    function create()
    {
        $email = null;
        return view('email.create', compact('email'));
    }

    function edit(Email $email)
    {
        return view('email.create', compact('email'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required',
            'body' => 'required',
        ]);

        try {
            DB::beginTransaction();

            Email::create(EmailResource::sanitizeResponse($request));

            DB::commit();

            return redirect()->route('email.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function show(Email $email)
    {
        return view('email.show', compact('email'));
    }

    public function update(Request $request, Email $email)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required',
            'body' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $email->update(EmailResource::sanitizeResponse($request));

            DB::commit();

            return redirect()->route('email.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }




    public function destroy(Email $email)
    {

        if ($email->delete()) {
            return redirect()->back()->with('success', 'Operation Successful');
        } else {
            return redirect()->back()->with('error', 'Unable to perform operation');
        }
    }
}
