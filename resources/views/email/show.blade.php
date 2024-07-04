@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="content-header">
        <x-bread-crumb :previous="[
            [
                'name' => 'Emails',
                'route' => route('email.index'),
            ],
        ]">
            <x-action-button :route="route('email.index')" />
        </x-bread-crumb>
        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title">Email Overview</h4>
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
                                <div class="profile-card-with-cover-content p-4">
                                    <div class="profile-details mt-2">
                                        <h4 class="card-title"><b>Email Title:</b> {{ $email->title }}</h4>
                                        <h4 class="card-title"><b>Email Content:</b></h4>
                                        <div class="mt-1">
                                            {!! $email->body !!}
                                        </div>
                                        <br />
                                        <x-action-button btn_class="btn btn-social btn-min-width py-2 btn-facebook"
                                            icon="fa fa-pencil" label="Edit" :route="route('email.edit', $email->id)" />
                                    </div>
                                </div>
                            </div>
                        </div>
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
