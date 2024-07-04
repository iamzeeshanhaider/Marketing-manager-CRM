@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="row">
        <x-bread-crumb current="Create Company" :previous="[
            [
                'name' => 'Companies',
                'route' => route('companies.index'),
            ],
        ]">
            <x-action-button route="{{ route('companies.index') }}" />
        </x-bread-crumb>

        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title" id="basic-layout-form">Create Company</h4>
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
                        action="{{ optional($company)->id ? route('companies.update', $company->id) : route('companies.store') }}"
                        method="post" class="form" enctype="multipart/form-data">
                        @csrf
                        @isset($company)
                            @method('PATCH')
                        @endisset
                        <div class="form-body">
                            <h4 class="form-section"><i class="ft-disc"></i> {{ $company ? 'Update' : '' }} Company Info
                            </h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <x-form.image-upload name="logo" :image="optional($company)->logo ? $company->logo : null" />
                                </div>
                                <div class="col-md-12">
                                    <x-form.input name="name"
                                        value="{{ optional($company)->name ? $company->name : old('name') }}"
                                        required="{{ true }}" label="Company Name" />
                                </div>

                                <div class="col-md-12">
                                    <x-form.input name="email"
                                        value="{{ optional($company)->email ? $company->email : old('email') }}"
                                        required="{{ true }}" label="Company Email" type="email" />
                                </div>

                                <div class="col-md-12">
                                    <x-form.input name="allowed_users"
                                        value="{{ optional($company)->allowed_users ? $company->allowed_users : old('allowed_users') }}"
                                        required="{{ true }}" label="Allowed Users" type="number" />
                                </div>

                                <div class="col-md-12">
                                    <x-form.status-select name="status"
                                        selected="{{ optional($company)->status ? $company->status : old('status') }}"
                                        required="{{ true }}" label="Company Status" />
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <x-form.button label="{{ $company ? 'Update' : 'Save' }}" />
                        </div>
                    </form>
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
