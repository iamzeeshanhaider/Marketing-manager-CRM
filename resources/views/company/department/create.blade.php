@extends('layouts.main')

<style>

</style>
@section('content')
    <section>
        <x-bread-crumb current="Create Department" :previous="[
            [
                'name' => 'Companies',
                'route' => route('companies.index'),
            ],
            [
                'name' => 'Departments',
                'route' => route('department.index', ['company' => $company->id]),
            ],
        ]">
            <x-action-button route="{{ route('companies.index') }}" />
        </x-bread-crumb>

        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title" id="basic-layout-form">Create Department Info for <a
                        href="{{ route('companies.index') }}">{{ $company->name }} </a> Company</h4>
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
                    @php
                        $routeName = optional($department)->id ? 'department.update' : 'department.store';
                        $routeParams = ['company' => $company->id];

                        if (optional($department)->id) {
                            $routeParams['department'] = $department->id;
                        }
                    @endphp

                    <form action="{{ route($routeName, $routeParams) }}" method="post" class="form"
                        enctype="multipart/form-data">
                        @csrf
                        @isset($department)
                            @method('PATCH')
                        @endisset
                        <div class="form-body">
                            <h4 class="form-section"><i class="ft-disc"></i> {{ $department ? 'Update' : '' }} Department Info</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <x-form.input name="name"
                                        value="{{ optional($department)->name ? $department->name : old('name') }}"
                                        required="{{ true }}" label="Department Name" />
                                </div>
                                <div class="col-md-12">
                                    <x-form.status-select name="status"
                                        selected="{{ optional($department)->status ? $department->status : old('status') }}"
                                        required="{{ true }}" label="Department Status" />
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <x-form.button label="{{ $department ? 'Update' : 'Save' }}" />
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
