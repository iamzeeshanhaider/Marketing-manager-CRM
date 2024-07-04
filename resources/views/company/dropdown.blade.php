@extends('layouts.main')
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/modal/sweetalert.css') }}">
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
    <!-- The modal markup -->
    <div class="modal fade" id="companyPopup" tabindex="-1" role="dialog" aria-labelledby="companyPopupLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header text-white company-header">
                    <h5 class="modal-title" id="companyPopupLabel">Select Company</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select name="selectedCompany" class="form-control custom-select">
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="selectCompanyBtn" class="btn btn-primary">Apply</button>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#companyPopup').modal('show');
            $('.modal .close').on('click', function() {
                $(this).closest('.modal').modal('hide');
            });
            $('#selectCompanyBtn').click(function() {
                var id = $('select[name="selectedCompany"]').val();
                let url = "{{ url('update/selected/company') }}/" + id;

                $.ajax({
                    url: url,
                    type: "POST",
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === "calander_error") {
                            swal.fire({
                                title: "<strong>You haven't selected the calander for company</strong>",
                                icon: "info",
                                html: ` <b>Select The Comapny Calander</b><a href="{{ route('company.calender') }}">Click Here</a>`,

                            });
                            return;
                        } else if (response.status === "success") {
                            window.location.href = "{{ route('dashboard') }}";
                            return;
                        }
                    },
                    error: function(response) {
                        console.log(response);
                        return;
                    }
                });
                return;
            });
        });
    </script>
@endpush
