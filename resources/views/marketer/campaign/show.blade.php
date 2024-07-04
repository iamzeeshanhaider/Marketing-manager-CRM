@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="content-header">
        <x-bread-crumb :previous="[
            [
                'name' => 'Campaigns',
                'route' => route('campaign.index'),
            ],
        ]" :current="$campaign->name">
            <x-action-button :route="route('campaign.index')" />
        </x-bread-crumb>
        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title">Campaign Overview</h4>
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
                    <div class="col-md-12 col-sm-12 border-right-grey border-right-lighten-2">
                        <div class="card profile-card-with-cover">
                            <div class="card-content">
                                <div class="card-img-top img-fluid bg-cover height-200"
                                    style="background: url({{ $campaign->company->getLogo() }}) 0 30%;"></div>
                                <div class="card-profile-image text-center">
                                    <img src="{{ $campaign->company->getLogo() }}" width="100"
                                        class="rounded-circle img-border box-shadow-1" alt="Card image">
                                </div>
                                <div class="profile-card-with-cover-content p-4">
                                    <div class="profile-details mt-2">
                                        <h4 class="card-title"><b>Campaign Name:</b> {{ $campaign->name }}</h4>
                                        <h4 class="card-title"><b>Company Name:</b>
                                            <a href="{{ route('companies.show', ['company' => $campaign->company_id]) }}">{{ $campaign->company->name }}</a>
                                        </h4>
                                        <h4 class="card-title">
                                            <b>Lead Status:</b>
                                             <x-lead-status-badge :status="$campaign->leadStatus" />
                                        </h4>
                                        <h4 class="card-title">
                                            <b>Email Status:</b>
                                            <x-email-status-badge :status="$campaign->email_status" />
                                        </h4>
                                        <h4 class="card-title">
                                            <b>Type:</b>
                                            {{ $campaign->type }}
                                        </h4>
                                            <h4 class="card-title"><b>Email Content:</b></h4>
                                        <div class="mt-1">
                                            {!! $campaign->email_content !!}
                                        </div>
                                        <br />
                                        <x-action-button btn_class="btn btn-social btn-min-width py-2 btn-facebook"
                                            icon="fa fa-pencil" label="Edit" :route="route('campaign.edit', $campaign->id)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <x-table.datatables :headings="['id', 'details', 'company', 'status', 'action']" title="Leads"
                        :subtitle="(optional($campaign->company)->name ? 'Showing Leads for ' . $campaign->company->name : '')"
                    >
                        @foreach ($campaign->leads as $lead)
                            <tr>
                                <td>{{ $lead->id }}</td>
                                <td>
                                    <strong>Email:</strong> {{ $lead->email ?? 'Not Assigned' }}<br>
                                    <strong>Phone:</strong> {{ $lead->phone ?? 'Not Assigned' }}<br>
                                </td>
                                <td>{{  $lead->company->name }}</td>
                                <td>
                                    <strong>Lead Status:</strong> <x-lead-status-badge :status="$lead->leadStatus" /> <br>
                                    <strong>Email Status:</strong> <x-email-status-badge :status="$lead->isEmailSent() ? 'Sent' : 'Pending'" /><br>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('leads.show', ['company' => $lead->company->id, 'lead' => $lead->id]) }}">View</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-table.datatables>

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
