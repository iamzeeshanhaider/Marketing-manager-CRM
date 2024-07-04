@props([
    'type' => 'submit',
    'label' => 'Save',
    'icon' => 'fa fa-check-square-o',
    'class' => 'btn btn-primary',
    'isWire' => false,
    'wireClick' => '',
])
<button type="{{ $type }}" class="{{ $class }}"
    @if ($isWire) wire:click="{{ $wireClick }}" @endif>
    <i class="{{ $icon }}"></i> {{ $label }}
</button>
