<div class="chat-container">
    <div class="conversation-history">
        <div class="chat">
            @if ($hasMore)
                <div class="text-center py-3">
                    <button wire:click="loadMore" class="btn btn-primary btn-sm"><i class="ft-arrow-up"></i> More</button>
                </div>
            @endif
            <div>
                <div class="media-list">
                    @foreach ($conversations as $index => $conversation)
                        <div>
                            <div id="conversationCollapse-{{ $index + 1 }}" class="card-header p-0">
                                <a data-toggle="collapse" href="#collapse-{{ $index }}" aria-expanded="false"
                                    aria-controls="collapse-{{ $index }}"
                                    class="email-app-sender media border-0 bg-blue-grey bg-lighten-5 collapsed nav-link">
                                    <div class="media-body w-100">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-md">
                                                    <img class="media-object rounded-circle"
                                                        src="{{ optional($conversation->agent)->getAvatar() ?? asset(constPaths::DefaultAvatar) }}"
                                                        alt="Generic placeholder image">
                                                </span>
                                                <div class="ml-2">
                                                    <h6 class="list-group-item-heading">
                                                        {{ $conversation->subject ?? optional($conversation->agent)->name ?? optional($conversation->campaign)->name }}
                                                    </h6>
                                                </div>
                                            </div>
                                            <p class="text-muted small">
                                                <span>{{ $conversation->created_at->format('d F, Y') }}</span>
                                                <br>
                                                <span>{{ $conversation->created_at->format('H:i A') }}</span>
                                            </p>
                                        </div>
                                    </div>

                                </a>
                            </div>

                            <div id="collapse-{{ $index }}" role="tabpanel"
                                aria-labelledby="conversationCollapse-{{ $index + 1 }}"
                                class="card-collapse collapse" aria-expanded="true" style="">
                                <div class="card-content">
                                    <div class="card-body">
                                        {!! optional($conversation->campaign)->email_content ?? $conversation->message !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="chat-form collapse fade {{ canEngageWithLead($lead->id) ? 'show' : 'hide' }}">
        <div class="card border-0">
            <livewire:alert />

            @if ($show_form)
                <div>
                    <form wire:submit.prevent="sendEmail()" class="card-body pb-1">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input class="form-control @error('subject') is-invalid @enderror"  id="subject" wire:model="subject" type="text">
                                @error('subject')
                                    <span class="text-danger small" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea wire:model="content"  id="content" rows="5" class="form-control @error('content') is-invalid @enderror"></textarea>
                                @error('content')
                                    <span class="text-danger small" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-right small">
                            <button class="btn btn-primary btn-sm" type="submit" wire:loading.attr="disabled">
                                <i class="ft-navigation"></i> Send Mail
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="text-right small">
                <button class="btn-sm btn-primary btn" wire:click="toggleForm" type="button" title="Send Mail">
                    <i class="ft-{{ $show_form ? 'arrow-down' : 'mail' }}"></i></button>
            </div>

        </div>
    </div>
</div>
