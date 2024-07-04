@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="row">
        <x-bread-crumb current="Create Email" :previous="[
            [
                'name' => 'Emails',
                'route' => route('email.index'),
            ],
        ]">
            <x-action-button route="{{ route('email.index') }}" />
        </x-bread-crumb>

        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title" id="basic-layout-form">Create Email</h4>
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
                    <form action="{{ optional($email)->id ? route('email.update', $email->id) : route('email.store') }}"
                        method="post" class="form" enctype="multipart/form-data">
                        @csrf
                        @isset($item)
                            @method('PATCH')
                        @endisset
                        <div class="form-body">
                            <h4 class="form-section"><i class="ft-disc"></i> {{ $email ? 'Update' : '' }} Email Info
                            </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.input name="title"
                                        value="{{ optional($email)->title ? $email->title : old('title') }}"
                                        required="{{ true }}" label="Title" />
                                </div>
                                <div class="col-md-6">
                                    <label for="meeting_type">Meeting Type</label>
                                    <select name="type" onchange="setCredsInput()" id="meeting_type"
                                        class="form-control custom-select">
                                        @foreach (\App\Enums\MeetingType::toSelectArray() as $key => $value)
                                            <option value="{{ $key }}"
                                                @if ($key === \App\Enums\MeetingType::Microsoft) disabled @elseif($key === optional($email)->type ? $email->type : old('type')) selected @endif>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">

                                    <x-form.textarea name="body"
                                        value="{{ optional($email)->body ? $email->body : old('body') }}"
                                        required="{{ true }}" class="editor" label="Email Body" />
                                </div>

                            </div>
                        </div>

                        <div class="form-actions">
                            <x-form.button label="{{ $email ? 'Update' : 'Save' }}" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
@endpush
