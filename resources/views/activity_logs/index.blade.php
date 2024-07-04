@extends('layouts.main')

@section('content')
    <section class="body" style="overflow-x: auto">

        <x-table.datatables :headings="['id', 'model_type', 'user_id', 'module_name', 'action', 'old_value' , 'new_value','action']" title="Activity Logs" id="logs">

            @include('activity_logs.logs-table', $logs)

        </x-table.datatables>

    </section>
@endsection

@section('footer')

   {{-- <script>
        $('#logs').dataTable({
            "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ]
        });
    </script>--}}
@endsection
