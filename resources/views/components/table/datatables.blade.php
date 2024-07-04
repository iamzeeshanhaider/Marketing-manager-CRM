@props([
    'headings' => ['id', 'name', 'action'],
    'title' => 'Company', // always pass title as singluar
    'subtitle' => null, // this parameter is optional
    'route' => '',
    'addon' => '',
    'tableId' => 'company_table',
])
@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatable-basic.min.js') }}" type="text/javascript">
    </script>
@endsection
<div class="row">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <div>
                    <h4 class="card-title border-0 bg-white">All {{ ucwords(\Str::plural($title)) }}</h4>
                    @if ($subtitle)
                        <p class="small">{!! $subtitle ?? '' !!}</p>
                    @endif
                </div>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        @isset($addons)
                            {{ $addons }}
                        @endisset

                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content">
                @isset($filters)
                    {{ $filters }}
                @endisset
                <div class="card-body card-dashboard">
                    <table class="table table-striped table-bordered zero-configuration"
                        id="{{ strtolower($tableId) }}">
                        <thead>
                            <tr>
                                @foreach ($headings as $heading)
                                    <th>{{ strtoupper(str_replace('_', ' ', $heading)) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            {{ $slot }}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        table_id = '#' + @json($tableId).toLowerCase();
        if (table_id) {
            $(table_id).dataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ]
            });
        }
    </script>
@endpush
