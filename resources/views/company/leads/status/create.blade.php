@extends('layouts.main')

<style>

</style>
@section('content')
    <section>
        <x-bread-crumb current="Lead Status" :previous="$company
            ? [['name' => 'Company: ' . $company->name, 'route' => route('companies.show', $company->id)]]
            : null,
            [
                'name' => 'Lead Status',
                'route' => route('lead_status.index', optional($company)->id ? ['company' => $company->id] : []),
            ]
            ">
            <x-action-button
                route="{{ url()->previous() === request()->url() ? route('leads.index', optional($company)->id) : url()->previous() }}" />
        </x-bread-crumb>

        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title" id="">
                    {{ optional($status)->name ? 'Update' : 'Create' }} Lead
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
                        action="{{ route(optional($status)->id ? 'lead_status.update' : 'lead_status.store', optional($status)->id ? ['status' => $status->id] : []) }}"
                        method="post" class="form" enctype="multipart/form-data">
                        @csrf
                        @isset($status)
                            @method('PATCH')
                        @endisset
                        <div class="form-body">
                            <h4 class="form-section"><i class="ft-disc"></i> {{ $status ? 'Update' : '' }} Lead Status</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.input name="name" value="{{ optional($status)->name ?? old('name') }}"
                                        label="Status Name" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.select-lead-color-code name="color_code"
                                        selected="{{ optional($status)->color_code ?? old('color_code') }}"
                                        label="Color Code" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.select-company name="company_id" :selected="[optional($status)->company ?? ($company ?? old('company_id'))]" label="Company"
                                        id="lead_company_select" />
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <x-form.button label="{{ $status ? 'Update' : 'Save' }}" />
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
