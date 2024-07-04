@props([
    'index' => 1,
    'items' => [],
])
<div class="dropdown">
    <button id="tableActionDropdown-{{ $index }}" type="button" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false" class="btn btn-info dropdown-toggle"><i class="fa fa-cog"></i>
    </button>
    <span aria-labelledby="tableActionDropdown{{ $index }}" class="dropdown-menu mt-1 dropdown-menu-right">
        @foreach ($items as $item)
            <a href="{{ $item['route'] ?? null }}" class="dropdown-item">
                <i class="{{ $item['icon'] }}"></i>
                {{ $item['name'] }}
            </a>
        @endforeach
    </span>
</div>
