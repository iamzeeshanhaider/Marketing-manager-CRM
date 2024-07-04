<div>
    <div class="card p-3 border-0 rounded shadow-lg">
        <form action="{{ route('user.profile.submit', ['id' => $user->id]) }}" method="get" >
            @csrf
            <div class="" style="min-height: 500px">
                @switch($currentStep)
                    @case('personal_info')
                        {{-- Personal Info --}}
                        <div class="card-header bg-light border-bottom h4">@lang('Personal Information')</div>

                        <div class="row p-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1" for="full_name">@lang('Full Name'):</label>
                                    <input class="form-control" name="full_name" type="text" value="{{$user->name ?? ''}}"
                                           placeholder="Enter Your Full Name">
                                    @error('full_name')
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1" for="user_email">@lang('Email'):</label>
                                    <input class="form-control" name="user_email" type="email" readonly
                                           value="{{$user->email ?? ''}}">
                                    @error('user_email')
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1" for="user_designation">@lang('Designation'):</label>
                                    <input class="form-control" name="user_designation" id="designation" type="text" readonly
                                           value="{{$user->designation ?? ''}}">
                                    @error('user_designation')
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1" for="user_phone">@lang('Phone Number'):</label>
                                    <input class="form-control" name="user_phone" id="phone" type="tel" value="{{$user->phone ?? ''}}"
                                           placeholder="+44201234567">
                                    @error('user_phone')
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1" for="user_dob">@lang('Date of Birth'):</label>
                                    <input class="form-control" name="user_dob" id="dob" type="date" value="{{$user->dob}}">
                                    @error('user_dob')
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1" for="user_gender">@lang('Gender'):</label>
                                    <select name="user_gender" class="form-control">
                                        @foreach(App\Enums\Gender::asSelectArray() as $value => $label)
                                            <option value="{{ $value }}" {{ old('gender') === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('user_gender')
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1" for="city">@lang('Ethnicity'):</label>
                                    <select name="user_ethnicity" class="form-control">
                                        @foreach(App\Enums\Ethnicity::asSelectArray() as $value => $label)
                                            <option value="{{ $value }}" {{ old('ethnicity') === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_ethnicity')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="mb-1" for="user_country">@lang('Country'):</label>
                                <select name="user_country" class="form-control">
                                    @foreach(App\Models\Country::asSelectArray() as $value => $label)
                                        <option value="{{ $value }}" {{ old('country') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_country')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1" for="user_city">@lang('City'):</label>
                                    <input class="form-control" name="user_city" type="text" value="{{$user->city ?? ''}}"
                                           placeholder="London">
                                    @error('user_city')
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-1" for="user_address">@lang('Address'):</label>
                                    <textarea class="form-control" name="user_address" rows="4">{{$user->address ?? ''}}</textarea>
                                    @error('user_address')
                                    <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        @break

                @endswitch
            </div>

            {{-- Buttons --}}
            <div class="btn-group px-2" role="group" aria-label="Basic example">
                {{-- Submit button --}}
                <button type="submit" class="btn btn-primary">
                    Save
                </button>

            </div>
            {{-- Buttons --}}
        </form>
    </div>
</div>
