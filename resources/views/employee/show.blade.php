@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="content-header">
        <x-bread-crumb :previous="[
            optional($employee)->companies->first()
                ? [
                    'name' => 'Company: ' . optional($employee)->companies->first()->name,
                    'route' => route('companies.show', optional($employee)->companies->first()->id),
                ]
                : null,
            [
                'name' => 'Employee',
                'route' => route('employee.index', optional($employee)->id),
            ],
        ]" :current="$employee->name">
            <x-action-button :route="optional($employee)->companies->first()
                ? route('employee.index', optional($employee)->companies->first()->id)
                : null" />
        </x-bread-crumb>


        <div class="content-body">
            <div id="user-profile">
                <div class="row">
                    <div class="col-12">
                        <div class="card profile-with-cover">
                            <div class="card-img-top img-fluid bg-cover height-300"
                                style="background: url({{ asset(\constPaths::Default) }}) 50%;"></div>
                            <div class="media profil-cover-details w-100 p-5 d-flex justify-content-between">
                                <div class="media-left pl-2 pt-2 d-flex align-items-center">
                                    <a href="#" class="profile-image">
                                        <img src="{{ $employee->getAvatar() }}" class="rounded-circle img-border height-100"
                                            alt="Card image">
                                    </a>
                                    <div class="pl-3">
                                        <h3 class="card-title pb-0 mb-0">{{ $employee->name }}</h3>
                                        <h5 class="card-title mt-0 pt-0"><a
                                                href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></h5>
                                    </div>
                                </div>
                                <div class="col-md-6 w-100">
                                    <ul class="list-group mb-3 card">
                                        <li class="list-group-item border-bottom">
                                            <h6 class="my-0">
                                                {{ optional($employee->last_login)->diffForHumans() ?? 'Never Logged In' }}
                                            </h6>
                                            <p class="text-muted mb-0">Last Login</p>
                                        </li>
                                        <li class="list-group-item border-bottom">
                                            <h6 class="my-0">
                                                {{ optional(optional($employee)->companies->first())->name }}</h6>
                                            <p class="text-muted mb-0">Company</p>
                                        </li>
                                        <li class="list-group-item border-bottom">
                                            <h6 class="my-0">
                                                {{ optional(optional($employee)->departments->first())->name }}</h6>
                                            <p class="text-muted mb-0">Department</p>
                                        </li>
                                        @if ($employee->hasRole('Agent'))
                                            <li class="list-group-item border-bottom">
                                                <h6 class="my-0">{{ count($employee->leads) }}</h6>
                                                <p class="text-muted mb-0">Assigned Leads</p>
                                            </li>
                                        @endif
                                        <li class="list-group-item border-bottom">
                                            <h6 class="my-0">
                                                {{ str_replace('_', ' ', $employee->roles->pluck('name')->implode(', ')) }}
                                            </h6>
                                            <p class="text-muted mb-0">Role</p>
                                        </li>
                                        <li class="list-group-item border-bottom">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <ul class="list-unstyled small">
                                                        @foreach ($employee->permissions->pluck('name')->slice(0, ceil($employee->permissions->count() / 2)) as $permission)
                                                            <li><span>&check;
                                                                    {{ ucwords(str_replace('_', ' ', $permission)) }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <ul class="list-unstyled small">
                                                        @foreach ($employee->permissions->pluck('name')->slice(ceil($employee->permissions->count() / 2)) as $permission)
                                                            <li><span>&check;
                                                                    {{ ucwords(str_replace('_', ' ', $permission)) }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            <p class="text-muted mb-0">Permissions</p>
                                        </li>
                                        <li class="list-group-item border-bottom">
                                            <h6 class="my-0">
                                                <x-status-badge :status="$employee->status" />
                                            </h6>
                                            <p class="text-muted mb-0">Status</p>
                                        </li>
                                    </ul>
                                    <div class="d-flex">
                                        <a class="nav-link text-muted"
                                            href="{{ route('employee.edit', $employee->id) }}"><i
                                                class="fa fa-user mr-2"></i>Edit User Info</a>
                                        @if ($employee->hasRole('Agent'))
                                            <a class="nav-link text-muted"
                                                href="{{ route('employee.show', $employee->id) . '#assigned_leads' }}"><i
                                                    class="fa fa-users mr-2"></i>Assigned Leads
                                                {{ count($employee->leads) }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Leads --}}
                        @if ($employee->hasRole('Agent'))
                            <div class="card" id="assigned_leads">
                                <x-table.datatables :headings="['id', 'details', 'company', 'address', 'status', 'action']" title="Leads"
                                    subtitle="Showing Leads assigned to employee/agent">

                                    {{-- @include('company.leads.leads-table', ['leads' => $employee->leads]) --}}

                                </x-table.datatables>
                            </div>
                        @endif
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
