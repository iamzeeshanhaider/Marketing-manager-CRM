@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="row">
        <x-bread-crumb current="Create Permission" :previous="[
            [
                'name' => 'Permissions',
                'route' => route('permisions.index'),
            ],
        ]">
            <x-action-button route="{{ route('permisions.index') }}" />
        </x-bread-crumb>

        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title" id="basic-layout-form">Create Permission</h4>
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
                        action="{{ optional($permission)->id ? route('permissions.update', $permission->id) : route('permissions.store') }}"
                        method="post" class="form" enctype="multipart/form-data">
                        @csrf
                        @isset($permission)
                            @method('PATCH')
                        @endisset
                        <div class="form-body">
                            <h4 class="form-section"><i class="ft-disc"></i> {{ $permission ? 'Update' : '' }} Permission Info
                            </h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <x-form.input name="name"
                                        value="{{ optional($permission)->name ? $permission->name : old('name') }}"
                                        required="{{ true }}" label="Permission Name" />
                                </div>

                            </div>
                        </div>

                        <div class="form-actions">
                            <x-form.button label="{{ $permission ? 'Update' : 'Save' }}" />
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
