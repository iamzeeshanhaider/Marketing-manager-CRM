@props([
    'label' => '',
    'name' => 'lead_agent_select',
    'selected' => null,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'multiple' => false,
    'role' => 'agent',
    'isWire' => false,
    'options' => [],
])

@php
    $id = strtolower($name);
@endphp
<li>
    <div class="row select-user">
        <div class="col-md-4">
            <label for="{{ $id }}">{{ ucwords($label) }}</label>
        </div>
        <div class="col-md-7">
            <select class="form-control @error($name) is-invalid @enderror" data-search="on" id="{{ $id }}" style="width: 100%">
               <option value="">Select User</option>

            </select>
        </div>
    </div>
</li>
<style>
    .select-user {

        min-width: 400px;
    }
    .select-user .col-md-4 {
        margin: 0px;
        padding: 0px;
    }
</style>
