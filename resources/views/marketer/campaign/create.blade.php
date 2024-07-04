@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="row">
        <x-bread-crumb current="Create Campaign" :previous="[
            [
                'name' => 'Campaigns',
                'route' => route('campaign.index'),
            ],
        ]">
            <x-action-button route="{{ route('campaign.index') }}" />
        </x-bread-crumb>

        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title" id="basic-layout-form">Create Campaign</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form
                        action="{{ optional($campaign)->id ? route('campaign.update', $campaign->id) : route('campaign.store') }}"
                        method="post" class="form" enctype="multipart/form-data">
                        @csrf
                        @isset($campaign)
                            @method('PATCH')
                        @endisset
                        <div class="form-body">
                            <h4 class="form-section"><i class="ft-disc"></i> {{ $campaign ? 'Update' : '' }} Campaign Info
                            </h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <x-form.input name="name"
                                        value="{{ optional($campaign)->name ? $campaign->name : old('name') }}"
                                        required="{{ true }}" label="Campaign Name" />
                                </div>
                                <div class="col-md-12">
                                    <x-form.select-company name="company_id" :selected="optional($campaign)->company
                                        ? [optional($campaign)->company]
                                        : old('company_id')" label="Company"
                                        id="lead_company_select" />
                                    {{-- <x-form.select-campaign name="company_id" label="Company"
                                        :options="$companies"
                                        selected="{{ optional($campaign)->company_id ? $campaign->company_id : old('company_id') }}"
                                        required="{{ true }}" /> --}}
                                </div>
                                <div class="col-md-12">
                                    <x-form.select-lead-status name="lead_status_id" id="lead_status_select"
                                        :selected="optional($campaign)->leadStatus ?? null" label="Lead Status" :required="true" />
                                    {{-- <x-form.select-campaign name="lead_status_id" label="Lead Status"
                                        :options="$leadStatuses"
                                        selected="{{ optional($campaign)->lead_status_id ? $campaign->lead_status_id : old('lead_status_id') }}"
                                        required="{{ true }}" /> --}}
                                </div>
                                <div class="col-md-12">
                                    <label for="country">Country</label>
                                    <x-form.select-countries :selected="optional($campaign)->country"></x-form.select-countries>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <label for="lead_type">Lead Type</label>
                                    <select name="lead_type" id="lead_type" class="form-control" required>
                                        @foreach (\App\Enums\LeadType::getInstances() as $source)
                                            <option {{ optional($campaign)->lead_type == $source ? 'selected' : '' }}
                                                value="{{ $source }}">
                                                {{ $source }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <x-form.select-email-status name="email_status" :selected="optional($campaign)->email_status
                                        ? $campaign->email_status
                                        : old('email_status')" :required="true" />
                                    {{-- <x-form.select-campaign name="email_status" label="Email Status"
                                        :options="$emailStatuses"
                                        selected="{{ optional($campaign)->email_status ? $campaign->email_status : old('email_status') }}"
                                        required="{{ true }}" /> --}}
                                </div>
                                <div class="col-md-12">
                                    <x-form.select-campaign-type name="type" :selected="optional($campaign)->type ? $campaign->type : old('type')" :required="true" />
                                    {{-- <x-form.select-campaign name="type" label="Type" :options="$types"
                                        selected="{{ optional($campaign)->type ? $campaign->type : old('type') }}"
                                        required="{{ true }}" /> --}}
                                </div>
                                <div class="col-md-12">
                                    <x-form.textarea name="email_content" label="Email Content"
                                        value="{{ optional($campaign)->email_content ? $campaign->email_content : old('email_content') }}"
                                        required="{{ true }}" />
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <x-form.button label="{{ $campaign ? 'Update' : 'Save' }}" />
                        </div>
                    </form>
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
