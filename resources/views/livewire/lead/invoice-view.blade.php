<div class="chat-container">
    <div class="conversation-history">
        <div class="chat">
            @if ($hasMore)
                <div class="text-center py-3">
                    <button wire:click="loadMore" class="btn btn-primary btn-sm"><i class="ft-arrow-up"></i> More</button>
                </div>
            @endif
            <div>
                <div class="media-list">


                    @forelse ($conversations as $index => $conversation)
                        <div>
                            <div id="conversationCollapse-{{ $index + 1 }}" class="card-header p-0">
                                <a data-toggle="collapse" href="#collapse-{{ $index }}" aria-expanded="false"
                                    aria-controls="collapse-{{ $index }}"
                                    class="email-app-sender media border-0 bg-blue-grey bg-lighten-5 collapsed nav-link">
                                    <div class="media-body w-100">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-md">
                                                    <img class="media-object rounded-circle"
                                                        src="{{ optional($conversation->agent)->getAvatar() ?? asset(constPaths::DefaultAvatar) }}"
                                                        alt="Generic placeholder image">
                                                </span>
                                                <div class="ml-2">
                                                    <h6 class="list-group-item-heading">
                                                        {{ $conversation->subject ?? (optional($conversation->agent)->name ?? optional($conversation->campaign)->name) }}
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-muted" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>

                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <button class="dropdown-item"
                                                        onclick="sendEmail({{ $conversation->id }})">Send
                                                        Invoice</button>
                                                    <button wire:click="editInvoice({{ $conversation->id }})"
                                                        class="dropdown-item">Edit Invoice</button>
                                                    <button wire:click="deleteInvoice({{ $conversation->id }})"
                                                        class="dropdown-item">Delete</button>
                                                </div>
                                            </div>
                                            <p class="text-muted small">
                                                <span>{{ $conversation->created_at->format('d F, Y') }}</span>
                                                <br>
                                                <span>{{ $conversation->created_at->format('H:i A') }}</span>
                                            </p>
                                        </div>
                                    </div>

                                </a>
                            </div>

                            <div id="collapse-{{ $index }}" role="tabpanel"
                                aria-labelledby="conversationCollapse-{{ $index + 1 }}"
                                class="card-collapse collapse" aria-expanded="true" style="">
                                <div class="card-content">
                                    <div class="card-body">
                                        @if ($conversation->invoice)
                                            {{-- {{ print_r($conversations) }} --}}

                                            <a href="{{ asset('invoices/' . $conversation->invoice) }}"
                                                target="_blank"><img height="50" width="50"
                                                    src="{{ asset('invoices/pdf-file.png') }}" alt="invoice "></a><br>
                                        @endif
                                        {!! $conversation->message !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="chat-form collapse fade {{ canEngageWithLead($lead->id) ? 'show' : 'hide' }}">
        <div class="card border-0">
            <livewire:alert />
            <div id="errorMessageContainer" style="display: none">
                <div class="alert bg-danger alert-icon-left alert-arrow-left alert-dismissible mb-2 text-white"
                    role="alert">
                    <span class="alert-icon"><i class="la la-thumbs-o-down"></i></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Oh !</strong> Please fix the following issues to continue
                    <ul class="error">

                    </ul>
                </div>
            </div>
            <div id="successMessageContainer" style="display: none">
                <div class="alert bg-success alert-icon-left alert-arrow-left alert-dismissible mb-2 text-white"
                    role="alert">
                    <span class="alert-icon"><i class="la la-thumbs-o-down"></i></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Hurray !</strong> Invoice Created Successfully
                </div>
            </div>
            @if ($show_form)
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Create Invoice</h5>
                            <button type="button" wire:click="hideInvoiceForm()" class="btn btn-danger btn-sm">
                                <i class="fa fa-times"></i> Hide Form
                            </button>
                        </div>
                    </div>
                    <div class="card-body pb-1 overflow-y-auto" style="max-height: 400px;">
                        <form method="POST" id="invoiceForm" action="{{ route('save.invoice', ['lead' => $lead]) }}"
                            class="pb-1">
                            @csrf
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input name="subject" class="form-control " id="subject" type="text">

                                </div>
                            </div>
                            <input type="hidden" name="items_Data" id="itemsData">
                            <div class="col-md-12">
                                <div class="form-group" wire:ignore>
                                    <select class="form-control" name="itemsArray[]" multiple data-search="on"
                                        id="itemsSelect" style="width: 100%" placeholder="Select Items">
                                        @forelse ($items as $item)
                                            <option data-price="{{ $item->price }}" value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @empty
                                            <option value="">No Items Found</option>
                                        @endforelse
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 table-responsive">
                                <table id="table-id" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Discount</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea id="content" rows="5" class="form-control" name="content"></textarea>

                                </div>
                            </div>
                            <div class="text-right small">
                                <button class="btn btn-primary btn-sm" id="submitForm1" type="submit">
                                    <i class="ft-navigation"></i> Save Invoice
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif


            @if ($invoiceData?->id && $showInvoice)
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Update Invoice</h5>
                            <button type="button" wire:click="hideInvoiceForm()" class="btn btn-danger btn-sm">
                                <i class="fa fa-times"></i> Hide Form
                            </button>
                        </div>
                    </div>
                    <div class="card-body pb-1 overflow-y-auto" style="max-height: 400px;">
                        <form method="POST" id="invoiceForm-update" action="" class="pb-1">
                            @csrf
                            {{-- <input type="text" wire:model="invoiceData" name="" value="{{ $invoiceData }}"
                            id=""> --}}
                            <input type="hidden" name="" id="invoice_id" value="{{ $invoiceData?->id }}">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input name="subject" class="form-control " value="{{ $invoiceData->subject }}"
                                        id="subject" type="text">

                                </div>
                            </div>
                            <input type="hidden" name="items_Data" value="{{ $hiddenFieldDAta }}"
                                id="invoiceItem_data">
                            <div class="col-md-12">
                                <div class="form-group" wire:ignore>
                                    <select class="form-control" name="itemsArray[]" multiple data-search="on"
                                        id="invoiceItems" style="width: 100%" placeholder="Select Items">
                                        @forelse ($items as $item)
                                            <option {{ in_array($item->id, $invoiceDataItemsIds) ? 'selected' : '' }}
                                                data-price="{{ $item->price }}" value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @empty
                                            <option value="">No Items Found</option>
                                        @endforelse
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-12 table-responsive">
                                <table id="table-id-invoice" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Discount</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($invoiceDataItems as $invoiceItem)
                                            <tr id="row_{{ $invoiceItem->id }}">
                                                <td> <input class="form-control" readonly type="text"
                                                        value="{{ $invoiceItem->name }}" name="name"
                                                        id="">
                                                </td>
                                                <td> <input class="form-control" readonly type="text"
                                                        value="{{ $invoiceItem->price }}" name="price"
                                                        id="">
                                                </td>

                                                <td> <input class="form-control" name="quantity" type="text"
                                                        value="{{ $invoiceItem->pivot->quantity }}" name=""
                                                        id=""> </td>
                                                <td> <input class="form-control" name="discount" type="text"
                                                        name="" value="{{ $invoiceItem->pivot->discount }}"
                                                        id=""> </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea id="content" rows="5" class="form-control" name="content">{{ $conversation->message }}</textarea>

                                </div>
                            </div>
                            <div class="text-right small">
                                <button class="btn btn-primary btn-sm" id="submitForm1" type="submit">
                                    <i class="ft-navigation"></i> Save Invoice
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif



            <div class="text-right small">
                <button class="btn-sm btn-primary btn" wire:click="toggleForm" type="button" id="items"
                    title="Send Mail">
                    <i class="ft-{{ $show_form ? 'arrow-down' : 'mail' }}"></i></button>
            </div>
        </div>
    </div>
</div>
<!-- The modal markup ---->
<div class="modal fade" id="itemsModal" tabindex="-1" role="dialog" aria-labelledby="itemsLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-white company-header">
                <h5 class="modal-title" id="itemsLabel">Items Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subject">Quantity</label>
                            <input class="form-control" id="quantity" type="number">
                        </div>
                    </div>
                    <input type="hidden" id="itemId">
                    <input type="hidden" id="name">
                    <input type="hidden" id="price">
                    <input type="hidden" id="hidden_object">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input class="form-control" name="discount" id="discount" type="number">
                        </div>
                    </div>
                    <input type="hidden" name="name" id="name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="applydiscount" class="btn btn-primary">Apply</button>
                <button type="button" id="close" data-dismiss="modal" class="btn btn-danger">Canel</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            let items = [];
            Livewire.hook('element.initialized', (el, component) => {
                if (el.id === 'itemsSelect') {


                    $('#itemsSelect').select2({
                        placeholder: 'Select an Item'
                    });
                    $('#itemsSelect').on('select2:select', function(e) {
                        var selectedItem = e.params.data;
                        var itemId = selectedItem.id;
                        var itemName = selectedItem.text;
                        let price = $(e.params.data.element).data('price');
                        $('#name').val(itemName);
                        $('#itemId').val(itemId);
                        $('#price').val(price);
                        $('#itemsModal').on('show.bs.modal', function(e) {
                            $(this).find(
                                    'input[type="text"], input[type="number"], textarea')
                                .val('');
                        });
                        $('#itemsModal').modal('show');
                    });

                    $('.close').on('click', function() {
                        $('#itemsModal').modal('hide');
                    });
                    $('#applydiscount').on('click', function() {
                        var items = JSON.parse($('#itemsData').val() || '{}');
                        var itemId = $('#itemId').val();
                        var quantity = $('#quantity').val();
                        var discount = $('#discount').val();
                        var price = $('#price').val();
                        var name = $('#name').val();

                        var data = {
                            'quantity': quantity,
                            'discount': discount,
                            'price': price,
                            'name': name,
                            'itemId': itemId
                        };
                        items[itemId] = data;
                        var dataArray = JSON.stringify(Object.values(items));
                        $('#itemsData').val(dataArray);
                        $('#itemsModal').modal('hide');
                        appendDataToTable(JSON.parse(dataArray));

                        function appendDataToTable(data) {
                            var tbody = $('#table-id tbody');
                            tbody.empty();
                            data.forEach(function(item) {
                                var name = item.name.trim();
                                var price = item.price.trim();
                                var quantity = item.quantity;
                                var discount = item.discount;
                                var id = item.itemId;
                                var newRow = $('<tr>').attr('id', 'row_' + id).append(
                                    $('<td>').append($('<input>').attr('type', 'text')
                                        .addClass('form-control').attr('name', 'name')
                                        .val(name).prop('readonly', true)),
                                    $('<td>').append($('<input>').attr('type', 'text')
                                        .addClass('form-control').attr('name', 'price')
                                        .val(price).prop(
                                            'readonly', true)),

                                    $('<td>').append($('<input>').attr('type', 'number')
                                        .addClass('form-control').attr('name',
                                            'quantity').attr("id", "quantity_" + id)
                                        .val(quantity)
                                    ),
                                    $('<td>').append($('<input>').attr('type', 'number')
                                        .addClass('form-control').attr('name',
                                            'discount').attr("id", "discount_" + id)
                                        .val(discount)
                                    )
                                );

                                tbody.append(newRow);
                            });
                        }
                        $('#itemsSelect').on('select2:unselect', function(e) {
                            var removedItemId = e.params.data.id;
                            var rowId = 'row_' + removedItemId;
                            $('#' + rowId).remove();
                            var items = JSON.parse($('#itemsData').val());
                            var filteredItems = items.filter(item => item.itemId !==
                                removedItemId);
                            if (filteredItems.length < items.length) {
                                $('#itemsData').val(JSON.stringify(filteredItems));
                            }

                        });
                        $('#table-id').on('input', 'input', function() {
                            var row = $(this).closest('tr');
                            var itemId = row.attr('id');
                            var quantity = row.find('input[name="quantity"]').val();
                            var discount = row.find('input[name="discount"]').val();
                            var name = row.find('input[name="name"]').val();
                            var price = row.find('input[name="price"]').val();
                            var id = itemId.match(/\d+/)[0];
                            var data = {
                                'quantity': quantity,
                                'discount': discount,
                                'price': price,
                                'name': name,
                                'itemId': id
                            };
                            var items = JSON.parse($('#itemsData').val());
                            var updatedItems = items.map(item => {
                                if (item.itemId === id) {
                                    return data;
                                } else {
                                    return item;
                                }
                            });
                            $('#itemsData').val(JSON.stringify(updatedItems));

                        });
                    });
                    $(document).ready(function() {
                        $('#errorMessageContainer').hide();

                        $("#invoiceForm").submit(function(e) {
                            e.preventDefault();
                            let frmData = new FormData(this);
                            $.ajax({
                                url: "{{ route('save.invoice', ['lead' => $lead]) }}",
                                type: "POST",
                                data: frmData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    $('#errorMessageContainer').hide();
                                    $('#successMessageContainer').show();
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2000);
                                },
                                error: function(xhr, status, error) {
                                    if (xhr.status === 422) {
                                        let errors = xhr.responseJSON.errors;
                                        let errorMessage = '';
                                        $.each(errors, function(key, value) {
                                            errorMessage += value[0] +
                                                '\n';
                                        });
                                        $('.error').html('');
                                        $('.error').html(errorMessage);
                                        $('#errorMessageContainer').show();
                                    } else {
                                        $('.error').html('');
                                        $('.error').html(xhr.responseJSON
                                            .errors);
                                        $('#errorMessageContainer').show();
                                    }
                                }
                            });
                        })
                    });
                }
                if (el.id === 'invoiceItems') {
                    $('#invoiceItems').select2({
                        placeholder: 'Select an Item'
                    });
                    $('#table-id-invoice').on('input', 'input', function() {
                        var row = $(this).closest('tr');
                        var itemId = row.attr('id');
                        var quantity = row.find('input[name="quantity"]').val();
                        var discount = row.find('input[name="discount"]').val();
                        var name = row.find('input[name="name"]').val();
                        var price = row.find('input[name="price"]').val();
                        var id = itemId.match(/\d+/)[0];
                        var data = {
                            'quantity': quantity,
                            'discount': discount,
                            'price': price,
                            'name': name,
                            'itemId': id
                        };
                        var items = JSON.parse($('#invoiceItem_data').val());
                        var updatedItems = items.map(item => {
                            if (item.itemId == id) {
                                return data;
                            } else {
                                return item;
                            }
                        });
                        $('#invoiceItem_data').val(JSON.stringify(updatedItems));
                    });
                    $('#invoiceItems').on('select2:unselect', function(e) {
                        var removedItemId = e.params.data.id;
                        var rowId = 'row_' + removedItemId;
                        $('#' + rowId).remove();
                        var items = JSON.parse($('#invoiceItem_data').val());
                        var filteredItems = items.filter(item => item.itemId !=
                            removedItemId);
                        if (filteredItems.length < items.length) {
                            $('#invoiceItem_data').val(JSON.stringify(
                                filteredItems));
                        }
                    });
                    $('#invoiceItems').on('select2:select', function(e) {
                        var selectedItem = e.params.data;
                        var itemId = selectedItem.id;
                        var itemName = selectedItem.text;
                        let price = $(e.params.data.element).data('price');
                        $('#name').val(itemName);
                        $('#itemId').val(itemId);
                        $('#price').val(price);
                        $('#itemsModal').on('show.bs.modal', function(e) {
                            $(this).find(
                                    'input[type="text"], input[type="number"], textarea')
                                .val('');
                        });
                        $('#itemsModal').modal('show');
                    });

                    $('#applydiscount').on('click', function() {
                        var items = JSON.parse($('#invoiceItem_data').val() || '{}');
                        var itemId = $('#itemId').val();
                        var quantity = $('#quantity').val();
                        var discount = $('#discount').val();
                        var price = $('#price').val();
                        var name = $('#name').val();
                        var data = {
                            'quantity': quantity,
                            'discount': discount,
                            'price': price,
                            'name': name,
                            'itemId': itemId
                        };
                        items[itemId] = data;
                        var dataArray = JSON.stringify(Object.values(items));
                        $('#invoiceItem_data').val(dataArray);
                        $('#itemsModal').modal('hide');
                        appendDataToTable(JSON.parse(dataArray));

                        function appendDataToTable(data) {
                            var tbody = $('#table-id-invoice tbody');
                            tbody.empty();
                            data.forEach(function(item) {
                                var name = item.name.trim();
                                var quantity = item.quantity;
                                var discount = item.discount;
                                var price = item.price;
                                var id = item.itemId;
                                var newRow = $('<tr>').attr('id', 'row_' + id).append(
                                    $('<td>').append($('<input>').attr('type', 'text')
                                        .addClass(
                                            'form-control').attr('name', 'name').val(
                                            name).prop(
                                            'readonly', true)),
                                    $('<td>').append($('<input>').attr('type', 'text')
                                        .addClass(
                                            'form-control').attr('name', 'price').val(
                                            price).prop(
                                            'readonly', true)),

                                    $('<td>').append($('<input>').attr('type', 'number')
                                        .addClass('form-control').attr('name',
                                            'quantity').attr(
                                            "id", "quantity_" + id).val(quantity)
                                    ),
                                    $('<td>').append($('<input>').attr('type', 'number')
                                        .addClass('form-control').attr('name',
                                            'discount').attr(
                                            "id", "discount_" + id).val(discount)
                                    )
                                );

                                tbody.append(newRow);
                            });
                        }
                        $('#itemsSelect').on('select2:unselect', function(e) {
                            var removedItemId = e.params.data.id;
                            var rowId = 'row_' + removedItemId;
                            $('#' + rowId).remove();
                            var items = JSON.parse($('#invoiceItem_data').val());
                            var filteredItems = items.filter(item => item.itemId !==
                                removedItemId);
                            if (filteredItems.length < items.length) {
                                $('#invoiceItem_data').val(JSON.stringify(
                                    filteredItems));
                            }

                        });
                        // $('#table-id').on('input', 'input', function() {
                        //     var row = $(this).closest('tr');
                        //     var itemId = row.attr('id');
                        //     var quantity = row.find('input[name="quantity"]').val();
                        //     var discount = row.find('input[name="discount"]').val();
                        //     var name = row.find('input[name="name"]').val();
                        //     var id = itemId.match(/\d+/)[0];
                        //     var data = {
                        //         'quantity': quantity,
                        //         'discount': discount,
                        //         'name': name,
                        //         'itemId': id
                        //     };
                        //     var items = JSON.parse($('#itemsData').val());
                        //     var updatedItems = items.map(item => {
                        //         if (item.itemId === id) {
                        //             return data;
                        //         } else {
                        //             return item;
                        //         }
                        //     });
                        //     $('#itemsData').val(JSON.stringify(updatedItems));

                        // });
                    });

                    $("#invoiceForm-update").submit(function(e) {
                        e.preventDefault();
                        let id = $('#invoice_id').val();
                        let url = "{{ url('update/invoice/') }}/" + id;
                        let frmData = new FormData(this);
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: frmData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $('#errorMessageContainer').hide();
                                $('#successMessageContainer').show();
                                setTimeout(function() {
                                    window.location.reload();
                                }, 2000);
                            },
                            error: function(xhr, status, error) {
                                console.log(xhr.status);
                                if (xhr.status === 422) {
                                    let errors = xhr.responseJSON.errors;
                                    let errorMessage = '';
                                    $.each(errors, function(key, value) {
                                        errorMessage += value[0] +
                                            '\n';
                                    });
                                    $('.error').html('');
                                    $('.error').html(errorMessage);
                                    $('#errorMessageContainer').show();
                                } else {
                                    $('.error').html('');
                                    $('.error').html(xhr.responseJSON
                                        .errors);
                                    $('#errorMessageContainer').show();
                                }
                            }
                        });
                    })
                }

            });
        });

        function sendEmail(invoiceId) {
            let url = "{{ url('send/invoice/') }}/" + invoiceId;
            $.ajax({
                url: url,
                type: "POST",
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#errorMessageContainer').hide();
                    $('#successMessageContainer').show();
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] +
                                '\n';
                        });
                        $('.error').html('');
                        $('.error').html(errorMessage);
                        $('#errorMessageContainer').show();
                    } else {
                        $('.error').html('');
                        $('.error').html(xhr.responseJSON
                            .errors);
                        $('#errorMessageContainer').show();
                    }
                }
            });
        }
    </script>
@endpush
