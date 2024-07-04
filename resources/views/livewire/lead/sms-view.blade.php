<div class="chat-container">
    <div class="conversation-history chat-history d-flex flex-column-reverse h-100">
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
                                            title="{{ optional($conversation->agent)->name ?? 'System' }}">
                                            <img src="{{ optional($conversation->agent)->getAvatar() ?? asset(constPaths::DefaultAvatar) }}"
                                                alt="avatar">
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
    <div class="chat-form collapse fade {{ canEngageWithLead($lead->id) ? 'show' : 'hide' }}">
        <div class="card border-0">
            <livewire:alert />

            <form wire:submit.prevent="sendSMS()" class="card-body pb-1">
                <div class="input-group">
                    <input type="text" wire:model="content" required
                        class="form-control @error('content') is-invalid @enderror" value="{{ old('content') }}"
                        maxlength="150">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit" wire:loading.attr="disabled"> <i
                                class="ft-navigation"></i> Send</button>
                    </div>
                </div>
                <div class="text-left small">
                    @error('message')
                        <span class="text-danger" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </form>
        </div>
    </div>
</div>
