@extends('layouts.main')

@section('content')
    <section class="">
        <x-bread-crumb current="Employees" :previous="[
            [
                'name' => 'Companies',
                'route' => route('companies.index'),
            ],
        ]">
            <x-action-button route="{{ route('companies.index') }}" />
        </x-bread-crumb>

        <x-table.datatables :headings="['id', 'name', 'company', 'status', 'role', 'action']" title="Employee"
            previous_route="{{ isset($company) ? route('companies.index') : null }}"
            subtitle="{{ getTableSubtitle('Employee') }}">

            @if (auth()->user()->hasPermissionTo('add_employees') || auth()->user()->hasPermissionTo('all_permissions'))
                <x-slot name="addons">
                    <a href="{{ route('employee.create', ['company' => request('company')]) }}"
                        class="btn btn-success btn-sm">
                        Add Employee
                    </a>
                </x-slot>
            @endif
            <x-slot name="filters">
                <x-filter.company-filter />
                @if (request('company'))
                    <a href="{{ request()->url() }}" class="text-danger"><i class="fa fa-close"></i> Clear Filter</a>
                @endif
            </x-slot>

            @foreach ($employees as $index => $employee)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $employee->getAvatar() }}" class="rounded-circle" width="30" alt="Avatar" />
                            <div>
                                <span class="pl-2">{{ $employee->name }}</span> <br>
                                <small class="pl-2"><a
                                        href="mailto:{{ $employee->email }}">{{ $employee->email }}</a></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span>{{ optional($employee->companies->first())->name }}</span> <br>
                            <small>
                                @if ($employee->departments->isNotEmpty())
                                    Department: {{ $employee->departments->first()->name }}
                                @endif
                            </small>
                        </div>
                    </td>
                    <td>
                        <x-status-badge :status="$employee->status" />
                    </td>
                    <td>
                        <div>
                            <span>{{ $employee->getRoleNames()->first() }}</span> <br>
                            @if ($employee->hasRole('Agent'))
                                <small><a href="{{ route('employee.show', $employee->id) . '#assigned_leads' }}">Assigned
                                        Leads: {{ count($employee->leads) }}</a></small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <x-table.action :index="$index" :items="[
                                [
                                    'name' => 'Preview',
                                    'icon' => 'ft-eye',
                                    'route' => route('employee.show', $employee->id),
                                    'type' => 'link',
                                ],
                                [
                                    'name' => 'Edit',
                                    'icon' => 'ft-edit-2',
                                    'route' => route('employee.edit', $employee->id),
                                    'type' => 'link',
                                ],
                            ]" />
                            <x-delete-confirmation action="{{ route('employee.destroy', $employee->id) }}"
                                buttonClass="btn danger" :index="$employee->id"
                                title="Are you sure you want to delete this Item?" />
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table.datatables>
    </section>
@endsection
