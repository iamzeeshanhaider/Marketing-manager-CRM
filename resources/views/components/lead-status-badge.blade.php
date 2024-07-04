@props([
    'status' => null,
])
<div class="badge"
    style="background-color: {{ $status['color_code'] }}; color: {{ calculateContrastColor($status->color_code) }}">
    {{ $status->name }}
</div>
