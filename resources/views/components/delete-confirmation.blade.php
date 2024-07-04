@props([
    'index' => 1,
    'action' => '',
    'label' => '',
    'buttonClass' => '',
    'title' => 'Are you sure?',
    'description' => 'Do you really want to delete these records? This Action cannot be undone.',
])
<div class="" id="{{$index}}">
    <a class="{{ $buttonClass }}" href="#" data-toggle="modal" data-target="#deleteModal-{{$index}}">
        <i class="ft-trash danger"></i> {{ $label }}
    </a>

    <div class="modal fade show" id="deleteModal-{{$index}}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="border-0 modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ $action }}" method="post" class="p-3 text-center "
                    onsubmit="$('#delete-button').attr('disabled', true)">
                    @csrf
                    @method('DELETE')

                    <div class="icon-box" >
                        <i class="fa fa-times"></i>
                    </div>

                    <h4 class="py-2 modal-title w-100">{{ $title }}</h4>

                    <div class="modal-body">
                        <p>{{ $description }}</p>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-danger" id="delete-button">Yes, Proceed</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.icon-box {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    border-radius: 50%;
    z-index: 9;
    text-align: center;
    border: 3px solid #f15e5e;
}

.icon-box i {
    color: #f15e5e;
    font-size: 46px;
    display: inline-block;
    margin-top: 13px;
}
</style>
