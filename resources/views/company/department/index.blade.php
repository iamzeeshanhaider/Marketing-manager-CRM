@extends('layouts.main')

@section('content')
    <section class="">
        <x-bread-crumb current="Departments" :previous="[
            [
                'name' => 'Companies',
                'route' => route('companies.index'),
            ],
        ]">
            <x-action-button route="{{ route('companies.index') }}" />
        </x-bread-crumb>
        @php
            $companyLink = route('companies.show', $company->id);
        @endphp

        <x-table.datatables :headings="['id', 'name', 'status', 'employees', 'action']" title="Department"
            subtitle="Showing Department for <a href='{{ $companyLink }}'>{{ $company->name }}</a> Company">

            @if (auth()->user()->hasPermissionTo('add_departments') || auth()->user()->hasPermissionTo('all_permissions'))
                <x-slot name="addons">
                    <a href="{{ route('department.create', optional($company)->id ? ['company' => $company->id] : '') }}"
                        class="btn btn-success btn-sm">
                        Add Department
                    </a>
                </x-slot>
            @endif
            @foreach ($departments as $index => $department)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $department->name }}
                    </td>
                    <td>
                        <x-status-badge :status="$department->status" />
                    </td>
                    <td class="small">
                        <a href="{{ route('employee.index', ['department' => $department->id]) }}">Employees
                            ({{ $department->employees_count }})
                        </a> <br>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <x-table.action :index="$index" :items="[
                                [
                                    'name' => 'Preview',
                                    'icon' => 'ft-eye',
                                    'route' => route('department.show', [
                                        'company' => $department->company->id,
                                        'department' => $department->id,
                                    ]),
                                ],
                                [
                                    'name' => 'Edit',
                                    'icon' => 'ft-edit-2',
                                    'route' => route('department.edit', [
                                        'company' => $department->company->id,
                                        'department' => $department->id,
                                    ]),
                                ],
                            ]" />
                            <x-delete-confirmation
                                action="{{ route('department.destroy', ['company' => $department->company->id, 'department' => $department->id]) }}"
                                buttonClass="btn danger" :index="$department->id"
                                title="Are you sure you want to delete this Item?" />
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table.datatables>

    </section>
@endsection
