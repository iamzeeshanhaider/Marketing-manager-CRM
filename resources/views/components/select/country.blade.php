<div>
    <label for="country_id" class="mb-1 control-label">{{ $label }}</label>
    <div class="input-group">
        <select @if ($isWire) wire:model.lazy="country_id" @else name="country_id" @endif
            id="country_id" class="form-control">
            <option value="" selected disabled>@lang('-- Select Country --')</option>
            @foreach ($countries as $country)
                <option value="{{ $country->id }}"
                    @isset($selected) {{ is_selected($country->id, $selected) }} @endisset>
                    {{ $country->name }}</option>
            @endforeach
        </select>
    </div>
    @error('country_id')
        <span class="text-danger small">{{ $message }}</span>
    @enderror
</div>
