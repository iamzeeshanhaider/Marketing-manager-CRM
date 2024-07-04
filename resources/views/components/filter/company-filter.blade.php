<div class="btn-group px-2 dropdown">
    <button type="button" class="btn btn-clear btn-sm btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        Filter By {{ $selected->name ?? 'Company' }}
    </button>
    <div class="dropdown-menu arrow">
        @forelse ($companies as $company)
            <a href="{{ request()->fullUrlWithQuery(['company' => $company->id]) }}"
                class="dropdown-item {{ request('company') == $company->id ? 'active' : '' }}">
                {{ $company->name }}
            </a>
        @empty
            <a href="#" disabled class="dropdown-item">No Companies Found</a>
        @endforelse
    </div>
</div>
