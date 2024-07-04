@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="content-header">
        <x-bread-crumb current="Leads Preview" :previous="array_merge(
            $company
                ? [
                    [
                        'name' => 'Company: ' . $company->name,
                        'route' => route('companies.show', $company->id),
                    ],
                ]
                : [],
            [
                [
                    'name' => 'Leads',
                    'route' => route('leads.index'),
                ],
            ],
        )">
            <x-action-button
                route="{{ url()->previous() === request()->url() ? route('leads.index', optional($company)->id) : url()->previous() }}" />
        </x-bread-crumb>

        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title">Lead Overview</h4>
                <a class="heading-elements-toggle"><i class="ft-ellipsis-h font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content content-detached">
                <div class="row p-3">
                    <div class="col-md-4">
                        <div class="email-app-menu card p-2">
                            <h6 class="text-muted text-bold-500">Profile</h6>
                            <div class="list-group border-top-0 list-group-messages">
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'overview']) }}"
                                    class="list-group-item {{ $view === 'overview' ? 'active' : 'list-group-item-action' }} border-0">
                                    <i class="ft-user mr-1"></i> Overview
                                </a>
                            </div>
                            <h6 class="text-muted text-bold-500 ">Conversations</h6>
                            <div class="list-group border-top-0 list-group-messages">
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'email']) }}"
                                    class="list-group-item {{ $view === 'emails' ? 'active' : 'list-group-item-action' }} border-0">
                                    <i class="fa fa-paper-plane-o mr-1"></i> Emails
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'sms']) }}"
                                    class="list-group-item {{ $view === 'sms' ? 'active' : 'list-group-item-action' }} border-0">
                                    <i class="ft-mail mr-1"></i> SMS
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'calls']) }}"
                                    class="list-group-item {{ $view === 'calls' ? 'active' : 'list-group-item-action' }} border-0">
                                    <i class="ft-phone mr-1"></i> Calls
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'comments']) }}"
                                    class="list-group-item {{ $view === 'comments' ? 'active' : 'list-group-item-action' }} border-0">
                                    <i class="ft-phone mr-1"></i> Comments
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['view' => 'invoice']) }}"
                                    class="list-group-item {{ $view === 'invoice' ? 'active' : 'list-group-item-action' }} border-0">
                                    <i class="ft-at-sign mr-1"></i>Invoice
                                </a>
                            </div>
                        </div>

                        @if ($view && $view !== 'overview')
                            <div class="card p-2">
                                <h6 class="text-muted text-bold-500">Lead</h6>
                                <div class="p-2">
                                    <p>Name: {{ $lead->full_name }}</p>
                                    {{-- TODO:: add email status - --}}
                                    <p>Email: {{ $lead->email }} <br> </p>
                                    <p>Phone: {{ $lead->tel }}</p>
                                    <div class="d-flex align-items-start">
                                        <p>Status:</p>
                                        <x-lead-status-badge :status="$lead->leadStatus" />
                                    </div>
                                    <p>Source: {{ $lead->source }}</p>
                                    <p>
                                        Agent: <a
                                            href="{{ $lead->agent ? route('employee.show', $lead->agent->id) : '#' }}">{{ $lead->agent->name ?? 'No Agent' }}</a>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <style>
                        .company-header {
                            background: linear-gradient(220deg, #292342, #6136aa);
                            background-color: transparent;
                        }

                        .modal-content {
                            border-radius: 10px;
                        }
                    </style>
                    <div class="col">
                        <div class="card email-app-details">
                            @switch($view)
                                @case('overview')
                                    <livewire:lead.profile :lead="$lead" :wire:key="'over-view'.$lead->id" />
                                @break

                                @case('sms')
                                    <livewire:lead.sms-view :lead="$lead" :wire:key="'sms'.$lead->id" />
                                @break

                                @case('calls')
                                    <livewire:lead.call-view :lead="$lead" :wire:key="'calls'.$lead->id" />
                                @break

                                @case('email')
                                    <livewire:lead.email-view :lead="$lead" :wire:key="'email'.$lead->id" />
                                @break

                                @case('comments')
                                    <livewire:lead.comment-view :lead="$lead" :wire:key="'comments'.$lead->id" />
                                @break

                                @case('invoice')
                                    <livewire:lead.invoice-view :lead="$lead" :wire:key="'comments'.$lead->id" />
                                @break

                                @default
                            @endswitch

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
