<div>
    <div class="p-3">

        <form wire:submit.prevent='@if ($editing) updateLead @endif'>
            @php
                $fieldNames = [
                    'full_name',
                    'email',
                    'tel',
                    'address',
                    'state',
                    'city',
                    'country',
                    'postcode',
                    'source',
                    'company_id',
                    'agent_id',
                    'status',
                    'qualification',
                    'work_experience',
                    'data_array',
                ];
            @endphp

            @foreach ($fieldNames as $fieldName)
                <div class="row align-items-end">
                    <div class="col-sm-3">
                        <h6 class="mb-0">{{ ucwords(str_replace(['_', '_id'], ' ', $fieldName)) }}:</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        @if ($editing)
                            <x-form.input name="{{ $fieldName }}" value="{{ $$fieldName }}" isWire="true" />
                        @else
                            @switch($fieldName)
                                @case('company_id')
                                    {{ $lead->company->name }}
                                @break

                                @case('agent_id')
                                    {{ optional($lead->agent)->name }}
                                @break

                                @case('status')
                                    <x-lead-status-badge :status="$lead->leadStatus" />
                                @break

                                @default
                                    {{ $$fieldName->name ?? $$fieldName }}
                            @endswitch
                        @endif
                    </div>
                </div>
                @if (!$loop->last)
                    <hr>
                @endif
            @endforeach

        </form>

        <x-action-button route="{{ route('leads.edit', ['company' => $lead->company->id, 'lead' => $lead->id]) }}"
            label="Edit" icon="fa fa-pencil" btn_class="btn btn-primary" />
    </div>
</div>
