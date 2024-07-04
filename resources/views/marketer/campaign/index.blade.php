@extends('layouts.main')

@section('content')
    <section class="">
        <x-bread-crumb current="Campaigns" />

        <x-table.datatables :headings="['id', 'name', 'company', 'lead status', 'email status', 'type', 'action']" title="Campaign" tableId="campaignTable"
            subtitle="{{ getTableSubtitle('Campaigns') }}">

            @if (auth()->user()->hasPermissionTo('marketing_campaign') || auth()->user()->hasRole('Admin'))
                <x-slot name="addons">
                    <a href="{{ route('campaign.create') }}" class="btn btn-success btn-sm">
                        Add Campaign
                    </a>
                </x-slot>
            @endif

                      <x-slot name="filters">
                <x-filter.company-filter />
                @if (request('company'))
                    <a href="{{ request()->url() }}" class="text-danger"><i class="fa fa-close"></i> Clear Filter</a>
                @endif
            </x-slot>

            @foreach ($campaigns as $index => $campaign)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div class="d-flex">
                            <span class="pl-2">{{ $campaign->name }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex">
                            <span class="pl-2">
                                <img src="{{ $campaign->company->getLogo() }}" class="rounded-circle" width="25"
                                    alt="Avatar" />
                                <a href="{{ route('companies.show', $campaign->company->id) }}">
                                    {{ $campaign->company->name }}
                                </a>
                            </span>
                        </div>
                    </td>
                    <td>
                        <x-lead-status-badge :status="$campaign->leadStatus" />
                    </td>
                    <td>
                        <x-email-status-badge :status="$campaign->email_status" />
                    </td>
                    <td>
                        {{ $campaign->type }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <x-table.action :index="$index" :items="[
                                [
                                    'name' => 'Preview',
                                    'icon' => 'ft-eye',
                                    'route' => route('campaign.show', $campaign->id),
                                    'type' => 'link',
                                ],
                                [
                                    'name' => 'Edit',
                                    'icon' => 'ft-edit-2',
                                    'route' => route('campaign.edit', $campaign->id),
                                    'type' => 'link',
                                ],
                                [
                                    'name' => 'Activate',
                                    'icon' => 'fa fa-cog',
                                    'route' => route('activate.campign'),
                                    'type' => 'link',
                                ],

                            ]" />
                            <x-delete-confirmation action="{{ route('campaign.destroy', $campaign->id) }}"
                                buttonClass="btn danger" :index="$campaign->id"
                                title="Are you sure you want to delete this Item?" />
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table.datatables>
    </section>
@endsection
