
<script type="text/javascript">
    var response_array=[];
    var items_array = <?php echo json_encode($products); ?>;
    var selected =[];
    var items_options=[];
    var checkout;


    $(document).on('input', '.promocode', function(e){
        var promocode = $(this).val();
        var token = $('#token').val();
        $.ajax({
            headers: {
                'token':token,
                'lang':'en',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'GET',
            url: '{!! route('getPromoCodeDetails') !!}',
            data: {'promocode' : promocode},
            success: function(response){
                if(!$('#promocode').val()) {
                    $('#validate').css('display','none');
                }else{
                    $('#validate').css('display','block');
                }
                // convert string to object in javascript
                var obj = jQuery.parseJSON(response);
                if(typeof obj.discount_rate !== "undefined"){
                    $('#promocode_response').val('Promocode is valid');
                }else{
                    if($('#users option:selected').val() == -1 ){
                        $('#promocode_response').val('Select User First');
                    }else{
                        $('#promocode_response').val('Invalid Code')
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                // console.log(JSON.stringify(jqXHR));
                // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    });

    $(document).ready(function() {
        grandTotalAmount();
        // $('#users').select2({
        //     matcher: matchCustom,
        //     templateResult: formatCustom
        // });
        $('#items1').select2({
            matcher: matchCustom,
            templateResult: formatCustom
        });
        if($('#items1').val()){
            $('#items1').attr('disabled',true);
        }

        // setInterval(promocode_ajax(token,promocode), 5000);
        userAddress();
    });

    function userAddress(){
        if($('#users').val() == null){
            return false;
        }

        var email = $('#users').val();
        <?php $address_url = url('/admin/address/create');?>
            <?php if (isset($order)) {
	$order_id = $order->id;
} else {
	$order_id = 0;
}
;?>;
        var order_id = <?php echo $order_id; ?>;
        $("#create_address").attr('href', '{{$address_url}}'+'?id='+email);
        $.ajax({
            method: 'GET',
            url: '{!! route('getUserAddress') !!}',
            data: {'id' : email,'order_id':order_id},
            success: function(response){
                if(response == 'false')
                {
                   alert(' user with id ('+email+') do not exist');
                }
                var cart =[];
                cart = response.food_cart;
                $('#cart_data').html(cart);
                usrAddresses = '';
                $.each(response.address, function(index,value) {

                    var optionExists = ($('#address option[value=' + value.id + ']').length > 0);
                    if(!optionExists){
                        if(response.selected_address != 0){
                            if(value.id == response.selected_address){
                                usrAddresses+='<option selected id="option-'+value.id+'"  value="' + value.id + '">' + value.address + '</option>';
                                //$('#address').append('<option selected id="option-'+value.id+'"  value="' + value.id + '">' + value.address + '</option>');
                            }else{
                                usrAddresses+='<option  id="option-'+value.id+'"  value="' + value.id + '">' + value.address + '</option>';
                                    //$('#address').append('<option  id="option-'+value.id+'"  value="' + value.id + '">' + value.address + '</option>');
                            }
                        }else{
                            usrAddresses+= '<option  id="option-'+value.id+'"  value="' + value.id + '">' + value.address + '</option>';
                            //$('#address').append('<option  id="option-'+value.id+'"  value="' + value.id + '">' + value.address + '</option>');
                        }
                    }
                });
                $('#address').html(usrAddresses);
                $('#district').val(response.district);
                $('#token').val(response.token);
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    }
    $('#users').on('change',function(){
        userAddress();
    });





    $(document).on('click','.updateCart',function(){
        var attr_id = $(this).closest('div[id]').attr('id');

        $('#'+attr_id+' .save_updates').css('display','block');
        $('#'+attr_id+ ' .updateCart').css('display','none');
        $('#'+attr_id+' .order_qty').attr('disabled',false);
        $('#'+attr_id+' .extra_qty').attr('disabled',false);
    });
    $(document).on('change','.change_qty',function(){
        var attr_id = $(this).closest('div[id]').attr('id');
        total = 0 ;
        var qty = parseInt($('#'+attr_id+' .order_qty').val());
        var rate = parseFloat($('#'+attr_id+' .order_rate').val());
        var total = qty*rate;
        $('#'+attr_id+' .extra_qty').each(function(){
            var extra_tr_num = $(this).attr('id').match(/\d+/);
            var extra_qty = parseInt($('#extra_qty'+extra_tr_num).val());
            var extra_rate = parseFloat($('#extra_rate'+extra_tr_num).val());
            var extra_amount = extra_qty * extra_rate;
            total += extra_amount;
        });
        $('#'+attr_id+' .total_cart_item').val(Math.round(total * 100) / 100+' LE');
    });

    $(document).on('click','.save_updates',function(){
        var attr_id = $(this).closest('div[id]').attr('id');
        $('#'+attr_id+' .order_qty').attr('disabled',true);
        $('#'+attr_id+' .extra_qty').attr('disabled',true);
        $('#'+attr_id+ ' .updateCart').css('display','block');
        $('#'+attr_id+' .save_updates').css('display','none');
        var object = $('#item'+attr_id).val();
        var object = JSON.parse(object);
        object.qty = $('#'+attr_id+' .order_qty').val();
        var extra_array = [];
        $("#"+attr_id+" .extra_id_value").each(function(){
            var extra_tr_num = $(this).attr('id').match(/\d+/);
            var extra_qty = $('#'+attr_id+' #extra_qty'+extra_tr_num).val();
            var extra_id = $('#'+attr_id+' #extra_id'+extra_tr_num).val();
            var extra_rate = $('#'+attr_id+' #extra_rate'+extra_tr_num).val();
            var extra_name = $('#'+attr_id+' #extra_name'+extra_tr_num).val();
            var extra_object = {'id':extra_id,'extra_name':extra_name,'qty':extra_qty,'accepted':1,'rate':extra_rate};
            extra_array.push(extra_object);
        });
        object.extras = extra_array;
        $('#item'+attr_id).val(JSON.stringify(object));
        grandTotalAmount();
    });




    $('#address').on('change',function(){
        var address_id = $(this).val();
        $.ajax({
            method: 'GET',
            url: '{!! route('getUserShippingRate') !!}',
            data: {'address_id' : address_id},
            success: function(response){
                // console.log(response.standard_rate);
                $('#shipping_rate').val('');
                $('#shipping_rate').val(response);
                grandTotalAmount();
            },
            error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    });


    // getting the grand total amount
    function grandTotalAmount(){
        var grand_total_amount=0;
        var idArray = [];
        $('.total_cart_item').each(function(value) {
            idArray.push(this.value);
        });
        idArray.clean("");
        $.each(idArray, function( index, value ) {
            grand_total_amount += parseFloat(value);
        });
        shipping_rule_rate = $('#shipping_rate').val();
        grand_total_amount +=parseFloat(shipping_rule_rate);
        $('#grand_total').val(Math.round(grand_total_amount * 100) / 100);
    }
    // array_filter
    Array.prototype.clean = function(deleteValue) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] == deleteValue) {
                this.splice(i, 1);
                i--;
            }
        }
        return this;
    };

    function calculateRow(row) {
        var price = +row.find('input[name^="qty"]').val();
    }

    function calculateGrandTotal() {
        var grandTotal = 0;
        $("table.order-list").find('input[name^="qty"]').each(function () {
            grandTotal += +$(this).val();
        });
        $("#grandtotal").text(grandTotal.toFixed(2));
    }

</script>
