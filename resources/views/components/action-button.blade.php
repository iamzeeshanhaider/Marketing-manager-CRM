@props([
    'route' => '',
    'label' => 'Go Back',
    'icon' => 'fa fa-arrow-left',
    'icon_class' => '',
    'btn_class' => 'btn btn-primary btn-sm',
])
@if ($route)
    <a href="{{ $route }}" class="{{ $btn_class }}">
        @if ($icon)
            <i class="{{ $icon }} {{ $icon_class }}"></i>
        @endif
        <span>{{ $label }}</span>
    </a>
@endif
