<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionupdateResource;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionsController extends Controller
{
    function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    function create()
    {
        $permission = null;
        return view('permissions.create', compact('permission'));
    }

    function edit(Permission $permission)
    {
        return view('permissions.create', compact('permission'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            Permission::create(PermissionupdateResource::sanitizeResponse($request));

            DB::commit();

            return redirect()->route('permisions.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $permission->update(PermissionupdateResource::sanitizeResponse($request));

            DB::commit();

            return redirect()->route('permisions.index')->with('success', 'Operation Successful');
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }
    }




    public function destroy(Permission $permission)
    {

        if ($permission->delete()) {
            return redirect()->back()->with('success', 'Operation Successful');
        } else {
            return redirect()->back()->with('error', 'Unable to perform operation');
        }
    }
}
