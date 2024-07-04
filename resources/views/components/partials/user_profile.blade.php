@props(['user' => null])

@if ($user)
    <div class="d-flex align-items-center">
        <div class="rounded-circle mr-1"
            style="height: 32px; width: 32px; background-image: url('{{ $user['avatar'] }}'); background-position: center; background-repeat: no-repeat; background-size: cover;">
        </div>
        <a href="{{ route('users.show', ['group' => strtolower($user['roles'][0]['name']), 'user' => $user['slug']]) }}"
            title="Student Info">
            {{ $user['name'] }} {{ $user['lname'] }}
        </a>
    </div>
@else
    Un-Assigned
@endif
