@props([
    'icon' => 'la la-home',
    'title' => 'Dashboard',
    'route' => null,
    'options' => null,
])
<li class=" nav-item">
    <a href="{{ $route ?? '#' }}">
        <i class="{{ $icon }}"></i>
        <span class="menu-title" data-i18n="nav.dash.main">{{ $title }}</span>
    </a>
    @if ($options)
    <ul class="menu-content">
        @foreach ($options as $item)
        <li>
            <a class="menu-item" href="{{ $item['route'] }}" data-i18n="nav.dash.{{ $item['name'] }}">{{ $item['name'] }}</a>
        </li>
        @endforeach
    </ul>
    @endif
</li>
