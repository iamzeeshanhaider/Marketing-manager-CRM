@section('css')
  {{--  --}}
@endsection

<div class="animated fadeIn">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">@lang('Activity Logs List')</strong>
                </div>
                <div class="card-body">
                    <livewire:activitylog.activity-logs-table userID="all" />
                </div>
            </div>
        </div>
    </div>
</div>

@section('script')
    {{--  --}}
@endsection
