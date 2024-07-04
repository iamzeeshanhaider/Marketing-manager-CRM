@props([
    'status' => null,
])
<div>
    @switch($status)
        @case(\App\Enums\GeneralStatus::Active())
            <div class="badge badge-success">
                {{ $status }}
            </div>
        @break

        @case(\App\Enums\GeneralStatus::InActive())
            <div class="badge badge-secondary">
                {{ $status }}
            </div>
        @break
    @endswitch
</div>
