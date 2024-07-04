@extends('layouts.main')

<style>

</style>
@section('content')
    <section class="content-header">
        <x-bread-crumb current="Leads Upload" :previous="array_merge(
            $company
                ? [
                    [
                        'name' => 'Company: ' . $company->name,
                        'route' => route('companies.show', $company->id),
                    ],
                ]
                : [],
            [
                [
                    'name' => 'Leads',
                    'route' => route('leads.index'),
                ],
            ],
        )">
            <x-action-button
                route="{{ url()->previous() === request()->url() ? route('leads.index', optional($company)->id) : url()->previous() }}" />
        </x-bread-crumb>

        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <h4 class="card-title">Lead Upload</h4>
                <a class="heading-elements-toggle"><i class="ft-ellipsis-h font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content content-detached">
                {{--
                    TODO:
                    - display csv template
                    - display upload area and handle upload
                --}}
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        //
    </script>
@endpush
