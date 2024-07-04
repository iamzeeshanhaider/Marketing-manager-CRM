@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="row">
        <x-bread-crumb current="Create Item" :previous="[
            [
                'name' => 'Items',
                'route' => route('items.index'),
            ],
        ]">
            <x-action-button route="{{ route('items.index') }}" />
        </x-bread-crumb>

        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title" id="basic-layout-form">Create Item</h4>
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
                        action="{{ optional($item)->id ? route('items.update', $item->id) : route('items.store') }}"
                        method="post" class="form" enctype="multipart/form-data">
                        @csrf
                        @isset($item)
                            @method('PATCH')
                        @endisset
                        <div class="form-body">
                            <h4 class="form-section"><i class="ft-disc"></i> {{ $item ? 'Update' : '' }} Item Info
                            </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.input name="name"
                                        value="{{ optional($item)->name ? $item->name : old('name') }}"
                                        required="{{ true }}" label="Item Name" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input name="price"
                                        value="{{ optional($item)->price ? $item->price : old('price') }}"
                                        required="{{ true }}" label="Item Price" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.textarea name="description"
                                        value="{{ optional($item)->description ? $item->description : old('description') }}"
                                        required="{{ true }}" label="Item Description" />
                                </div>

                            </div>
                        </div>

                        <div class="form-actions">
                            <x-form.button label="{{ $item ? 'Update' : 'Save' }}" />
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
