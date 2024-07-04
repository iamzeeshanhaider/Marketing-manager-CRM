<div>
    <label for="gender-select" class="mb-1 control-label">{{ $label }}</label>
    <div class="input-group">
        <select required @if ($isWire) wire:model.lazy="gender" @else name="gender" @endif
            id="gender-select" class="form-control">
            @if (count($genders))
                <option value="" selected disabled>@lang('--- Select Gender ---')</option>
                @foreach ($genders as $value)
                    <option value="{{ $value }}" class="text-capitalize"
                        @isset($selected) {{ is_selected($value, $selected) }} @endisset>
                        {{ ucwords($value) }}</option>
                @endforeach
            @endif
        </select>
    </div>
    @error('gender')
        <span class="text-danger small">{{ $message }}</span>
    @enderror
</div>
