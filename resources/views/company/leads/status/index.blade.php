@extends('layouts.main')

@section('styles')
@endsection
@section('content')
    <section class="">

        <x-bread-crumb current="Lead Status" :previous="getCompany(request('company'))
            ? [
                [
                    'name' => 'Company: ' . getCompany(request('company'))?->name,
                    'route' => route('companies.show', request('company')),
                ],
            ]
            : []">
            <x-action-button
                route="{{ url()->previous() === request()->url() ? route('lead_status.index', ['company' => request('company')]) : url()->previous() }}" />
        </x-bread-crumb>

        <x-table.datatables :headings="['id', 'name', 'color_code', 'leads', 'action']" title="Lead Status" subtitle="{{ getTableSubtitle('Lead Status') }}"
            route="{{ route('lead_status.create', ['company' => request('company')]) }}" tableId="leadStatusTable">



            @foreach ($leadStatuses as $index => $status)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $status->name }}</td>
                    <td><x-lead-status-badge :status="$status" /></td>
                    <td>
                        <a
                            href="{{ route('leads.index', ['status' => $status->id, 'company' => getCompany(request('company'))?->id]) }}">
                            {{ count($status->leads) }}
                        </a>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <x-table.action :index="$index" :items="[
                                [
                                    'name' => 'Edit',
                                    'icon' => 'ft-edit-2',
                                    'route' => route('lead_status.edit', [
                                        'status' => $status->id,
                                        'company' => getCompany(request('company'))?->id,
                                    ]),
                                ],
                            ]" />
                            <x-delete-confirmation action="{{ route('lead_status.destroy', $status->id) }}"
                                buttonClass="btn danger" :index="$status->id"
                                title="Are you sure you want to delete this Item?" />
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table.datatables>
    </section>
@endsection
