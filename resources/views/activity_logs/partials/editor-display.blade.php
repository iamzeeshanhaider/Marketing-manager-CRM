<span class="iro_editor-content">
    {!! \Str::limit(html_entity_decode(strip_tags($value)), 42) !!}
    @if (strlen(strip_tags($value)) > 42)
    <a onclick="handleGeneralModal(this)" class="btn btn-sm text-primary"
        data-link="{{ route('logs.show', $row->id) }}" title="Preview">
        <small>more</small>
    </a>
    @endif
</span>
