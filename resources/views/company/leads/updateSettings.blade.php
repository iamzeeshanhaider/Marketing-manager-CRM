@extends('layouts.main')
@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
    <style>
        .iti {
            width: 100%;
        }
    </style>
@endsection
@section('content')
    <section>
        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title" id="">

                </h4>
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
                    <form id="settingsForm" action="{{ route('settings.update') }}" method="post" class="form"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-body">
                            <h4 class="form-section text-center"><i class="ft-disc"></i>General Settings</h4>
                            <h4 class="form-section "><i class="ft-mail"></i>Mailgun Settings</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.input name="MAILGUN_DOMAIN"
                                        value="{{ optional($settings)->MAILGUN_DOMAIN ?? old('MAILGUN_DOMAIN') }}"
                                        label="Mailgun Domain" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="MAIL_FROM_ADDRESS"
                                        value="{{ optional($settings)->MAIL_FROM_ADDRESS ?? old('MAIL_FROM_ADDRESS') }}"
                                        label="Mail From Address" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input name="MAILGUN_SECRET" type="text"
                                        value="{{ optional($settings)->MAILGUN_SECRET ?? old('MAILGUN_SECRET') }}"
                                        label="Mailgun Secret" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="MAIL_MAILER"
                                        value="{{ optional($settings)->MAIL_MAILER ?? old('MAIL_MAILER') }}"
                                        label="Mail Mailer" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input name="MAIL_HOST" type="text"
                                        value="{{ optional($settings)->MAIL_HOST ?? old('MAIL_HOST') }}"
                                        label="Mail Host" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="MAIL_PORT"
                                        value="{{ optional($settings)->MAIL_PORT ?? old('MAIL_PORT') }}"
                                        label="Mail Port" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input name="MAIL_USERNAME" type="text"
                                        value="{{ optional($settings)->MAIL_USERNAME ?? old('MAIL_USERNAME') }}"
                                        label="Mail Username" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="MAIL_PASSWORD" type="text"
                                        value="{{ optional($settings)->MAIL_PASSWORD ?? old('MAIL_PASSWORD') }}"
                                        label="Mail Password" />
                                </div>
                            </div>
                            <h4 class="form-section "><i class="ft-mail"></i>SMS Settings</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.input name="TWILIO_ACCOUNT_SID"
                                        value="{{ optional($settings)->TWILIO_ACCOUNT_SID ?? old('TWILIO_ACCOUNT_SID') }}"
                                        label="Twilio Account Id" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input name="TWILIO_AUTH_TOKEN" type="text"
                                        value="{{ optional($settings)->TWILIO_AUTH_TOKEN ?? old('TWILIO_AUTH_TOKEN') }}"
                                        label="Twilio Auth Token" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="TWILIO_SMS_FROM"
                                        value="{{ optional($settings)->TWILIO_SMS_FROM ?? old('TWILIO_SMS_FROM') }}"
                                        label="Twilio Sms From" />
                                </div>
                                <h4 class="form-section "><i class="ft-phone"></i>Vonage Settings</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-form.input name="VONAGE_KEY"
                                            value="{{ optional($settings)->VONAGE_KEY ?? old('VONAGE_KEY') }}"
                                            label="Vonage Key" />
                                    </div>

                                    <div class="col-md-6">
                                        <x-form.input name="VONAGE_SECRET" type="text"
                                            value="{{ optional($settings)->VONAGE_SECRET ?? old('VONAGE_SECRET') }}"
                                            label="Vonage Secret" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.input name="VONAGE_NUMBER" type="text"
                                            value="{{ optional($settings)->VONAGE_NUMBER ?? old('VONAGE_NUMBER') }}"
                                            label="Vonage Number" />
                                    </div>

                                </div>
                            </div>
                            <h4 class="form-section "><i class="ft-mail"></i>SMS Settings</h4>
                            <div class="row">
                                <h4 class="form-section "><i class="ft-phone"></i>Leads Settings</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Upload Leads Sample</label>
                                        <input type="file" class="form-control" name="LEADS_SAMPLE" />
                                    </div>
                                </div>
                            </div>


                            <div class="form-actions">
                                <x-form.button label="Save" />
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>
    <script>
        document.getElementById('settingsForm').addEventListener('submit', function(event) {
            event.preventDefault();
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
        });
    </script>
@endpush
