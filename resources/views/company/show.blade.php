@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="content-header">
        <x-bread-crumb :previous="[
            [
                'name' => 'Companies',
                'route' => route('companies.index'),
            ],
        ]" :current="$company->name">
            <x-action-button :route="route('companies.index')" />
        </x-bread-crumb>
        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title">Company Overview</h4>
                <a class="heading-elements-toggle"><i class="ft-ellipsis-h font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content content-detached">
                <div class="card-body overflow-hidden row">
                    <div class="col-md-9 col-sm-12 border-right-grey border-right-lighten-2">

                        <div class="card profile-card-with-cover">
                            <div class="card-content">
                                <div class="card-img-top img-fluid bg-cover height-200"
                                    style="background: url({{ $company->getLogo() }}) 0 30%;"></div>
                                <div class="card-profile-image text-center">
                                    <img src="{{ $company->getLogo() }}" width="100"
                                        class="rounded-circle img-border box-shadow-1" alt="Card image">
                                </div>
                                <div class="profile-card-with-cover-content p-4">
                                    <div class="profile-details mt-2">
                                        <h4 class="card-title"><b>Company Name:</b> {{ $company->name }}</h4>
                                        <h4 class="card-title"><b>Company Email:</b> {{ $company->email }}</h4>
                                        <x-action-button btn_class="btn btn-social btn-min-width py-2 btn-facebook"
                                            icon="fa fa-pencil" label="Edit" :route="route('companies.edit', $company->id)" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="list-group">
                            <a href="{{ route('employee.index', ['company' => $company->id]) }}"
                                class="list-group-item list-group-item-action">
                                <h5 class="list-group-item-heading badge badge-pill badge-info">
                                    {{ count($company->employees) }}</h5>
                                <p class="list-group-item-text">Employees</p>
                            </a>
                            <a href="{{ route('department.index', ['company' => $company->id]) }}"
                                class="list-group-item list-group-item-action">
                                <h5 class="list-group-item-heading badge badge-pill badge-info">
                                    {{ count($company->departments) }}</h5>
                                <p class="list-group-item-text">Departments</p>
                            </a>
                            <a href="{{ route('lead_status.index', ['company' => $company->id]) }}"
                                class="list-group-item list-group-item-action">
                                <h5 class="list-group-item-heading badge badge-pill badge-info">
                                    {{ count($company->leadStatus) }}</h5>
                                <p class="list-group-item-text">Leads Status</p>
                            </a>
                            <a href="{{ route('leads.index', ['company' => $company->id]) }}"
                                class="list-group-item list-group-item-action">
                                <h5 class="list-group-item-heading badge badge-pill badge-info">
                                    {{ count($company->leads) }}</h5>
                                <p class="list-group-item-text">Leads</p>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <x-status-badge :status="$company->status" />
                                <p class="list-group-item-text">Status</p>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <h5 class="list-group-item-heading badge badge-pill badge-warning">
                                    {{ $company->allowed_users }}</h5>
                                <p class="list-group-item-text">Allowed Users</p>
                            </a>
                        </div>
                    </div>
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
