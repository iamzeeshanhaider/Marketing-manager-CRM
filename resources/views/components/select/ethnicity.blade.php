<div>
    <label for="ethnicity" class="mb-1 control-label">{{ $label }}</label>
    <div class="input-group">
        <select @if ($isWire) wire:model.lazy="ethnicity" @else name="ethnicity" @endif required
            id="ethnicity" class="form-control">
            <option value="" selected>@lang('--Select Ethnicity--')</option>
            @foreach ($ethnicities as $value)
                <option value="{{ $value }}"
                    @isset($selected) {{ is_selected($value, $selected) }} @endisset>
                    {{ ucwords($value) }}</option>
            @endforeach
        </select>
    </div>
    @error('ethnicity')
        <span class="text-danger small">{{ $message }}</span>
    @enderror
</div>
