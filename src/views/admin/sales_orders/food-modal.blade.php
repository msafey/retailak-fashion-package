<style>
  .center{
  width: 150px;
    margin: 40px auto;    
  }
  td{
    padding-bottom: 20px !important; 
  }
</style>
  <div class="modal-dialog modal-lg email_modal_exist" >
    <div class="modal-content" style="padding:15px;">
    <div class="modal-header">
            <button type="button" class="close" style="float: right;" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <b><h3 class="modal-title" id="exampleModalLabel">Add Item Choices</h3></b>
    </div>
    <div class="modal-body">
      <div class="form-group" >
            <div class="row">
              <div class="col-md-8" style="margin-left: 20px;" >
                <div class="row">
                  <label for=""><h3>Product Name: <span style="color: red">#</span><b>{{$product->name_en}}</b><label style="margin-left: 10px;"> (<span style="font-size: 20px;">{{$product->description_en}}</span>) </label></h3></label>
                </div>
                  <input type="text" id="parent_id"  value="{{$product->id}}" hidden="">
              </div>
              <div class="col-md-3" style="float: right;">
                <div class="input-group" style="width: 70%;left: 40px;margin-top: 20px;">
                    <span class="input-group-btn">
                        <button type="button" onchange="grandTotal();" class="btn btn-default btn-number "  data-type="minus" data-field="quant[2]">
                          <span class="glyphicon glyphicon-minus"></span>
                        </button>
                    </span>
                    <input type="text" id="count" onchange="grandTotal();" readonly="readonly" name="quant[2]" class="form-control input-number" value="1" min="1" max="150">
                    <span class="input-group-btn">
                        <button type="button"  class="btn btn-default btn-number " onchange="grandTotal();" data-type="plus" data-field="quant[2]">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </span>
                </div>
              </div>
            </div>
          </div>
          @if($type != 'one_size')
          <div class="form-group">
            <div class="row" id="choose" style="margin:0px;background-color: #ccceb0;padding: 0px 10px;min-height: 50px;">
                <h3>Please Choose Size</h3>
            </div>
          </div>
          <div class="form-group" style="margin-bottom: 20px;">
            <?php $i =1; ?>
              @foreach($product_variations as $variant)
              <div class="card card-block">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-lg-1">
                        <input type="radio" value="{{$variant->item_id}}" class="option_type" id="option{{$variant->item_id}}" name="option">
                        <input type="text" hidden="" disabled value="{{$variant->rate}}" id="item-price{{$variant->item_id}}">
                        <input type="text" hidden="" name="food_type" value="{{$type}}">
                        <input type="text" id="option_name{{$variant->item_id}}" value="{{$variant->item_name}}" hidden="">
                    </div>
                    <div class="col-lg-2" style="">
                        <label style="margin-bottom: 0;" class="form-group" for="from">{{$variant->item_name}}
                        </label>
                    </div>
                    <div class="col-lg-6" style="float: right;left: 50px;"> 
                      <div class="col-lg-3 div_prices" style="width: 150px;display: none;" id="div_price{{$variant->item_id}}">
                        <label for="">Price</label>
                        <input type="" readonly="" class="form-control" value="{{$variant->rate}} LE">
                      </div>
                      <div class="col-lg-3 prices" style="width: 150px;display: none;"  id="price-{{$variant->item_id}}">
                        <label for="">Total</label>
                        <input type="text" class="form-control" id="total_price-{{$variant->item_id}}" readonly="" value="0">
                      </div>
                    </div>
                    
                </div>
                @if(count($variant->extras) >0)
                <div class="row extras" id="extra-{{$variant->item_id}}" style="display:none;margin-left: 50px;margin-bottom: 20px;">
                  <div class="col-lg-11 card card-block">
                    <table style="" >
                      <thead>
                        <tr >
                          <td class="col-lg-3">Extra</td>
                          <td class="col-lg-2">Qty</td>
                          <td class="col-lg-3">Price</td>
                          <td class="col-lg-2">Include Extra</td>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($variant->extras as $extra)
                          <tr class="tr-{{$variant->item_id}}" id="extra_field{{$i}}">
                              <td class="col-lg-3">
                                <input type="text" disabled="" value="{{$extra->name}}" id="extra_name{{$i}}" class="form-control">

                                <input type="text" class="extras-{{$variant->item_id}}" hidden="" id="extra_id{{$i}}" name="extra_id-{{$variant->item_id}}[]" value="{{$extra->extra_id}}">
                              </td>
                              <td class="col-lg-2" >
                                <input type="number" value="1" id="extra_qty{{$i}}" min="1" onchange="grandTotal();" name="qty-{{$variant->item_id}}[]"  class="form-control extra_qty">
                              </td>

                              <td class="col-lg-2">
                                <input type="text" disabled="" value="{{$extra->food_extra_price}}" id="extra_rate{{$i}}" class="form-control">
                              </td>

                              <td class="col-lg-1">
                                <input 
                                 id="checked-{{$extra->extra_id}}" 
                                 class="optional_add"
                                 name="add-{{$variant->item_id}}[]" 
                                 value="1"
                                 type="checkbox" 
                                 @if($extra->is_optional == 0)
                                  disabled checked 
                                 @else
                                  checked 
                                 @endif >

                                <input type="text" hidden=""
                                 id="unchecked-{{$extra->extra_id}}" 
                                 name="add-{{$variant->item_id}}[]"
                                  @if($extra->is_optional == 0)
                                   value="1"
                                  @else 
                                    disabled="" value="0" 
                                  @endif >
                              </td>
                          </tr>
                          <?php $i++;?>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                @endif
               
              </div>
              @endforeach
          </div>
          @else
            <?php $i =1; ?>

            <div class="card card-block">
              <div class="row" style="margin-bottom: 20px;">
                  <div class="col-lg-1">
                      <input type="radio" checked="" readonly="" value="{{$product->id}}" class="option_type" id="option{{$product->id}}" name="option">
                      <input type="text" hidden="" disabled value="{{$item_rate}}" id="item-price{{$product->id}}">
                      <input type="text" hidden="" name="food_type" value="{{$type}}">
                      <input type="text" id="option_name{{$product->id}}" value="{{$product->name_en}}" hidden="">
                  </div>
                  <div class="col-lg-2" style="">
                      <label style="margin-bottom: 0;" class="form-group" for="from">One Size
                      </label>
                  </div>
                  <div class="col-lg-6" style="float: right;left: 50px;"> 
                    <div class="col-lg-3 div_prices" style="width: 150px;" id="div_price{{$product->id}}">
                      <label for="">Price</label>
                      <input type="" readonly="" class="form-control" value="{{$item_rate}} LE">
                    </div>
                    <div class="col-lg-3 prices" style="width: 150px;"  id="price-{{$product->id}}">
                      <label for="">Total</label>
                      <input type="text" class="form-control" id="total_price-{{$product->id}}" readonly="" value="0 LE">
                    </div>
                  </div>
              </div>
              @if(count($extras_array)>0);
              <div class="row extras" id="extra-{{$product->id}}" style="margin-left: 50px;margin-bottom: 20px;">
                <div class="col-lg-11 card card-block">
                  <table style="" >
                    <thead>
                      <tr >
                        <td class="col-lg-3">Extra</td>
                        <td class="col-lg-2">Qty</td>
                        <td class="col-lg-3">Price</td>
                        <td class="col-lg-2">Add</td>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($extras_array as $extra)
                        <tr class="tr-{{$product->id}}" id="extra_field{{$i}}">
                            <td class="col-lg-3">
                              <input type="text" disabled="" value="{{$extra->name}}" id="extra_name{{$i}}" class="form-control">

                              <input type="text" class="extras-{{$product->id}}" hidden="" id="extra_id{{$i}}" name="extra_id-{{$product->id}}[]" value="{{$extra->extra_id}}">
                            </td>
                            <td class="col-lg-2" >
                              <input type="number" value="1" id="extra_qty{{$i}}" min="1" onchange="grandTotal();" name="qty-{{$product->id}}[]"  class="form-control extra_qty">
                            </td>

                            <td class="col-lg-2">
                              <input type="text" disabled="" value="{{$extra->food_extra_price}}" id="extra_rate{{$i}}" class="form-control">
                            </td>

                            <td class="col-lg-1">
                              <input 
                               id="checked-{{$extra->extra_id}}" 
                               class="form-control optional_add"
                               name="add-{{$product->id}}[]" 
                               value="1"
                               type="checkbox" 
                               @if($extra->is_optional == 0)
                                disabled checked 
                               @else
                                checked 
                               @endif >

                              <input type="text" hidden=""
                               id="unchecked-{{$extra->extra_id}}" 
                               name="add-{{$product->id}}[]"
                                @if($extra->is_optional == 0)
                                 value="1"
                                @else 
                                  disabled="" value="0" 
                                @endif >
                            </td>
                        </tr>
                        <?php $i++;?>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              @endif       
            </div>

          @endif
          <div class="row">
            <div class="col-lg-6" style="">
              <label for="">Note:</label>
              <textarea name="" class="form-control" id="note" cols="10" rows="3"></textarea>              
            </div>
          </div>
          <br>
          <br>
             <div class="row">
                  <div class="col-sm-32" id="saved"><button type="button" id="cancel" style="margin-left: 12px" class="btn btn-warning cancel">Cancel</button>
                  <button  type="button"  style="margin-left: 12px" id="save" class="btn btn-primary save">Add</button>
                  </div>
            </div>
          </div>
    </div>
  </div>
  <script>
    $('.btn-number').click(function(e){
        e.preventDefault();
        fieldName = $(this).attr('data-field');
        type      = $(this).attr('data-type');
        var input = $("input[name='"+fieldName+"']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if(type == 'minus') {
                
                if(currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                } 
                if(parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if(type == 'plus') {

                if(currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if(parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });
    $('.input-number').focusin(function(){
       $(this).data('oldValue', $(this).val());
    });
    $('.input-number').change(function() {
        
        minValue =  parseInt($(this).attr('min'));
        maxValue =  parseInt($(this).attr('max'));
        valueCurrent = parseInt($(this).val());
        
        name = $(this).attr('name');
        if(valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            alert('Sorry, the minimum value was reached');
            $(this).val($(this).data('oldValue'));
        }
        if(valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            alert('Sorry, the maximum value was reached');
            $(this).val($(this).data('oldValue'));
        } 
    });
    $(".input-number").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                 // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) || 
                 // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });


function grandTotal(){
  var item_id = $('.option_type:checked').val();
  var count = $('#count').val();
  var total = 0;
  var item_rate = $('#item-price'+item_id).val();
  if(item_rate !=0){
      total = item_rate * count;
      $('.tr-'+item_id).each(function(){
        var extra_tr_num = $(this).attr('id').match(/\d+/);
        var extra_qty = parseInt($('#extra_qty'+extra_tr_num).val());
        var extra_rate = parseFloat($('#extra_rate'+extra_tr_num).val());
        var extra_amount = extra_qty * extra_rate;
        var extra_id = $('#extra_id'+extra_tr_num).val();
        if($('.tr-'+item_id+' #checked-'+extra_id).is(':checked')){
          total += extra_amount;
        }
      });
      $('#total_price-'+item_id).val(Math.round(total * 100) / 100+' LE');
  }else{
    $('#total_price-'+item_id).val(0);
  }
  return total;
}
    $(document).on('change','.optional_add',function(){
        var closest_tr = $(this).closest('tr').attr('class');
         var attr_id = $(this).attr('id').match(/\d+/);
        if($(this).is(':checked')){
          $('.'+closest_tr+' #unchecked-'+attr_id).attr('disabled',true);
        }else{
          $('.'+closest_tr+' #unchecked-'+attr_id).attr('disabled',false);
        }
        grandTotal();
    });
    
    $(document).one('click','#save',function(){
        var item_id = $('.option_type:checked').val();
        if(item_id == undefined){  
          alert('Select Option First');
          return false;
        }
        var key = '';
        var parent_name = '<?php echo $product->name_en; ?>';
        var parent_id = '<?php echo $product->id; ?>';
        key +=parent_name;
        var option_name = $('#option_name'+item_id).val();
        key+="-";
        key +=option_name;
        var item_price = $('#item-price'+item_id).val();
        var type = '<?php echo $type; ?>';
        var extra_array = [];
        $('.tr-'+item_id).each(function(){
          var extra_tr_num = $(this).attr('id').match(/\d+/);
          var extra_qty = $('#extra_qty'+extra_tr_num).val();
          var extra_id = $('#extra_id'+extra_tr_num).val();
          var extra_rate = $('#extra_rate'+extra_tr_num).val();
          var extra_name = $('#extra_name'+extra_tr_num).val();
          if($('.tr-'+item_id+' #checked-'+extra_id).is(':checked')){
            key +='-';
            var object = {'id':extra_id,'extra_name':extra_name,'qty':extra_qty,'accepted':1,'rate':extra_rate};
            key+=extra_name;
          }else{
            var object = {'id':extra_id,'extra_name':extra_name,'qty':extra_qty,'accepted':0,'rate':extra_rate};
          }
              extra_array.push(object);
        });
        var note = $('#note').val();
        var last_object = {'parent_id':parent_id,'parent_name':parent_name,'item_rate':item_price,'qty':$('#count').val(),'option':option_name,'option_id':item_id,'extras':extra_array,'total':grandTotal(),'type':type,'note':note,'key':key,'reference':'new','method_view':'cart'};
          if($('#'+key).length == 0){
              appendCartItem(last_object,key);
          }else{
            var last_qty = $('#'+key+' #order_qty').val();
            var last_option = $('#'+key+' #option_value').val();
            if (confirm('You Already Ordered '+parent_name+' of size '+last_option+' With Qty:'+last_qty+' with same extras,you want to override it?')) {
                $('#cart_data #'+key).remove();
                appendCartItem(last_object,key);
            } else {
                $('#foodModal').modal('hide');
            }
          }
    });

    function appendCartItem(last_object,key){
      $.ajax({
        method: 'GET',
        url: '{!! route('AddFoodItemToCart') !!}',
        data: {'item' : last_object},
        success: function(response){
          // if(!$('#'+key).length>1){
            $('#cart_data').append(response.data);
            // $('#'+key+' .card-block').attr("style","overflow-y:scroll;overflow-x:hidden");
            $('#foodModal').modal('hide');
            $('#foodModal').empty();
            $('#item1 option[value=""]').prop('selected', true);
            grandTotalAmount();
          // }
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
      });
    }


    $(document).on('change','.option_type',function(){
        <?php $url = url("public/imgs/success-circle.svg");?>
        $('#choose').empty();
        var item = $(this).val();
        var option_name = $('#option_name'+item).val();
        var selected_data = "<div style='margin:20px;'><img id='success_image' src='{{$url}}' style='display: inline;max-width: 20px;'><span style='    font-weight: 500;line-height: 1.1;'><b> ( "+option_name+" Choosen ) </span></b></div>";
        $('#choose').html(selected_data)
        $('.extras').css('display','none');
        $('.prices').css('display','none');
        $('.div_prices').css('display','none');
        $('#div_price'+item).css('display','block');
        $('#extra-'+item).css('display','block');
        $('#price-'+item).css('display','block');
        grandTotal();
    });
  </script> 