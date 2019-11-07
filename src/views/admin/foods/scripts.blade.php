<script>

    var option_element = '<div class="row optionExtraRow1" id="option1_1">' +
        '<div class="col-md-1">' +
        '<label style="margin-bottom: 0;" class="form-group" for="from">Extras</label>' +
        '</div>' +
        '<div class="col-md-2" style=" ">' +
        '<select name="multi_size_extra_id[]" class="form-control option_extra" id="option_extra1_1">' +
        '<option value="">All</option>' +
        '<option value="1">Extra</option>' +
        '<option value="2">Extra</option>' +
        '<option value="3">Extra</option>' +
        '</select>' +
        '</div>' +
        '<div class="col-md-1">' +
        '<label style="margin-bottom: 0;" class="form-group" for="from">+ Price</label>' +
        '</div>' +
        '<div class="col-md-2" style="">' +
        '<input type="text" name="option_extra_price[]" id="option_extra_price1_1" class="form-control option_price">' +
        '</div>' +
        '<div class="col-md-1">' +
        '<label for="">Optional?</label>' +
        '</div>' +
        '<div class="col-md-1">' +
        '<input type="checkbox" style="margin-top: 6px;" class="form-control optional" value="1" checked="checked" name="option_is_optional[]" id="multi_size_checked_optional1_1">' +
        '<input id="option_unchecked_optional1_1" class="un_optional" type="hidden" disabled value="0" name="option_is_optional[]">' +
        '</div>' +
        '<div class="col-md-1">' +
        '<button class="btn btn-danger removeBTN" style="float: right;" type="button" id="option_remove1_1"><i class="fa fa-minus" aria-hidden="true"></i></button></div></div>'


    var extra_element = '<div class="row multiSizeExtraRow1" id="multisize1_1">' +
        '<div class="col-md-1">' +
        '<label style="margin-bottom: 0;" class="form-group" for="from">Extras</label>' +
        '</div>' +
        '<div class="col-md-2" style=" ">' +
        '<select name="multi_size_extra_id[]" class="form-control multi_extra get_extra_price"  id="multi_size_extra1_1">' +
        '<option value="">All</option>' +
        '<option value="1">Extra</option>' +
        '<option value="2">Extra</option>' +
        '<option value="3">Extra</option>' +
        '</select>' +
        '</div>' +
        '<div class="col-md-1">' +
        '<label style="margin-bottom: 0;" class="form-group" for="from">+ Price</label>' +
        '</div>' +
        '<div class="col-md-2" style="">' +
        '<input type="text" name="multi_size_extra_price[]" id="multi_size_extra_price1_1" class="form-control multi_price">' +
        '</div>' +
        '<div class="col-md-1">' +
        '<label for="">Optional?</label>' +
        '</div>' +
        '<div class="col-md-1">' +
        '<input type="checkbox" style="margin-top: 6px;" class="form-control optional" value="1" checked="checked" name="multi_size_is_optional[]" id="multi_size_checked_optional1_1">' +
        '<input id="multi_size_unchecked_optional1_1" class="un_optional" type="hidden" disabled="disabled" value="0" name="multi_size_is_optional[]">' +
        '</div>' +
        '<div class="col-md-1">' +
        '<button class="btn btn-danger removeBTN" style="float: right;" type="button" id="multisize_remove1_1"><i class="fa fa-minus" aria-hidden="true"></i></button></div></div>'

    $(document).on('change','.get_extra_price',function(e){
        var attr_id = $(this).attr('id');
        var value = $(this).val();
        var type_selected = attr_id.replace(/[0-9]/g, '');
        if(type_selected == 'one_size_extra'){
            var parent = $('#'+attr_id).closest('.oneSizeExtraRow').attr('id');
            var parent_num = parent.match(/\d+/);
            type_id = 'one_size_extra_price'+parent_num;
            extraPrice(value,type_id);
        }else if(type_selected == 'multi_size_extra_'){
            var parent = $('#'+attr_id).closest('.multiSizeRow').attr('id');
            var parent_num = parent.match(/\d+/);
            var attr = attr_id.match(/\d+$/)[0];
            type_id = 'multi_size_extra_price'+parent_num+'_'+attr;
            extraPrice(value,type_id);
        }else if(type_selected == 'option_extra_'){
            var parent = $('#'+attr_id).closest('.optionRow').attr('id');
            var parent_num = parent.match(/\d+/);
            var attr = attr_id.match(/\d+$/)[0];
            type_id = 'option_extra_price'+parent_num+'_'+attr;
            extraPrice(value,type_id);
        }
    });
    function extraPrice(id,type_id){
        var price = 0;
          $.ajax({
            method: 'GET',
            url: '{!! route('extraPrice') !!}',
            data: {'extra_id': id},
            success: function (response) {
                $('#'+type_id).val(response);
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
          return price;
    }
    $('#add_m_size').on('click', function (e) {
        var attr_id = $('.multiSizeRow:last').attr('id');
        var attr_num = attr_id.match(/\d+/);
        var size_value = $('#' + attr_id + ' #multi_size_' + attr_num).val();
        var size_price = $('#' + attr_id + ' #multi_size_price' + attr_num).val();
        if (size_value == null || size_value == '' || size_price == null || size_price == '') {
        } else {
            var newClone = $('#' + attr_id).clone();
            newClone.appendTo('#multi_size_field');
            var contentTypeInput = $('.multiSizeRow:last');
            var cTypeIncrementNum = parseInt(contentTypeInput.prop('id').match(/\d+/g), 10) + 1;
            contentTypeInput.attr('id', 'multi_size' + cTypeIncrementNum);
            $('#multi_size' + cTypeIncrementNum + ' #multi_size_' + attr_num).attr('id', 'multi_size_' + cTypeIncrementNum);
            $('#multi_size'+cTypeIncrementNum+' #multi_size_'+cTypeIncrementNum).append('<option value="" selected>Choose Size</option><option value="small">Small</option><option value="medium">Medium</option><option value="large">Large</option>');


            $('#multi_size' + cTypeIncrementNum + ' #multi_size_price' + attr_num).attr('id', 'multi_size_price' + cTypeIncrementNum);
            $('#multi_size' + cTypeIncrementNum + ' #multi_size_note' + attr_num).attr('id', 'multi_size_note' + cTypeIncrementNum);
            $('#multi_size' + cTypeIncrementNum + ' #multi_size_extra' + attr_num).attr('id', 'multi_size_extra' + cTypeIncrementNum);

            $('#multi_size' + cTypeIncrementNum + ' .multiSizeExtraRow' + attr_num).remove();
            var last_extra_option = $('#multi_size' + attr_num + ' #multi_size_extra' + attr_num+'_1').html();
            $('#multi_size' + cTypeIncrementNum).append(extra_element);
            $('#multi_size' + cTypeIncrementNum + ' #multi_size_' + cTypeIncrementNum).val('');
            // $('#multi_size' + cTypeIncrementNum + ' #multi_size_' + cTypeIncrementNum + ' option[value=' + size_value + ']').remove();
            $('#multi_size' + cTypeIncrementNum + ' #multi_size_price' + cTypeIncrementNum).val('');
            $('#multi_size' + cTypeIncrementNum + ' #multi_size_note' + cTypeIncrementNum).val('');
            $('#multi_size' + cTypeIncrementNum + ' .multiSizeExtraRow1').attr('class', 'multiSizeExtraRow' + cTypeIncrementNum);
            $('#multi_size' + cTypeIncrementNum + ' .multiSizeExtraRow' + cTypeIncrementNum).attr('id', 'multisize' + cTypeIncrementNum + '_1');
            $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1').addClass('row');
            $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_extra1_1').attr('id', 'multi_size_extra' + cTypeIncrementNum + '_1');

            $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_extra_price1_1').attr('id', 'multi_size_extra_price' + cTypeIncrementNum + '_1');
            $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_checked_optional1_1').attr('id', 'multi_size_checked_optional' + cTypeIncrementNum + '_1');
            $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_unchecked_optional1_1').attr('id', 'multi_size_unchecked_optional' + cTypeIncrementNum + '_1');
            $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multisize_remove1_1').attr('id', 'multisize_remove' + cTypeIncrementNum + '_1');
            $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_extra' + cTypeIncrementNum + '_1').empty();
            $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_extra' + cTypeIncrementNum + '_1').append(last_extra_option);
            var arr = $('.multi_size').map(function(){
                          return this.value
                      }).get();
            arr.clean("");
            $.each(arr,function(index,value){
                $('#multi_size' + cTypeIncrementNum + ' #multi_size_' + cTypeIncrementNum + ' option[value=' + value + ']').remove();
            });
            $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_extra' + cTypeIncrementNum + '_1').val('');

            $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_extra_price' + cTypeIncrementNum + '_1').val('');




        //     var newClone = $('#' + attr_id).clone();
        //     newClone.appendTo('#multi_size_field');
        //     var contentTypeInput = $('.multiSizeRow:last');
        //     var cTypeIncrementNum = parseInt(contentTypeInput.prop('id').match(/\d+/g), 10) + 1;
        //     contentTypeInput.attr('id', 'multi_size' + cTypeIncrementNum);
        //     $('#multi_size' + cTypeIncrementNum + ' #multi_size_' + attr_num).attr('id', 'multi_size_' + cTypeIncrementNum);
        //     $('#multi_size' + cTypeIncrementNum + ' #multi_size_price' + attr_num).attr('id', 'multi_size_price' + cTypeIncrementNum);
        //     $('#multi_size' + cTypeIncrementNum + ' #multi_size_note' + attr_num).attr('id', 'multi_size_note' + cTypeIncrementNum);
        //     $('#multi_size'+cTypeIncrementNum+' #multi_size_note'+cTypeIncrementNum).val("");
        //     $('#multi_size'+cTypeIncrementNum+' #multi_size_'+cTypeIncrementNum).append('<option value="" selected>Choose Size</option><option value="small">Small</option><option value="medium">Medium</option><option value="large">Large</option>');
        //     // button add multi_size_extra
        //     $('#multi_size' + cTypeIncrementNum + ' #multi_size_extra' + attr_num).attr('id', 'multi_size_extra' + cTypeIncrementNum);
          
        //     $('#multi_size' + cTypeIncrementNum + ' #multi_size_' + cTypeIncrementNum).val('');

        //     $('#multi_size' + cTypeIncrementNum + ' #multi_size_' + cTypeIncrementNum + ' option[value=' + size_value + ']').remove();

        //     $('#multi_size' + cTypeIncrementNum + ' #multi_size_price' + cTypeIncrementNum).val('');

        //     $('#multi_size' + cTypeIncrementNum + ' #multi_size_note' + cTypeIncrementNum).val('');
        //     $('#multi_size' + cTypeIncrementNum + ' #multi_size_note' + cTypeIncrementNum).attr('name','multi_size_note');

        //     $('#multi_size' + cTypeIncrementNum + ' .multiSizeExtraRow1').attr('class', 'multiSizeExtraRow' + cTypeIncrementNum);
        //     $('#multi_size' + cTypeIncrementNum + ' .multiSizeExtraRow' + cTypeIncrementNum).attr('id', 'multisize' + cTypeIncrementNum + '_1');
        //     var count = $('#multi_size'+cTypeIncrementNum+' .multiSizeExtraRow'+cTypeIncrementNum).length;
        //     console.log(count);
        //     $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1').addClass('row');

        //     $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_extra1_1').attr('id', 'multi_size_extra' + cTypeIncrementNum + '_1');
        //     $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_extra'+cTypeIncrementNum+'_1').attr('name', 'multi_size_extra_id');


        //     $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_extra_price1_1').attr('id', 'multi_size_extra_price' + cTypeIncrementNum + '_1');
        //     $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_extra_price'+cTypeIncrementNum+'_1').attr('name', 'multi_size_extra_price');


        //     $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_checked_optional1_1').attr('id', 'multi_size_checked_optional' + cTypeIncrementNum + '_1');
        //     $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_checked_optional'+cTypeIncrementNum+'_1').attr('name', 'multi_size_is_optional');

        //     $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_unchecked_optional1_1').attr('id', 'multi_size_unchecked_optional' + cTypeIncrementNum + '_1');

        //     $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_unchecked_optional'+cTypeIncrementNum+'_1').attr('name', 'multi_size_is_optional');
        //     $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multisize_remove1_1').attr('id', 'multisize_remove' + cTypeIncrementNum + '_1');
        //     $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_extra' + cTypeIncrementNum + '_1').val('');
        //     $('#multi_size' + cTypeIncrementNum + ' #multisize' + cTypeIncrementNum + '_1 #multi_size_extra_price' + cTypeIncrementNum + '_1').val('');
        //     $('#multi_size' + cTypeIncrementNum + ' #multi_size_' + cTypeIncrementNum).attr('readonly', false);

        }
    });
    $(document).on('click', '.add_m_extra_row', function () {
        var attr_id = $(this).attr('id');
        var attr_num = attr_id.match(/\d+/);
        var parent_id = $('#multi_size' + attr_num);
        if($('#multi_size' + attr_num + ' .multiSizeExtraRow' + attr_num + ':last').length>0){
        }else{
            $('#multi_size'+attr_num).append(extra_element);
        }
        var getDiv = $('#multi_size' + attr_num + ' .multiSizeExtraRow' + attr_num + ':last').attr('id');
        var div_id_num = getDiv.match(/\d+$/)[0];
        var extra_value = $('#multi_size' + attr_num + ' .multi_extra:last').val();
        var extra_price = $('#multi_size' + attr_num + ' .multi_price:last').val();

        if (extra_value == null || extra_value == '' || extra_price == null || extra_price == '') {
        } else {
            var newClone = $('#multi_size' + attr_num + ' #' + getDiv).clone();
            newClone.appendTo('#multi_size' + attr_num);
            var contentTypeInput = $('#multi_size' + attr_num + ' .multiSizeExtraRow' + attr_num + ':last');
            var cTypeIncrementNum = +div_id_num + +1;
            $('#multi_size' + attr_num + ' .multiSizeExtraRow' + attr_num + ':last').attr('id', 'multisize' + attr_num + '_' + cTypeIncrementNum);
            $('#multisize' + attr_num + '_' + cTypeIncrementNum + ' #multi_size_extra' + attr_num + '_' + div_id_num).attr('id', 'multi_size_extra' + attr_num + '_' + cTypeIncrementNum);
            $('#multisize' + attr_num + '_' + cTypeIncrementNum + ' #multi_size_extra_price' + attr_num + '_' + div_id_num).attr('id', 'multi_size_extra_price' + attr_num + '_' + cTypeIncrementNum);
            $('#multisize' + attr_num + '_' + cTypeIncrementNum + ' #multi_size_checked_optional' + attr_num + '_' + div_id_num).attr('id', 'multi_size_checked_optional' + attr_num + '_' + cTypeIncrementNum);
            $('#multisize' + attr_num + '_' + cTypeIncrementNum + ' #multi_size_unchecked_optional' + attr_num + '_' + div_id_num).attr('id', 'multi_size_unchecked_optional' + attr_num + '_' + cTypeIncrementNum);
            $('#multisize' + attr_num + '_' + cTypeIncrementNum + ' #multisize_remove' + attr_num + '_' + div_id_num).attr('id', 'multisize_remove' + attr_num + '_' + cTypeIncrementNum);
            $('#multi_size_extra' + attr_num + '_' + cTypeIncrementNum).val('');
            $('#multi_size_extra_price' + attr_num + '_' + cTypeIncrementNum).val('');
            $('#multi_size_extra' + attr_num + '_' + cTypeIncrementNum + ' option[value=' + extra_value + ']').remove();
        }
    });
    $(document).on('change', '.optional', function (e) {
        var attr_id = $(this).attr('id');
        var attr_num = attr_id.match(/\d+$/)[0];
        var type_selected = attr_id.replace(/[0-9]/g, '');
        if (type_selected == 'option_checked_optional_') {
            var parent_div = $('#' + attr_id).closest('.optionRow').attr('id');
            var parent_num = parent_div.match(/\d+/);
            if (!$(this).is(':checked')) {
                $('#option_unchecked_optional' + parent_num + '_' + attr_num).attr('disabled', false);
            } else {
                $('#option_unchecked_optional' + parent_num + '_' + attr_num).attr('disabled', true);
            }
        } else if (type_selected == 'multi_size_checked_optional_') {
            var parent_div = $('#' + attr_id).closest('.multiSizeRow').attr('id');
            var parent_num = parent_div.match(/\d+/);
            if (!$(this).is(':checked')) {
                $('#multi_size_unchecked_optional' + parent_num + '_' + attr_num).attr('disabled', false);
            } else {
                $('#multi_size_unchecked_optional' + parent_num + '_' + attr_num).attr('disabled', true);
            }
        } else if (type_selected == 'one_size_checked_optional') {
            if (!$(this).is(':checked')) {
                $('#one_size_unchecked_optional' + attr_num).attr('disabled', false);
                // alert(attr_num);
                // console.log(attr_id);
                // alert($('#one_size_unchecked_optional'+attr_num).val());
            } else {
                $('#one_size_unchecked_optional' + attr_num).attr('disabled', true);
            }
        }
    });
    $(document).on('change', '.multi_size', function (e) {
        var value = $(this).val();
        var attr_id = $(this).attr('id');
        var attr_num = attr_id.match(/\d+/);
        $('.multiSizeExtraRow' + attr_num).each(function () {
            $('.multiSizeExtraRow' + attr_num + ' .multi_extra').each(function () {
                $(this).attr("name", 'multi_size_extra_id-' + value + '[]');
            });
            $('#multi_size_note' + attr_num).attr("name", 'multi_size_note-' + value);
            $('.multiSizeExtraRow' + attr_num + ' .multi_price').each(function () {
                $(this).attr("name", 'multi_size_extra_price-' + value + '[]');
            });
            $('.multiSizeExtraRow' + attr_num + ' .optional').each(function () {
                $(this).attr("name", 'multi_size_is_optional-' + value + '[]');
            });
            $('.multiSizeExtraRow' + attr_num + ' .un_optional').each(function () {
                $(this).attr("name", 'multi_size_is_optional-' + value + '[]');
            });
        });
        $('#' + attr_id).attr('readonly', true);
    });
    $(document).on('click', '.removeBTN', function (e) {
        var attr_id = $(this).attr('id');
        var attr_num = attr_id.match(/\d+$/)[0];
        var type_selected = attr_id.replace(/[0-9]/g, '');
        if (type_selected == 'option_remove_') {
            var parent_div = $('#' + attr_id).closest('.optionRow').attr('id');
            var parent_num = parent_div.match(/\d+/);
            var numItems = $('.option_price').length;
            if (numItems > 1) {
                $('#' + parent_div + ' #option' + parent_num + '_' + attr_num).remove();
            }
        } else if (type_selected == 'multisize_remove_') {
            var parent_div = $('#' + attr_id).closest('.multiSizeRow').attr('id');
            var parent_num = parent_div.match(/\d+/);
            var numItems = $('.multi_price').length;
            if (numItems > 1) {
                var item_to_add = $('#' + parent_div + ' #multisize' + parent_num + '_' + attr_num + ' .multi_extra').val();
                console.log(item_to_add);
                $('#' + parent_div + ' #multisize' + parent_num + '_' + attr_num).remove();
            }
        } else if (type_selected == 'onesize_remove') {
            var parent_div = $('#' + attr_id).closest('.oneSizeExtraRow').attr('id');
            var parent_num = parent_div.match(/\d+/);
            var numItems = $('.oneSizeExtraRow').length;
            if (numItems > 1) {
                $('#' + parent_div).remove();
            }
        }
    });
    $(document).on('click', '.removeMultiRow', function (e) {
        var attr_id = $(this).closest('div[id]').attr('id');
        var class_name = $('#' + attr_id).attr('class');
        if ($('#' + attr_id).hasClass('multiSizeRow')) {
            if ($('.multiSizeRow').length > 1) {
                $('#' + attr_id).remove();
            }
        } else if ($('#' + attr_id).hasClass('optionRow')) {
            if ($('.optionRow').length > 1) {
                $('#' + attr_id).remove();
            }
        }
    });
    $('#add_option').on('click', function (e) {
        var attr_id = $('.optionRow:last').attr('id');
        var attr_num = attr_id.match(/\d+/);
        var option_value = $('#' + attr_id + ' #option_name' + attr_num).val();
        var option_price = $('#' + attr_id + ' #option_price' + attr_num).val();
        if (option_value == null || option_value == '' || option_price == null || option_price == '') {
        } else {
            var newClone = $('#' + attr_id).clone();
            newClone.appendTo('#options_field');
            var contentTypeInput = $('.optionRow:last');
            var cTypeIncrementNum = parseInt(contentTypeInput.prop('id').match(/\d+/g), 10) + 1;
            contentTypeInput.attr('id', 'type_option' + cTypeIncrementNum);
            $('#type_option' + cTypeIncrementNum + ' #option_name' + attr_num).attr('id', 'option_name' + cTypeIncrementNum);
            $('#type_option' + cTypeIncrementNum + ' #option_price' + attr_num).attr('id', 'option_price' + cTypeIncrementNum);
            $('#type_option' + cTypeIncrementNum + ' #option_note' + attr_num).attr('id', 'option_note' + cTypeIncrementNum);
            $('#type_option' + cTypeIncrementNum + ' #add_option_extra' + attr_num).attr('id', 'add_option_extra' + cTypeIncrementNum);
            $('#type_option' + cTypeIncrementNum + ' .optionExtraRow' + attr_num).remove();
            $('#type_option' + cTypeIncrementNum).append(option_element);
            $('#type_option' + cTypeIncrementNum + ' #option_name' + cTypeIncrementNum).val('');
            $('#type_option' + cTypeIncrementNum + ' #option_price' + cTypeIncrementNum).val('');
            $('#type_option' + cTypeIncrementNum + ' #option_note' + cTypeIncrementNum).val('');
            $('#type_option' + cTypeIncrementNum + ' .optionExtraRow1').attr('class', 'optionExtraRow' + cTypeIncrementNum);
            $('#type_option' + cTypeIncrementNum + ' .optionExtraRow' + cTypeIncrementNum).attr('id', 'option' + cTypeIncrementNum + '_1');
            $('#type_option' + cTypeIncrementNum + ' #option' + cTypeIncrementNum + '_1').addClass('row');
            $('#type_option' + cTypeIncrementNum + ' #option' + cTypeIncrementNum + '_1 #option_extra1_1').attr('id', 'option_extra' + cTypeIncrementNum + '_1');
            $('#type_option' + cTypeIncrementNum + ' #option' + cTypeIncrementNum + '_1 #option_extra_price1_1').attr('id', 'option_extra_price' + cTypeIncrementNum + '_1');
            $('#type_option' + cTypeIncrementNum + ' #option' + cTypeIncrementNum + '_1 #option_checked_optional1_1').attr('id', 'option_checked_optional' + cTypeIncrementNum + '_1');
            $('#type_option' + cTypeIncrementNum + ' #option' + cTypeIncrementNum + '_1 #option_unchecked_optional1_1').attr('id', 'option_unchecked_optional' + cTypeIncrementNum + '_1');
            $('#type_option' + cTypeIncrementNum + ' #option' + cTypeIncrementNum + '_1 #option_remove1_1').attr('id', 'option_remove' + cTypeIncrementNum + '_1');

            $('#type_option' + cTypeIncrementNum + ' #option' + cTypeIncrementNum + '_1 #option_extra' + cTypeIncrementNum + '_1').val('');
            $('#type_option' + cTypeIncrementNum + ' #option' + cTypeIncrementNum + '_1 #option_extra_price' + cTypeIncrementNum + '_1').val('');
        }
    });
    $(document).on('click', '.add_option_row', function () {
        var attr_id = $(this).attr('id');
        var attr_num = attr_id.match(/\d+/);
        var parent_id = $('#type_option' + attr_num);
        var getDiv = $('#type_option' + attr_num + ' .optionExtraRow' + attr_num + ':last').attr('id');
        var div_id_num = getDiv.match(/\d+$/)[0];
        var option_value = $('#type_option' + attr_num + ' .option_extra:last').val();
        var option_price = $('#type_option' + attr_num + ' .option_price:last').val();
        if (option_value == null || option_value == '' || option_price == null || option_price == '') {
        } else {
            var newClone = $('#type_option' + attr_num + ' #' + getDiv).clone();
            newClone.appendTo('#type_option' + attr_num);
            var contentTypeInput = $('#type_option' + attr_num + ' .optionExtraRow' + attr_num + ':last');
            var cTypeIncrementNum = +div_id_num + +1;
            $('#type_option' + attr_num + ' .optionExtraRow' + attr_num + ':last').attr('id', 'option' + attr_num + '_' + cTypeIncrementNum);
            $('#option' + attr_num + '_' + cTypeIncrementNum + ' #option_extra' + attr_num + '_' + div_id_num).attr('id', 'option_extra' + attr_num + '_' + cTypeIncrementNum);
            $('#option' + attr_num + '_' + cTypeIncrementNum + ' #option_extra_price' + attr_num + '_' + div_id_num).attr('id', 'option_extra_price' + attr_num + '_' + cTypeIncrementNum);
            $('#option' + attr_num + '_' + cTypeIncrementNum + ' #option_checked_optional' + attr_num + '_' + div_id_num).attr('id', 'option_checked_optional' + attr_num + '_' + cTypeIncrementNum);
            $('#option' + attr_num + '_' + cTypeIncrementNum + ' #option_unchecked_optional' + attr_num + '_' + div_id_num).attr('id', 'option_unchecked_optional' + attr_num + '_' + cTypeIncrementNum);
            $('#option' + attr_num + '_' + cTypeIncrementNum + ' #option_remove' + attr_num + '_' + div_id_num).attr('id', 'option_remove' + attr_num + '_' + cTypeIncrementNum);
            $('#option_extra' + attr_num + '_' + cTypeIncrementNum).val('');
            $('#option_extra_price' + attr_num + '_' + cTypeIncrementNum).val('');
            $(' #option_extra' + attr_num + '_' + cTypeIncrementNum + ' option[value=' + option_value + ']').remove();
            // document.getElementById('#type_option' + cTypeIncrementNum).scrollIntoView();
        }
    });
    $(document).on('input', '.option_name', function (e) {
        var value = $(this).val();
        var attr_id = $(this).attr('id');
        var attr_num = attr_id.match(/\d+/);
        $('.optionExtraRow' + attr_num).each(function () {
            $('.optionExtraRow' + attr_num + ' .option_extra').each(function () {
                $(this).attr("name", 'option_extra_id-' + value + '[]');
            });
            $('.optionExtraRow' + attr_num + ' .option_price').each(function () {
                $(this).attr("name", 'option_extra_price-' + value + '[]');
            });
            $('.optionExtraRow' + attr_num + ' .optional').each(function () {
                $(this).attr("name", 'option_is_optional-' + value + '[]');
            });
            $('.optionExtraRow' + attr_num + ' .un_optional').each(function () {
                $(this).attr("name", 'option_is_optional-' + value + '[]');
            });
            $('#option_note' + attr_num).attr('name', 'option_note-' + value);
        });
    });
    $(document).on('click', '.add_one_size_extra', function () {
        var attr_id = $('.oneSizeExtraRow:last').attr('id');
        var attr_num = attr_id.match(/\d+/);
        var one_size_value = $('#' + attr_id + ' #one_size_extra' + attr_num).val();
        var one_size_price = $('#' + attr_id + ' #one_size_extra_price' + attr_num).val();

        if (one_size_value == null || one_size_value == '' || one_size_price == null || one_size_price == '') {
        } else {
            var newClone = $('#' + attr_id).clone();
            newClone.appendTo('#one_size_field');
            var contentTypeInput = $('.oneSizeExtraRow:last');
            var cTypeIncrementNum = +attr_num + +1;
            contentTypeInput.attr('id', 'onesize' + cTypeIncrementNum);
            $('#onesize' + cTypeIncrementNum + ' #one_size_extra_price' + attr_num).attr('id', 'one_size_extra_price' + cTypeIncrementNum);
            $('#onesize' + cTypeIncrementNum + ' #one_size_extra' + attr_num).attr('id', 'one_size_extra' + cTypeIncrementNum);
            $('#onesize' + cTypeIncrementNum + ' #one_size_checked_optional' + attr_num).attr('id', 'one_size_checked_optional' + cTypeIncrementNum);
            $('#onesize' + cTypeIncrementNum + ' #one_size_unchecked_optional' + attr_num).attr('id', 'one_size_unchecked_optional' + cTypeIncrementNum);
            $('#onesize' + cTypeIncrementNum + ' #onesize_remove' + attr_num).attr('id', 'onesize_remove' + cTypeIncrementNum);
            $('#onesize' + cTypeIncrementNum + ' #one_size_extra_price' + cTypeIncrementNum).val('');
            $('#onesize' + cTypeIncrementNum + ' #one_size_extra' + cTypeIncrementNum).val('');
            $('.one_size_extra').each(function(value){
                if($('.one_size_extra option[value="'+value+'"]').is(':selected')){
                    $('#onesize' + cTypeIncrementNum + ' #one_size_extra' + cTypeIncrementNum + ' option[value=' + value + ']').remove();
                }
            });
        }
    });
    $(document).on('change', '.food_types', function (e) {
        var value = $(this).val();
        $('#is_extra').attr('checked',false);
        $('#extra_price').css('display','none');
        if (value == 'one_size') {
            $('#one_size_field').css('display', 'block');
            $('#multi_size_field').css('display', 'none');
            $('#options_field').css('display', 'none');
            $('#m-add').css('display', 'none');
            $('#opt-add').css('display', 'none');
            $('#one_size_price').css('display', 'block');
        } else if (value == 'multi_sizes') {
            $('#one_size_field').css('display', 'none');
            $('#multi_size_field').css('display', 'block');
            $('#options_field').css('display', 'none');
            $('#m-add').css('display', 'block');
            $('#opt-add').css('display', 'none');
            $('#one_size_price').css('display', 'none');
        } else if (value == 'options') {
            $('#one_size_field').css('display', 'none');
            $('#multi_size_field').css('display', 'none');
            $('#options_field').css('display', 'block');
            $('#m-add').css('display', 'none');
            $('#opt-add').css('display', 'block');
            $('#one_size_price').css('display', 'none');
        }
    });
    $('#is_extra').on('change', function () {
        if ($(this).is(':checked')) {
            $('#extra_price').css('display','block');
            $('#one_size_field').css('display', 'none');
            $('#multi_size_field').css('display', 'none');
            $('#options_field').css('display', 'none');
            $('#m-add').css('display', 'none');
            $('#opt-add').css('display', 'none');
            $('#one_size_price').css('display', 'none');
            $('#one_size_type').attr('checked', false);
            $('#multi_size_type').attr('checked', false);
            $('#option_type').attr('checked', false);
        } else {
            $('#extra_price').css('display','none');
            $('#one_size_field').css('display', 'none');
            $('#one_size_price').css('display', 'none');
        }
    });
</script>