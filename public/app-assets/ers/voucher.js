var categoriesDetailCount = 1;
var itemCount = -1;
var categoriesCount = 1;
$('body').on('change','select.voucherCategory', function(evt){
    var category = $(this).find(':selected').data('view');
    var categoryContainer = $(this);
    var categoryID = $(this).val();
    evt.preventDefault();
    categoriesDetailCount = categoriesDetailCount+1;
    itemCount = itemCount+1;
    $('#categoriesDetailCount').val(categoriesDetailCount);
    var element_id = $(this).attr('id');
    if($("[data-parent='" + element_id + "']").length){
        alert('Details already exist against this selection. Please add more selection.');
    }
    else{
        if($('#'+category).length){
            alert('Oh! This Category Already Exist.');
        }
        else{
            $.ajax({
                url: '/voucher/category/form/display' + category + '/parent/' + element_id + '/item/' + itemCount + '/id/' + categoryID,
                success:function (response){
                    $("#rowContainer").append(JSON.parse(response).row);
                    $("#itemCount").val(itemCount);
                    categoryContainer.attr('disabled', true);
                    if(category == 'hotel-stay-pakistan'){
                        $('.datetimeHotelStay').daterangepicker({
                            timePicker: false,
                        });
                    }
                    else if(category == 'out-of-lahore-meal'){
                        $('.datetimeOutOfLahore').daterangepicker({
                            timePicker: false,
                            "maxSpan": {
                                "days": 5
                            }
                        });
                    }
                    else{
                        $('.datetime').daterangepicker({
                            timePicker: true,
                            timePicker24Hour: true,
                            timePickerIncrement: 1,
                            maxSpan:3,
                            locale: {
                                format: 'MM/DD/YYYY HH:mm'
                            }
                        });
                    }
                }
            });
        }
    }
}).on('click', '#addVoucherItem', function(){
    itemCount = itemCount+1;
    var element_id = $(this).data('category');
    var categoryID = $(this).data('category-id');
    $.ajax({
        url: "{{ URL::to('item/add') }}/" + itemCount + "/parent/" + element_id + '/id/' + categoryID,
        success:function (response){
            $("#"+element_id).closest('div').find('.card-body').append(JSON.parse(response).row);
            $("#itemCount").val(itemCount);
            if(element_id == 'hotel-stay-pakistan'){
                $('.datetime').daterangepicker({
                    timePicker: false,
                });
            }
            else{
                $('.datetime').daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerIncrement: 1,
                    locale: {
                        format: 'MM/DD/YYYY HH:mm'
                    }
                });
            }
        }
    });
}).on('change', "input[name^='kms_']", function(){
    var category = $('#'+$(this).closest('div.card').data('parent')+' option:selected').text();
    var totalAmountContainer = $('#total_'+$(this).closest('div.card').data('parent'));
    if(category == 'Fuel - Mileage (within city)' || category == 'Fuel - Mileage (out of city)'){
        var kms = $(this).val();
        var from =  $(this).closest('div.form-row').find("input[name^='date_']").val();

        var amount = 0;
        var amountContainer = $(this).closest('div.form-row').find("input[name^='amount_']");
        var mode_of_travel = $(this).closest('div.form-row').find("select[name^='mode_of_travel_']").val();
        var totalAmount = totalAmountContainer.val()-amountContainer.val();
        $.ajax({
            url: "{{URL::to('get/rate')}}/" + mode_of_travel + "/" + from,
            success:function(response){
                var res = JSON.parse(response);
                amount = kms*res[0].amount;
                amountContainer.val(amount.toFixed(0));
                totalAmount = parseInt(totalAmount)+ parseInt(amount);
                console.log(totalAmount);
                totalAmountContainer.val(totalAmount.toFixed(0));
            }
        });
    }
}).on('change', "select[name^='mode_of_travel_']",function(){
    var category = $('#'+$(this).closest('div.card').data('parent')+' option:selected').text();
    if(category == 'Fuel - Mileage (within city)' || category == 'Fuel - Mileage (out of city)'){
        var totalAmountContainer = $('#total_'+$(this).closest('div.card').data('parent'));
        var kmsContainer = $(this).closest('div.form-row').find("input[name^='kms_']");
        var amountContainer = $(this).closest('div.form-row').find("input[name^='amount_']");
        var totalAmount = totalAmountContainer.val()-amountContainer.val();
        kmsContainer.val(0);
        amountContainer.val(0);
        totalAmount = parseInt(totalAmount);
        totalAmountContainer.val(totalAmount.toFixed(0));
    }
}).on('change', "input[name^='rate_per_litre_']", function(){
    var amountPaid = $(this).closest('div.form-row').find("input[name^='amount_paid_']").val();
    var ratePerLitre = $(this).val();
    var litres = amountPaid/ratePerLitre;
    $(this).closest('div.form-row').find("input[name^='litres_']").val(litres.toFixed(2))
}).on('change', "input[name^='amount_paid_']", function(){
    var category = $('#'+$(this).closest('div.card').data('parent')+' option:selected').text();
    if(category == 'Fuel - Receipts' || category == 'Local - Transport'){
        var totalAmountContainer = $('#total_'+$(this).closest('div.card').data('parent'));
        var amountContainer = $(this).closest('div.form-row').find("input[name^='amount_']");
        var amountPaidContainer = $(this).closest('div.form-row').find("input[name^='amount_paid_']");

        var totalAmount = totalAmountContainer.val()-amountContainer.val();
        var amount = amountPaidContainer.val();
        amountContainer.val(parseInt(amount).toFixed(0));
        totalAmount = parseInt(totalAmount)+ parseInt(amount);
        totalAmountContainer.val(totalAmount.toFixed(0));

        if(category == 'Fuel - Receipts'){
            var ratePerLitre = $(this).closest('div.form-row').find("input[name^='rate_per_litre_']").val();
            var amountPaid = $(this).val();
            if(ratePerLitre > 0){
                var litres = amountPaid/ratePerLitre;
                $(this).closest('div.form-row').find("input[name^='litres_']").val(litres)
            }
        }
    }
    else if(category == 'Meal - Entertainment' || category == 'Local - Toll tax / Parking charges / E-Tag' || category == 'Misc' || category == 'Local - Toll tax / Parking charges / E-Tag (out of city)'){
        var totalAmountContainer = $('#total_'+$(this).closest('div.card').data('parent'));
        var amountContainer = $(this).closest('div.form-row').find("input[name^='amount_']");
        var amountPaidContainer = $(this).closest('div.form-row').find("input[name^='amount_paid_']");
        var totalAmount = totalAmountContainer.val()-amountContainer.val();
        var amount = amountPaidContainer.val();

        amountContainer.val(parseInt(amount).toFixed(0));
        totalAmount = parseInt(totalAmount)+ parseInt(amount);
        totalAmountContainer.val(totalAmount.toFixed(0));
    }

    else if(category == 'Local - Hotel Stay'){
        var amountToBePaidContainer = $(this).closest('div.form-row').find("input[name^='amount_to_be_paid_']");
        var amountPaidContainer = $(this);
        var amountContainer = $(this).closest('div.form-row').find("input[name^='amount_']");
        var totalAmountContainer = $('#total_'+$(this).closest('div.card').data('parent'));
        var diffDays = $(this).closest('div.form-row').find("input[name^='duration_']").val();
        var from = $(this).closest('div.form-row').find("input[name^='date_range_']").val().split("-");

        var totalAmount = totalAmountContainer.val()-amountContainer.val();
        var amount = $(this).val();
        var allowedAmount = 0;

        $.ajax({
            url: "{{URL::to('get/rate')}}/" + category + "/" + from[0].trim(),
            success:function(response){
                var res = JSON.parse(response);
                allowedAmount = diffDays*res[0].amount;
                if(allowedAmount <= amount){
                    amountContainer.val(allowedAmount.toFixed(0));
                    amountToBePaidContainer.val(allowedAmount.toFixed(0));
                    totalAmount = parseInt(totalAmount)+ parseInt(allowedAmount);
                    totalAmountContainer.val(totalAmount);
                    amountPaidContainer.val(amount);
                }
                else{
                    amountContainer.val(amount);
                    amountToBePaidContainer.val(amount);
                    totalAmount = parseInt(totalAmount)+ parseInt(amount);
                    totalAmountContainer.val(totalAmount);
                    amountPaidContainer.val(amount);
                }
            }
        })
    }
}).on('focusout', "input[name^='kms_']", function(){
    var category = $('#'+$(this).closest('div.card').data('parent')+' option:selected').text();
    var totalAmountContainer = $('#total_'+$(this).closest('div.card').data('parent'));
    console.log(category);
    if(category == 'Local - DA'){
        var kms = $(this).val();
        if (kms != ''){
            var isHalfed = 0;
            var daDays = 0;
            var amount = 0;
            if(kms >= 100){
                var tripDuration = ($(this).closest('div.form-row').find("input[name^='date_range_']").val()).split("-");
                var amountContainer = $(this).closest('div.form-row').find("input[name^='amount_']");
                var dateFrom = new Date((tripDuration[0]).trim());
                var dateTo = new Date((tripDuration[1]).trim());
                var timeDiff = Math.abs(dateTo.getTime() - dateFrom.getTime());


                var from = $.format.date(new Date(tripDuration[0].split(' ')[0]), "yyyy-MM-dd");
                var timeFrom = dateFrom.toLocaleTimeString();
                var timeTo = dateTo.toLocaleTimeString();

                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
                var diffTime = Math.ceil(timeDiff / (1000 * 3600));

                console.log(from);
                if(diffDays == 1){
                    if(dateFrom.getDate() == dateTo.getDate()){
                        if(dateFrom.getHours() >= 0 && dateTo.getHours() <= 16) {
                            if (diffTime >= 4 && diffTime < 8) {
                                daDays = 0.5;
                            }
                            else if (diffTime >= 8 && diffTime <= 24) {
                                daDays = 1;
                            }
                        }
                        else if(dateFrom.getHours() >= 16 && dateTo.getHours() <= 23){
                            daDays = 0.5;
                        }
                        else{
                            daDays = 1;
                        }
                    }
                    else{
                        if(dateFrom.getHours() >= 16 && dateTo.getHours() < 4){
                            if(diffTime >= 4){
                                daDays = 0.5;
                            }
                        }
                        else{
                            if(diffTime >= 4 && diffTime < 8){
                                daDays = 0.5;
                            }
                            else if(diffTime >= 8){
                                daDays = 1;
                            }
                        }
                    }
                }
                else{
                    if(dateFrom.getHours() >= 0 && dateTo.getHours() <= 16) {
                        if(dateTo.getMinutes() == 30 && dateTo.getHours() == 16){
                            daDays = diffDays;
                        }
                        else{
                            daDays = diffDays-0.5;
                        }
                    }
                    else if(dateFrom.getHours() >= 16 && dateTo.getHours() <= 23){
                        daDays = diffDays-0.5;
                    }
                    else{
                        daDays = diffDays;
                    }
                }
                var totalAmount = totalAmountContainer.val()-amountContainer.val();

                if(diffDays >= 1){
                    $.ajax({
                        url:"{{URL::to('get/rate')}}/" + category + "/" + from,
                        success:function(response){
                            var res = JSON.parse(response);
                            amount = daDays*res[0].amount;
                            amountContainer.val(amount);
                            totalAmount = parseInt(totalAmount)+ parseInt(amount);
                            totalAmountContainer.val(totalAmount);
                        }
                    });

                    $(this).closest('div.form-row').find("input[name^='da_eligible_period_']").val(daDays);
                }
            }
            else{
                alert('D.A not allowed. Minimum distance eligible for D.A is 100 Kms round trip');
                $(this).closest('div.form-row').find("input[name^='da_eligible_period_']").val('');
                $(this).closest('div.form-row').find("input[name^='amount_']").val('');
            }
        }
    }
}).on('change', "select[name^=accomodation_food_]", function(){
    var category = $('#'+$(this).closest('div.card').data('parent')+' option:selected').text();
    if(category == 'Foreign - DA'){
        var amount = 0;
        var accomodation_food = $(this).val();
        var tripDuration = ($(this).closest('div.form-row').find("input[name^='date_range_']").val()).split("-");
        var personalDays = $(this).closest('div.form-row').find("input[name^='personal_days_']").val();
        var dateFrom = new Date((tripDuration[0]).trim());
        var dateTo = new Date((tripDuration[1]).trim());
        var timeDiff = Math.abs(dateTo.getTime() - dateFrom.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

        var from = $.format.date(new Date(tripDuration[0].split(' ')[0]), "yyyy-MM-dd");

        if (personalDays < diffDays ){
            diffDays = diffDays - personalDays;
            var diffTime = Math.ceil(timeDiff / (1000 * 3600));
            var amountContainer = $(this).closest('div.form-row').find("input[name^='amount_']");

            console.log(accomodation_food);
            if(accomodation_food == 'Own Accomodation'){
                $(this).closest('div.form-row').find("input[name^='da_eligible_period_']").val((diffDays).toFixed(1));
            }
            else if(accomodation_food == 'Accomodation by Host'){
                $(this).closest('div.form-row').find("input[name^='da_eligible_period_']").val((diffDays/2).toFixed(1));
            }
            else if(accomodation_food == 'Both Accomodation & Food by Host'){
                $(this).closest('div.form-row').find("input[name^='da_eligible_period_']").val((diffDays/3).toFixed(1));
            }
            var eligible_period = $(this).closest('div.form-row').find("input[name^='da_eligible_period_']").val();
            $.ajax({
                url: "{{URL::to('get/rate')}}/" + category + "/" + from,
                success:function(response){
                    var res = JSON.parse(response);
                    amount = eligible_period*res[0].amount;
                    amountContainer.val(amount);
                }
            })
        }
        else{
            alert('Personal days must be less than DA days');
            $(this).closest('div.form-row').find("input[name^='personal_days_']").val(0)
        }

    }
}).on('change', "input[name^=date_range_]", function(){
    var category = $('#'+$(this).closest('div.card').data('parent')+' option:selected').text();
    if(category == 'Local - Hotel Stay'){
        var durationContainer = $(this).closest('div.form-row').find("input[name^='duration_']");

        var tripDuration = ($(this).closest('div.form-row').find("input[name^='date_range_']").val()).split("-");
        var dateFrom = new Date((tripDuration[0]).trim());
        var dateTo = new Date((tripDuration[1]).trim());
        var timeDiff = Math.abs(dateTo.getTime() - dateFrom.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        diffDays = diffDays+1;
        durationContainer.val(diffDays);
    }
}).on('change', "input[name^=forex_amount_]", function(){
    var category = $('#'+$(this).closest('div.card').data('parent')+' option:selected').text();
    if(category == 'Foreign - Expenses'){
        var amountContainer = $(this).closest('div.form-row').find("input[name^='amount_']");

        var totalAmountContainer = $('#total_'+$(this).closest('div.card').data('parent'));
        var totalAmount = totalAmountContainer.val()-amountContainer.val();
        var amount = $(this).val();
        amountContainer.val(amount);
        totalAmount = parseInt(totalAmount)+ parseInt(amount);
        totalAmountContainer.val(totalAmount);
    }
}).on('change', "select[name^=description_]", function(){
    var category = $('#'+$(this).closest('div.card').data('parent')+' option:selected').text();
    if(category == 'Meal - Out of office'){
        var amountContainer = $(this).closest('div.form-row').find("input[name^='amount_']");
        var description = $(this).closest('div.form-row').find("select[name^='description_']").val();
        var totalAmountContainer = $('#total_'+$(this).closest('div.card').data('parent'));
        var amount = 0;

        var from = $(this).closest('div.form-row').find("select[name^='date_']").val();
        var totalAmount = totalAmountContainer.val()-amountContainer.val();
        $.ajax({
            url: "{{URL::to('get/rate')}}/" + description + "/" + from,
            success:function(response){
                var res = JSON.parse(response);
                amount = res[0].amount;
                amountContainer.val(amount);
                totalAmount = parseInt(totalAmount)+ parseInt(amount);
                totalAmountContainer.val(totalAmount.toFixed(0));
            },
            error:function(response){
                console.log(response);
            }
        });

    }
}).on('click', '#removeCategory', function(){
    if(confirm('Are you sure you want to remove this ?')){
        var categoryContainer = $(this).closest('div.form-row');
        var categoryItemsContainer = $(this).closest('div.form-row').find("select[name^='voucher_categories_'] option:selected");
        $('#'+categoryItemsContainer.data('view')).remove();
        categoryContainer.remove();
    }

}).on('click', '#removeCategoryItem', function(){
    if(confirm('Are you sure you want to remove this ?')){
        var categoryItemContainer = $(this).closest('div.form-row');
        var totalAmountContainer = $('#total_'+$(this).closest('div.card').data('parent'));
        var amountContainer = $(this).closest('div.form-row').find("input[name^='amount_']");

        var totalAmount = totalAmountContainer.val() - amountContainer.val();
        totalAmountContainer.val(totalAmount);
        categoryItemContainer.remove();
    }
}).on('change', '.datetimeOutOfLahore', function(){
    var duration = ($(this).closest('div.form-row').find("input[name^='date_range_']").val()).split("-");
    var dateFrom = new Date((duration[0]).trim());
    var dateTo = new Date((duration[1]).trim());

    var timeDiff = Math.abs(dateTo.getTime() - dateFrom.getTime());
    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
});

$('#chargeTo').on('change', function(){
    if($(this).val() == 'Cost Center'){
        $('#costCenter').removeAttr('hidden');
        $('.costCenter').attr('required', true);
        $('.costCenter').addClass('select2');
        $('.select2').select2();
        $('#orderNumber').attr('hidden', true);
        $('#orderNumber').removeAttr('required');
    }
    else if($(this).val() == 'Order'){
        $('.costCenter').removeAttr('required');
        $('.costCenter').next('.select2-container').hide();
        $('#costCenter').attr('hidden', true);
        $('#orderNumber').removeAttr('hidden');
        $('#orderNumber').attr('required', true);
    }
    else{
        $('.costCenter').removeAttr('required');
        $('.costCenter').next('.select2-container').hide();
        $('#costCenter').attr('hidden', true);
        $('#orderNumber').removeAttr('hidden');
        $('#orderNumber').attr('hidden', true);
    }
});

$('#addCategory').click(function(evt){
    evt.preventDefault();
    if($('#voucher_categories_'+categoriesCount).val() == ""){
        alert('You cannot add more categories without completing the first category');
    }
    else{
        categoriesCount = categoriesCount+1;
        $('#categoriesCount').val(categoriesCount);
        $.ajax({
            url: "{{ URL::to('voucher/category/add') }}/" + categoriesCount,
            success:function (response){
                $("#rowContainer").append(JSON.parse(response).row);
                $('.select2').select2({
                    tags: true
                });
            }
        });
    }
});

$('#voucher-form').on('keypress', function(e){
    var key = e.charCode || e.keyCode || 0;
    if(key==13){
        e.preventDefault();
    }
})