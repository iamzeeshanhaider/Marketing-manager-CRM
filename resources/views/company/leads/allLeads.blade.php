@extends('layouts.main')


@section('styles')
    <style>
        /* Style for options on hover */
        .custom-select option:hover {
            background-color: linear-gradient(220deg, #292342, #6136aa)
                /* Primary color */
                color: #fff;
            /* Text color */
        }

        .custom-select {
            border-radius: 10px;
        }

        .company-header {
            background: linear-gradient(220deg, #292342, #6136aa);
            background-color: transparent;
        }

        .modal-content {
            border-radius: 10px;
        }
    </style>
@endsection


@section('content')
    <x-slot name="filters">
        <x-filter.company-filter />
        @if (request('company'))
            <a href="{{ request()->url() }}" class="text-danger"><i class="fa fa-close"></i> Clear Filter</a>
        @endif

    </x-slot>
    <section class="" style="overflow-x: auto">
        <x-bread-crumb current="Leads" :previous="$company
            ? [['name' => 'Company: ' . $company->name, 'route' => route('companies.show', $company->id)]]
            : []">
            <x-action-button
                route="{{ url()->previous() === request()->url() ? route('leads.index', optional($company)->id) : url()->previous() }}" />
        </x-bread-crumb>

        <x-table.all-leads-datatable :headings="['', 'id', 'company', 'details', 'lead_status', 'action']" title="Leads" :subtitle="(optional($company)->name ? 'Showing Leads for ' . $company->name . '<br>' : '') .
            (optional($status)->name ? 'Showing Leads with ' . $status->name . 'Status' : '')">

            <x-slot name="addons">
                <input type="submit" class="btn btn-primary" value="Check All" name="submit" id="select-all-button">
                @if (auth()->user()->hasPermissionTo('assign_leads') || auth()->user()->hasPermissionTo('all_permissions'))
                    <button type="submit" class="btn btn-primary" id="lead_agent_submit">
                        <i class="fa fa-check-square-o"></i> Assign Leads
                    </button>
                @endif
                @if (auth()->user()->hasPermissionTo('manage_lead_status') || auth()->user()->hasPermissionTo('all_permissions'))
                    <x-action-button
                        route="{{ route('lead_status.index', optional($company)->id ? ['company' => $company->id] : []) }}"
                        label="Manage Status" icon="ft-folder" btn_class="btn btn-primary" />
                @endif
                @if (auth()->user()->hasPermissionTo('add_leads') || auth()->user()->hasPermissionTo('all_permissions'))
                    <a href="{{ route('leads.create', optional($company)->id ? ['company' => $company->id] : '') }}"
                        class="btn btn-success btn-sm">
                        Add Leads
                    </a>
                @endif
                @if (auth()->user()->hasPermissionTo('upload_leads') || auth()->user()->hasPermissionTo('all_permissions'))
                    <x-action-button
                        route="{{ route('upload-leads', ['action' => 'upload'], optional($company)->id ? ['company' => $company->id] : '') }}"
                        label="Upload" icon="fa fa-upload" btn_class="btn btn-warning" />
                @endif
            </x-slot>

            </x-table.lead-datatables>
    </section>
    <div class="modal fade" id="companyAgentPopup" tabindex="-1" role="dialog" aria-labelledby="companyAgentPopupLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-white company-header">
                    <h5 class="modal-title" id="companyAgentPopupLabel">Select Agent</h5>
                </div>
                <div class="modal-body">
                    <!-- Add a dropdown/select to choose the company -->
                    <select name="selecteAgent" id="selecteAgent" class="form-control custom-select">
                        <option value="">All</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" id="selectAgentBtn" class="btn btn-primary">Apply</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#lead_agent_submit').click(function() {

                var agent_id = $('#lead_agent_select').val();
                var lead_ids = [];
                $('.lead_checkbox:checked').each(function() {
                    lead_ids.push($(this).val());
                });
                if (lead_ids.length > 0) {
                    // var company_id = getParameterByName('company');
                    var company_id = "{{ \App\Models\Company::first()->id }}";

                    $.ajax({
                        url: "{{ route('company.agents') }}",
                        method: "POST",
                        data: {
                            company_id: company_id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.success) {
                                var selectBox = document.getElementById('selecteAgent');
                                selectBox.innerHTML = '';
                                for (var userId in data.users) {
                                    if (data.users.hasOwnProperty(userId)) {
                                        var option = document.createElement('option');
                                        option.value = userId;
                                        option.textContent = data.users[userId];
                                        selectBox.appendChild(option);
                                    }
                                }
                                $('#companyAgentPopup').modal('show');
                            } else {
                                toastr.error(data.message);
                            }
                        }
                    });


                } else {
                    toastr.error('Please select at least one lead');
                }
            });
            $('#selectAgentBtn').click(function() {
                var agent_id = $('#selecteAgent').val();
                var lead_ids = [];
                $('.lead_checkbox:checked').each(function() {
                    lead_ids.push($(this).val());
                });
                $.ajax({
                    url: "{{ route('leads.assign') }}",
                    method: "POST",
                    data: {
                        agent_id: agent_id,
                        lead_ids: lead_ids,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#companyAgentPopup').modal('hide');
                        if (data.success) {
                            toastr.success(data.message);
                            loadDatatable();
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            });

        });
    </script>


    <!-- Add this at the end of your HTML body -->
@endpush
