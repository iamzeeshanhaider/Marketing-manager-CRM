@extends('layouts.main')

@section('content')
    <section class="">
        <x-bread-crumb current="Companies" />

        <x-table.datatables :headings="['id', 'name', 'email', 'status', 'addons', 'action']" title="Company" tableId="companyTable">

            @foreach ($companies as $index => $company)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex">
                            <img src="{{ $company->getLogo() }}" class="rounded-circle" width="25" alt="Avatar" />
                            <span class="pl-2">{{ $company->name }}</span>
                        </div>
                    </td>
                    <td>{{ $company->email }}</td>
                    <td>
                        <x-status-badge :status="$company->status" />
                    </td>
                    <td class="small">
                        <a href="{{ route('employee.index', ['company' => $company->id]) }}">Employees
                            ({{ $company->employees_count }})
                        </a> <br>
                        <a href="{{ route('department.index', ['company' => $company->id]) }}">Departments
                            ({{ $company->departments_count }})</a>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <x-table.action :index="$index" :items="[
                                [
                                    'name' => 'Preview',
                                    'icon' => 'ft-eye',
                                    'route' => route('companies.show', $company->id),
                                    'type' => 'link',
                                ],
                                [
                                    'name' => 'Edit',
                                    'icon' => 'ft-edit-2',
                                    'route' => route('companies.edit', $company->id),
                                    'type' => 'link',
                                ],
                            ]" />
                            <x-delete-confirmation action="{{ route('companies.destroy', $company->id) }}"
                                buttonClass="btn danger" :index="$company->id"
                                title="Are you sure you want to delete this Item?" />
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table.datatables>
    </section>
@endsection
