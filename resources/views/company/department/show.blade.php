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
            [
                'name' => $company->name,
                'route' => route('companies.show', $company->id),
            ],
        ]" :current="$department->name">
            <x-action-button :route="route('department.index', $company->id)" />
        </x-bread-crumb>
        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title">Department Overview</h4>
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
                                <div class="profile-card-with-cover-content p-4">
                                    <div class="profile-details mt-2">
                                        <h4 class="card-title"><b>Department Name:</b> {{ $department->name }}</h4>
                                        <p class="p-0 m-0"><small>Company Name:{{ $company->name }}</small></p>
                                        <x-action-button btn_class="mt-2 btn btn-social btn-min-width py-2 btn-facebook"
                                            icon="fa fa-pencil" label="Edit" :route="route('department.edit', [
                                                'company' => $company->id,
                                                'department' => $department->id,
                                            ])" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3 col-sm-12">
                        <div class="list-group">
                            <a href="{{ route('employee.index', ['department' => $department->id]) }}"
                                class="list-group-item list-group-item-action">
                                <h5 class="list-group-item-heading badge badge-pill badge-info">
                                    {{ count($department->employees) }}</h5>
                                <p class="list-group-item-text">Employees</p>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <x-status-badge :status="$department->status" />
                                <p class="list-group-item-text">Status</p>
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
