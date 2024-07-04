<div class="px-3">
    <div x-data="{ show: @entangle('show').defer }" x-show="show" x-transition.duration.500ms @keydown.window.escape="show = false"
        x-init="setTimeout(() => {
            show = false;
            @this.call('closeAlert');
        }, 500);">
        <div class="alert alert-{{ $alertStatus }} alert-dismissible fade show align-items-center" role="alert">
            <span>{{ $alertMessage }}</span>
            <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"
                wire:click="closeAlert"></button>
        </div>
    </div>
</div>
