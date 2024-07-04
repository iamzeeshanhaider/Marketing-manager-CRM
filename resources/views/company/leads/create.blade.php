@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
    <style>
        .iti {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    <section>
        <x-bread-crumb current="Leads" :previous="$company
            ? [['name' => 'Company: ' . $company->name, 'route' => route('companies.show', $company->id)]]
            : []">
            <x-action-button
                route="{{ url()->previous() === request()->url() ? route('leads.index', optional($company)->id) : url()->previous() }}" />
        </x-bread-crumb>

        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title" id="">
                    {{ optional($lead)->full_name ? 'Update' : 'Create' }} Lead
                    @if ($company)
                        for <a href="{{ route('companies.show', $company->id) }}">{{ $company->name }}</a> Company
                    @endif
                </h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form
                        action="{{ route(optional($lead)->id ? 'leads.update' : 'leads.store', optional($lead)->id ? ['lead' => $lead->id] : []) . (optional($company)->id ? '?company=' . $company->id : '') }}"
                        method="post" class="form" enctype="multipart/form-data">
                        @csrf
                        @isset($lead)
                            @method('PATCH')
                        @endisset
                        <div class="form-body">
                            <h4 class="form-section"><i class="ft-disc"></i> {{ $lead ? 'Update' : '' }} Lead Info</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.input name="full_name"
                                        value="{{ optional($lead)->full_name ?? old('full_name') }}" label="Full Name" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.lead-source-select name="source" id="lead_source_select" :selected="optional($lead)->source ?? null"
                                        label="Lead Source" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="email" type="email"
                                        value="{{ optional($lead)->email ?? old('email') }}" label="Email Address" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="appointment_date" type="date"
                                        value="{{ optional($lead)->appointment_date ?? old('appointment_date') }}"
                                        label="Enter Appointment Date" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="consultation_date" type="date"
                                        value="{{ optional($lead)->consultation_date ?? old('consultation_date') }}"
                                        label="Enter Consultation Date" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="website" type="text"
                                        value="{{ optional($lead)->website ?? old('website') }}" label="Website" />
                                </div>
                                <div class="col-md-6">
                                    <label for="lead_industry">Lead Industry</label>
                                    <select name="lead_industry" id="lead_industry" class="form-control" required>
                                        <option value="">Select Lead Industry</option>
                                        @foreach (\App\Enums\LeadIndustry::getInstances() as $source)
                                            <option {{ optional($lead)->lead_industry == $source ? 'selected' : '' }}
                                                value="{{ $source }}">
                                                {{ $source }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="date_of_birth" type="date"
                                        value="{{ optional($lead)->date_of_birth ?? old('date_of_birth') }}"
                                        label="Enter Date Of Birth" />
                                </div>
                                <div class="col-md-6">
                                    <label for="lead_type">Lead Type</label>
                                    <select name="lead_type" id="lead_type" class="form-control" required>
                                        @foreach (\App\Enums\LeadType::getInstances() as $source)
                                            <option {{ optional($lead)->lead_type == $source ? 'selected' : '' }}
                                                value="{{ $source }}">
                                                {{ $source }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <x-form.select-email-status name="email_status" id="email_status_select"
                                        :selected="optional($lead)->email_status ?? null" label="Email Status" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="tel" type="tel" id="tel"
                                        value="{{ optional($lead)->tel }}" label="Phone Number" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.select-phone-status name="tel_status" id="tel_status_select" :selected="optional($lead)->tel_status ?? null"
                                        label="Call Status" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.textarea name="qualification" label="Qualificatin"
                                        value="{{ optional($lead)->qualification ?? old('qualification') }}" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.textarea name="work_experience" label="Experience"
                                        value="{{ optional($lead)->work_experience ?? old('work_experience') }}" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input name="comments"
                                        value="{{ optional($lead)->comments ?? old('comments') }}" label="Comments" />
                                </div>
                            </div>
                            <input type="hidden" name="fullPhone" id="fullPhone" value="">
                            <h4 class="form-section"><i class="ft-disc"></i> Lead Address Info</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.select-countries :selected="optional($lead)->country"></x-form.select-countries>
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="city"
                                        value="{{ optional($lead)->city ? $lead->city : old('city') }}" label="City" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="state"
                                        value="{{ optional($lead)->state ? $lead->state : old('state') }}"
                                        label="State" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="postcode"
                                        value="{{ optional($lead)->postcode ? $lead->postcode : old('postcode') }}"
                                        label="PostCode" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.textarea name="address" label="Address"
                                        value="{{ optional($lead)->address ?? old('address') }}" />
                                </div>
                            </div>

                            <h4 class="form-section"><i class="ft-disc"></i> Additional Info</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.select-company name="company_id" :selected="$selectedCompany ? [$selectedCompany] : old('company_id')" label="Company"
                                        id="lead_company_select" />
                                </div>
                                @if (auth()->user()->hasPermissionTo('assign_agent'))
                                    <div class="col-md-6">
                                        <x-form.select-user name="agent_id" :selected="optional($lead)->agent ? [optional($lead)->agent] : old('agent_id')" label="Assigned Agent"
                                            id="lead_agent_select" />
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <x-form.select-lead-status name="status" id="lead_status_select" :selected="optional($lead)->leadStatus ?? null"
                                        label="Lead Status" />
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <x-form.button label="{{ $lead ? 'Update' : 'Save' }}" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>
    <script>
        const input = document.querySelector("#tel");
        const iti = window.intlTelInput(input, {

            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js",
        });

        input.addEventListener('blur', function() {
            const countryCode = iti.getSelectedCountryData().dialCode;
            const phoneNumber = input.value;
            const fullPhoneNumber = countryCode + phoneNumber;
            $("#fullPhone").val(fullPhoneNumber);
        });
    </script>
@endpush
