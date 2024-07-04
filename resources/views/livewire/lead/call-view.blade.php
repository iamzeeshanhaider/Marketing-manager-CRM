<div class="" style="min-height: 100vh;">
    <div class="{{ canEngageWithLead($lead->id) ? 'show' : 'hide' }}">
        <div class="card border-0">
            <div class="d-flex align-items-start justify-content-between p-2">
                <div>
                    <livewire:alert />
                </div>

                <div class="text-right">
                    <button class="btn btn-success" type="button" wire:click="startCall" wire:loading.attr="disabled">
                        <i class="ft-phone"></i> Call
                    </button>
                </div>
            </div>

        </div>
    </div>
    <div class="chat">
        <div class="text-center py-3">
            @if ($hasMore)
                <button wire:click="loadMore" class="btn btn-primary btn-sm"><i class="ft-arrow-up"></i> More</button>
            @endif
        </div>
        <div class="d-flex flex-column-reverse align-items-end justify-content-end px-3">
            @foreach ($conversations as $conversation)
                @if ($conversation->is_oubtbound)
                    <div class="d-flex justify-content-end row">
                        <div class="col-9">
                            <div class="d-flex flex-row-reverse">
                                <div class="chat-avatar">
                                    <a class="avatar" data-toggle="tooltip" href="#" data-placement="left"
                                        title="{{ $conversation->agent->name }}" data-original-title="">
                                        <img src="{{ $conversation->agent->getAvatar() }}" alt="avatar">
                                    </a>
                                </div>
                                <p class="pr-2" style="min-width: 250px">{!! $conversation->message !!}</p>
                            </div>
                            <div class="d-flex small">
                                <p class="text-muted ">{{ $conversation->created_at->diffForHumans() }}</p>
                                <i
                                    class="px-2 ft-check-circle {{ $conversation->status === 'success' ? 'text-success' : 'text-danger' }}"></i>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

</div>
