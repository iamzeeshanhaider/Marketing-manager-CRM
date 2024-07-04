@extends('layouts.main')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/modal/sweetalert.css') }}">

    <style>
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
    <section class="">
        <x-bread-crumb current="Companies" />

        <x-table.datatables :headings="['id', 'name', 'email', 'Calander Type', 'Action']" title="Company" tableId="companyTable">

            <tr>
                <td>1</td>
                <td>
                    <div class="d-flex">
                        <img src="{{ $company->getLogo() }}" class="rounded-circle" width="25" alt="Avatar" />
                        <span class="pl-2">{{ $company->name }}</span>
                    </div>
                </td>
                <td>{{ $company->email }}</td>

                <td>
                    <div class="badge  badge-warning">
                        {{ $company->calendar ? App\Enums\MeetingType::getDescription($company->calendar->calendar_type) : '' }}
                    </div>
                </td>
                <td>
                    <div onclick="updateStatus({{ $company->id }},'{{ $company->calendar?->client_id }}','{{ $company->calendar?->client_secret }}','{{ $company->calendar?->cc_email }}','{{ $company->calendar?->redirect }}')"
                        style="cursor: pointer" class="badge badge-primary">
                        Update
                    </div>
                </td>

            </tr>

        </x-table.datatables>


        <!-- The modal markup -->
        <div class="modal fade" id="meetingTypePopup" tabindex="-1" role="dialog" aria-labelledby="meetingTypePopupLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white company-header">
                        <h5 class="modal-title" id="meetingTypePopupLabel">Meeting Service</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <select name="meeting_type" onchange="setCredsInput()" id="meeting_type"
                                    class="form-control custom-select">
                                    @foreach ($meetingTypes as $key => $value)
                                        <option value="{{ $key }}"
                                            @if ($key === \App\Enums\MeetingType::Microsoft) disabled @elseif ($key === $company->calendar?->calendar_type) selected @endif>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <form id="meettype">
                                <div id="google" class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="starttime" class="form-label">Client ID:</label>
                                        <input id="client_id" name="client_id" type="text" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="starttime" class="form-label">Client Secret:</label>
                                        <input id="client_secret" name="client_secret" type="text" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="starttime" class="form-label">Redirect:</label>
                                        <input id="redirect" name="redirect" type="text" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3" id="api_key">
                                        <label for="api_key" class="form-label">Api Key</label>
                                        <input id="apiinput" name="api_key" type="text" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3" id="cc_email_con">
                                        <label for="cc_email" class="form-label">CC Email</label>
                                        <input id="cc_email" name="cc_email" type="text" class="form-control">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <input type="hidden" id="company_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="meetingtBtn" class="btn btn-primary">Apply</button>
                    </div>
                </div>
            </div>
        </div>


    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/enum.js') }}"></script>
    <script>
        var google = "{{ App\Enums\MeetingType::Google }}";
        var zoom = "{{ App\Enums\MeetingType::Zoom }}";
        var microsoft = "{{ App\Enums\MeetingType::Microsoft }}";
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        function updateStatus(company_id, client_id, client_secret, cc_email, redirect) {
            $('#company_id').val(company_id);
            $('#client_id').val(client_id);
            $('#client_secret').val(client_secret);
            $('#cc_email').val(cc_email);
            $('#redirect').val(redirect);
            $('#meetingTypePopup').modal('show');

        }
        setCredsInput();

        function setCredsInput() {
            var selectedMeetingType = $('#meeting_type').val();
            if (selectedMeetingType == google || selectedMeetingType == zoom) {
                $('#google').show();
                $('#api_key').hide();
            } else {
                $('#google').hide();
                $('#api_key').show();
            }
        }
        $(document).ready(function() {
            $("#meetingtBtn").click(function() {
                var selectedMeetingType = $('#meeting_type').val();
                var company_id = $('#company_id').val();
                var cc_email = $('#cc_email').val();
                var formData = new FormData();

                if (selectedMeetingType == google || selectedMeetingType == zoom) {
                    var client_id = $('#client_id').val();
                    var client_secret = $('#client_secret').val();
                    var redirect = $('#redirect').val();
                    if (client_id && client_secret) {
                        formData.append('client_secret', client_secret);
                        formData.append('client_id', client_id);
                        formData.append('redirect', redirect);
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: 'Ooops...',
                            text: 'Please All Valid Creds'
                        });
                        return;
                    }
                } else {
                    var api_key = $('#apiinput').val();
                    if (!api_key) {
                        swal.fire({
                            icon: 'error',
                            title: 'Ooops...',
                            text: 'Api Key should Not be empty'
                        });
                        return;
                    }
                    formData.append('api_key', api_key);
                }
                formData.append('_token', csrfToken);
                formData.append('company_id', company_id);
                formData.append('meeting_type', selectedMeetingType);
                formData.append('cc_email', cc_email);
                $.ajax({
                    url: '/updat/meeting/type',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#meetingTypePopup').modal('hide');
                            $('#meettype')[0].reset();
                            swal.fire({
                                icon: 'success',
                                title: 'Hurrah...',
                                text: 'Calander Configured Successfully'
                            });
                            window.location.reload();
                        } else {
                            swal.fire({
                                icon: 'error',
                                title: 'Ooops...',
                                text: response.message
                            });
                        }
                    },
                });

            });
        });
    </script>
@endpush
