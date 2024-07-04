<span class="badge badge-pill badge-{{ $row->type_color ? $row->type_color : ($row->location->type_color ? $row->location->type_color : 'primary') }}">{{ ucwords($value) }}</span>
