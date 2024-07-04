@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="row">
        <x-bread-crumb current="Test SMS" :previous="[
            [
                'name' => 'Test SMS',
                'route' => route('test.sms'),
            ],
        ]">
            <x-action-button route="{{ route('test.sms') }}" />
        </x-bread-crumb>

        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title" id="basic-layout-form">Test SMS</h4>
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
                    <form action="{{ route('test.sms') }}" method="get" class="form">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                {{-- Take phone number and sms text only --}}
                                <div class="col-md-12">
                                    <x-form.input name="phone_number" label="Phone Number" required="true" />
                                </div>
                                <div class="col-md-12">
                                    <x-form.textarea name="sms_text" label="SMS Text" required="true" />
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <x-form.button label="Send SMS" />
                        </div>
                    </form>

                    @if($response)
                        <div class="row">
                            <div class="col-md-12">
                                <pre>
                                    @php
                                        var_dump($response);
                                    @endphp
                                </pre>
                            </div>
                        </div>
                    @endif
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
