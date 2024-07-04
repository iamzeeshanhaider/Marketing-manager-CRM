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
                <h4 class="card-title" id="basic-layout-form">Assign Permission</h4>
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
                        action="{{ route('permissions.remove') }}"
                        method="post" class="form" enctype="multipart/form-data">
                        @csrf

                        <div class="form-body">
                            <h4 class="form-section"><i class="ft-disc"></i>Assign  Permission
                            </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.select name="user" id="lead_user_select"
                                        :items="$users"
                                        required="{{ true }}" label="Users" />
                                </div>
                                <div class="col-md-6">

                                    <x-form.remove-permision multiple name="permissions[]"
                                        id="permisission"
                                        label="Permissions" />
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <x-form.button label="{{  'Save' }}" />
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
