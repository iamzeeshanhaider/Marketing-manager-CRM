@extends('layouts.main')

@section('content')
    <div class="content-body">
        <section id="form-control-repeater">
            <form action="{{ route('importExcel') }}" method="post" enctype="multipart/form-data">
                @csrf <!-- {{ csrf_field() }} -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title" id="file-repeater">Upload Latest leads .CSV file</h4>
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <x-form.select-company name="company_id" label="Company" id="lead_company_select" />
                                    </div>
                                    <div class="col-md-12">
                                        <x-form.select-lead-status name="status" id="lead_status_select"
                                            label="Lead Status" />
                                    </div>
                                    <div class="form-group col-md-12 mb-2">
                                        <label>Upload file <a href="{{ $sampleFile }}">Download Sample File</a></label>
                                        <input type="file" class="form-control" name="csv_file" />
                                    </div>
                                    @if (session('alert'))
                                        <div class="alert alert-success">
                                            {{ session('alert') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-success white btn-sm" id="submit"><i
                                                    class="la la-paper-plane-o"></i> Upload </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
