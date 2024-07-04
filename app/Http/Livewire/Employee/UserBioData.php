<?php

namespace App\Http\Livewire\Employee;

use App\Enums\Ethnicity;
use App\Enums\Gender;
use App\Enums\UKStatus;
use App\Models\User;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserBioData extends Component
{
    public $previousStep;
    public $currentStep = 'personal_info';

    public $user;
    public $disabled = false;

    // personal_info
    public $name;
    public $lname;
    public $username;
    public $email; // readonly
    public $dob;
    public $gender;
    public $phone;
    public $student_id; // readonly
    public $designation;

    // student_info
    public $country_id;
    public $city;
    public $address;
    public $ethnicity;


    // career_info
    public $qualification;
    public $employment_status;
    public $years_of_experience;


    public function mount()
    {
        $this->user = User::find(Auth::id());

        $this->name = $this->user->name;
        $this->lname = $this->user->lname;
        $this->username = $this->user->username;
        $this->email = $this->user->email ?? 'jgfcvhjgvbj';
        //$this->dob = $this->user->dob();
        $this->gender = $this->user->gender;
        $this->phone = $this->user->phone;
        $this->designation = $this->user->designation;

        $this->country_id = $this->user->country_id;
        $this->city = $this->user->city;
        $this->address = $this->user->address;
        $this->ethnicity = $this->user->ethnicity;

        if ($this->user->bio) {
            $this->student_id = $this->user->bio->student_id;

            $this->qualification = $this->user->bio->qualification;
            $this->employment_status = $this->user->bio->employment_status;
            $this->years_of_experience = $this->user->bio->years_of_experience;
        }
    }

    public function goToPreviousStep()
    {
        $this->currentStep = $this->previousStep;

        $this->previousStep = $this->previousStep === 'personal_info' ? null : 'personal_info';
    }

    public function goToNextStep()
    {
        switch ($this->currentStep) {
            case 'personal_info':
                $this->persistPersonalInfo();
                break;

            case 'demographic_info':
                $this->persistDemographicInfo();
                break;

            case 'career_info':
                $this->persistCareerInfo();
                break;
        }
    }

    public function persistPersonalInfo()
    {
        $validatedData = $this->validate(
            [
                'name' => 'required|max:100',
                'lname' => 'required|max:100',
                'username' => 'required|unique:users,username,' . $this->user->id,
                'email' => 'required|email|unique:users,email,' . $this->user->id,
                'phone' => 'nullable',
                'student_id' => 'nullable',
                'designation' => 'nullable',
            ],
            [
                'name.required' => 'The First Name is required.',
                'lname.required' => 'The Last Name is required.',

            ]
        );

        // Save validated data to database
        $this->user->update($validatedData);

        $this->previousStep = 'personal_info';
        $this->currentStep = 'demographic_info';
    }

    public function persistDemographicInfo()
    {
        $validatedData = $this->validate(
            [
                'dob' => 'required|date',
                'gender' => ['required', new EnumValue(Gender::class)],
                'country_id' => 'required|exists:countries,id',
                'city' => 'nullable',
                'address' => 'nullable',
                'ethnicity' => ['required', new EnumValue(Ethnicity::class)],
                'uk_status' => ['nullable', new EnumValue(UKStatus::class)],
            ],
            [
                'country_id.required' => 'Select a Country of origin.',
            ]
        );

        // Save validated data to database
        $this->user->update($validatedData);

        if ($this->user->isStudent()) {
            $this->previousStep = $this->currentStep;
            $this->currentStep = 'demographic_info';
        } else {
            $this->user->update(['has_completed_profile' => true]);
            return redirect()->intended('/profile')->with('message', 'Profile Updated Successfully.');
        }
    }

    public function persistCareerInfo()
    {
        $validatedData = $this->validate(
            [
                'qualification' => 'required',
                'employment_status' => 'nullable',
                'years_of_experience' => 'nullable',
            ],
        );

        $this->user->bio()->update($validatedData);

        $this->user->update(['has_completed_profile' => true]);
        return redirect()->intended('/profile')->with('message', 'Profile Updated Successfully.');
    }


    public function render()
    {
        return view('employee.userbiodata.user-bio-data');
    }
}
