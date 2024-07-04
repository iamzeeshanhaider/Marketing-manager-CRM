@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="row">
        <x-bread-crumb current="Create Employee" :previous="[
            [
                'name' => 'Companies',
                'route' => route('companies.index'),
            ],
        ]">
            <x-action-button route="{{ route('companies.index') }}" />
        </x-bread-crumb>

        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title" id="">
                    @php
                        $coy = optional($employee)->companies
                            ? optional($employee)->companies->first()
                            : $company ?? null;
                    @endphp
                    {{ optional($employee)->name ? 'Update' : 'Create' }} Employee
                    @if ($coy)
                        for <a href="{{ route('companies.show', $coy->id) }}">{{ $coy->name }}</a>
                        Company
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
                        action="{{ optional($employee)->id ? route('employee.update', $employee->id) : route('employee.store') }}"
                        method="post" class="form" enctype="multipart/form-data">
                        @csrf
                        @isset($employee)
                            @method('PATCH')
                        @endisset
                        <div class="form-body">
                            <h4 class="form-section"><i class="ft-disc"></i> {{ $employee ? 'Update' : '' }} Employee Info
                            </h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <x-form.image-upload name="avatar" label="Upload Avatar" :image="optional($employee)->avatar
                                        ? $emplyee->avatar
                                        : \constPaths::DefaultAvatar" />
                                </div>
                                <div class="col-md-12">
                                    <x-form.input name="name"
                                        value="{{ optional($employee)->name ? $employee->name : old('name') }}"
                                        required="{{ true }}" label="Full Name" />
                                </div>

                                <div class="col-md-12">
                                    <x-form.input name="email"
                                        value="{{ optional($employee)->email ? $employee->email : old('email') }}"
                                        required="{{ true }}" label="Email Address" type="email" />
                                </div>

                                <div class="col-md-12">
                                    <x-form.input name="password" value="" label="Password" type="password"
                                        meta="Leave blank to use default password" />
                                </div>

                                <div class="col-md-12">
                                    <x-form.status-select name="status"
                                        selected="{{ optional($employee)->status ? $employee->status : old('status') }}"
                                        required="{{ true }}" label="Status" />
                                </div>
                                <div class="col-md-12">
                                    <x-form.select-company name="company_id" :selected="optional($employee)->companies ? $employee->companies : []"
                                        required="{{ true }}" label="Company" id="employee_company_select" />
                                </div>

                                <div class="col-md-12">
                                    <x-form.select-department name="department_id" :selected="optional($employee)->departments ? $employee->departments : null"
                                        required="{{ true }}" label="Department" />
                                </div>

                                <div class="col-md-12">
                                    <x-form.select-role name="role_id" :selected="optional($employee)->roles ? $employee->roles : null" required="{{ true }}"
                                        label="Role" />
                                </div>
                                <div class="col-md-12">
                                    <x-form.select-permisions name="permissions[]" :user_id="optional($employee)->id" id="permisission"
                                        label="Permissions" />
                                </div>
                                @if (!$employee)
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <x-form.input name="notify" type="checkbox" :checked="true"
                                            class="form-check-input" />
                                        <p>Notify Employee</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-actions">
                            <x-form.button label="{{ $employee ? 'Update' : 'Save' }}" />
                        </div>
                    </form>

                    @if ($employee)
                        <form method="POST" class="text-right" action="{{ route('password.email') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $employee->email }}" />
                            <x-form.button label="Send Password Reset Link" icon="ft-mail
                        " />
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        //
    </script>
@endpush
