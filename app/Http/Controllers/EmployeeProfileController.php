<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserPasswordRequest;
use App\Models\User;
use constPaths;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Jambasangsang\Flash\Facades\LaravelFlash;

class EmployeeProfileController extends Controller
{

    public $previousStep;
    public $currentStep = 'personal_info';

    public $user;
    public $disabled = false;

    // personal_info
    public $name;
    public $username;
    public $email; // readonly
    public $dob;
    public $gender;
    public $phone;
    public $designation;

    // student_info
    public $country_id;
    public $city;
    public $address;
    public $ethnicity;
    public $uk_status;

    // career_info
    public $qualification;
    public $employment_status;
    public $years_of_experience;

    public function index(Request $request)
    {
        $user = Auth::user();
        $view = $request->get('view') ?? 'profile';
        return view('employee.profile', compact('user', 'view'));
    }

    public function bio(Request $request)
    {
        $user = Auth::user();
        $view = $request->get('view') ?? 'profile';
        return view('employee.users.bio', compact('user', 'view'));
    }

    public function create()
    {

        $user = User::find(Auth::id());

        if (isset($user_id)) {

            $data['companies'] = Company::all();
            return view('admin.add-product', $data);
        } else {
            return redirect('login');
        }
    }

    public function store(Request $request)
    {
        //        $user = User::find(Auth::id());
        try {
            DB::beginTransaction();

            $user = auth()->user();
            $user->name =  $request->get('full_name');
            $user->email = $request->get('user_email');
            $user->phone = $request->get('user_phone');
            $user->designation = $request->get('user_designation');
            $user->dob = $request->get('user_dob');
            $user->gender = $request->get('user_gender');
            $user->ethnicity = $request->get('user_ethnicity');
            $user->country_id = $request->get('user_country');
            $user->city = $request->get('user_city');
            $user->address = $request->get('user_address');
            $user->save();
            DB::commit();

            return redirect()->back()->with('success', 'Profile Updated Successfully');

            // return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            LaravelFlash::withError($th->getMessage());
            return back();
        }
    }

    public function store_photo(Request $request)
    {
        $user = User::find(Auth::id());
        try {
            DB::beginTransaction();
            if ($request->hasFile('image')) {
                $image  = uploadOrUpdateFile($request->file('image'), $user->image, constPaths::UserAvatar);
                $user->image =  $image;
                $user->save();

                DB::commit();
                LaravelFlash::withSuccess('Profile Photo Updated Successfully');
            } else {
                LaravelFlash::withError('Profile Photo Updated Successfully');
            }
            return redirect()->back();
        } catch (\Throwable $th) {
            DB::rollback();
            LaravelFlash::withError($th->getMessage());

            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserPasswordRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store_password(UpdateUserPasswordRequest $request)
    {
        $request->validated();

        #Match The Old Password
        if (Hash::check($request->get('current_password'), auth()->user()->password)) {
            User::find(Auth::id())->update([
                'password' => Hash::make($request->get('new_password'))
            ]);
            LaravelFlash::withSuccess('Password Changed Successfully');
            return redirect()->back();
        } else {
            LaravelFlash::withError('Current Password Doesn\'t match!');
            return redirect()->back();
        }
    }

    public function render()
    {
        return view('employee.user-bio-data.user-bio-data');
    }
}
