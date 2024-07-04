@props([
    'current' => '',
    'previous' => null,
])

<div class="content-header-left col-12 mb-2 breadcrumb-new border-bottom mb-3">
    <div class="d-flex justify-content-between">
        <div>
            <h5 class="content-header-title mb-0 d-inline-block">{{ $current }}</h5>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb bg-transparent">
                        <li class="breadcrumb-item"><a class="" href="{{ route('dashboard') }}">Dashboard</a></li>
                        @if ($previous)
                            @foreach ($previous as $item)
                                @isset($item)
                                    <li class="breadcrumb-item"><a
                                            href="{{ $item['route'] ?? '' }}">{{ str_limit($item['name'] ?? '', 30) }}</a>
                                    </li>
                                @endisset
                            @endforeach
                        @endif
                        <li class="breadcrumb-item active bg-transparent">{{ str_limit($current, 30) }}</li>
                    </ol>
                </div>
            </div>
        </div>
        <div>
            {{ $slot }}
        </div>
    </div>
</div>
