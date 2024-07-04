<div class="dropdown">
    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
        Actions
    </button>
    <div class="dropdown-menu">
        <a class="dropdown-item"
            href="{{ route('leads.show', ['company' => $row->company->id, 'lead' => $row->id]) }}">View</a>
        <a class="dropdown-item"
            href="{{ route('leads.edit', ['company' => $row->company->id, 'lead' => $row->id]) }}">Edit</a>
        <a class="dropdown-item"
            href="{{ route('leads.destroy', ['company' => $row->company->id, 'lead' => $row->id]) }}">Delete</a>
    </div>
</div>
