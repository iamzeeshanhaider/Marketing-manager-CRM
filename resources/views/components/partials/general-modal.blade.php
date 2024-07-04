@props([
    'title' => $variables['title'] ?? 'Create',
    'type' => $variables['type'] ?? 'Modal',
    'file' => $variables['file'] ? '' . $variables['file'] : null,
    'size' => $variables['size'] ?? 'lg',
    'padding' => $variables['padding'] ?? '4',
])

<div wire:ignore class="modal fade" id="generalModal" tabindex="-1" role="dialog" aria-labelledby="generalModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" >
    {{-- data-controls-modal="generalModal" --}}
    <div class="modal-dialog modal-{{ $size }} modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <div class="modal-header border-botton">
                <h5 class="modal-title text-capitalize" id="text">
                    {{ $title }} {{ ucwords(str_replace('_', ' ', $type)) }}
                </h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
            </div>
            <div class="modal-body p-{{ $padding }}" wire:ignore>
                @if ($file && $type)
                    @if (in_array($type, [
                            'logs',
                            'users'
                        ]))
                        @include($file)
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
