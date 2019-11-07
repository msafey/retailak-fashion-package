  <div class="modal-dialog modal-lg modal_exist">
    <div class="modal-content">
    <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Payment Entry</h5>
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <div class="form-group">
             <label style="margin-bottom: 0;"  class="form-group" for="from">Mode Of Payment: </label>    
              <select required id="payment_mode_id" name="payment_mode_id" @if(isset($payment_exist) && $payment_exist->status != 0) disabled="disabled" @endif class="form-control" >
                  <option value="-1" disabled selected>Select Mode Of Payment</option>
             <?php foreach ($payment_methods as $method) { ?>
                  <option value="{{$method->id}}" @if(isset($payment_exist) && $payment_exist->payment_mode_id == $method->id)selected @endif >{{$method->name}}</option>
              <?php } ?>
              </select>
          </div>
          <div class="form-group">
              <label style="margin-bottom: 0;"  class="form-group" for="from">Posting Date: </label>    
              <div class='input-group date' id='datetimepicker1'>

                <input type='text' id="posting_date" disabled="disabled" name="posting_date" value="@if(isset($payment_exist) && $payment_exist->status != 0){{$payment_exist->date}} @else {{old('date_created',Carbon\Carbon::today()->format('Y-m-d'))}}@endif" class="form-control"/>
                <span class="input-group-addon">
                  <span class="zmdi zmdi-calendar"></span>
                </span>
              </div>

          </div>


          <div class="form-group">
              
                            <label>Orders Data </label>
                            <table class="table" style="width:100%">

                                <tr>
                                    <th>Order Name</th>
                                    <th>Order Qty</th>
                                    <th>Product Name</th>
                                    <th>Price Per Unit</th>
                                    <th>Total Price</th>
                                </tr>

                                <tr>

                                    <td>
                                        <table class="remove">

                                            @foreach ($orders->OrderItems as $product)

                                                <tr class="removeborder">
                                                    <td>{{$product->item_name}}</td>
                                                </tr>

                                            @endforeach
                                        </table>
                                    </td>

                                    <td>
                                        <table class="remove">

                                            @foreach ($orders->OrderItems as $product)

                                                <tr class="removeborder">
                                                    <td>{{$product->qty}}</td>
                                                </tr>

                                            @endforeach
                                        </table>
                                    </td>

                                    <td>
                                        <table class="remove">

                                            @foreach ($products as $product)

                                                <tr class="removeborder">
                                                    <td>{{$product['name']}}</td>
                                                </tr>

                                            @endforeach
                                        </table>
                                    </td>
                                   <!--  <td>
                                        <table class="remove">

                                            <tr class="removeborder">
                                                <td>{{str_replace('SO-', '', $orders->salesorder_id)}}</td>
                                            </tr>

                                        </table>
                                    </td> -->

                                    <!-- {{str_replace('SO-', '', $orders->salesorder_id)}} -->

                                    <td>
                                        <table class="remove">
                                            @foreach ($products as $product)
                                                <tr class="removeborder">
                                                    <td>{{$product['rate']}}</td>
                                                </tr>

                                            @endforeach
                                        </table>
                                    </td>


                                    <td>
                                        <table class="remove">


                                            @foreach ($products as $product)
                                                <tr class="removeborder">
                                                    <td>{{$product['total_price']}}</td>
                                                </tr>

                                            @endforeach
                                        </table>
                                    </td>

                                   <!--  <td>
                                        <table class="remove">


                                            <tr class="removeborder">
                                                <td>{{$final_total_price}}</td>
                                            </tr>

                                        </table>
                                    </td> -->


                                </tr>


                            </table>
          </div>

          <div class="form-group">

            <input type="hidden" name="sales_order_id" id="sales_order_id" value="{{$sales_order_id[0]}}">
            <label style="margin-bottom: 0;"  class="form-group" for="from">Paid Amount: </label>    
            <input type="number" id="paid_amount" name="paid_amount" class="form-control paid_amount" style="display: inline;" step="0.01" min="{{$total_amount_after_discount}}" @if(isset($payment_exist) && $payment_exist->status != 0)value="{{$payment_exist->paid_amount}}" disabled="disabled" @endif >
          </div>
          <div class="form-group">
            <label style="margin-bottom: 0;"  class="form-group" for="from">Shipping Rate: </label>
            <input type='text' disabled="disabled" value="{{$shipping_rate}}" class="form-control"/>

          </div>
          <div class="form-group">
             <label style="margin-bottom: 0;"  class="form-group" for="from">Promo Code Discount: </label>   

            <input type='text' disabled="disabled" value="{{$promocode_msg}}" class="form-control"/>

          </div>
          <div class="form-group">
            <label style="margin-bottom: 0;" class="form-group" for="from">Grand Total
            </label>
            <input type='text' id="final_total_amount" hidden="hidden" @if(isset($payment_exist) && $payment_exist->status != 0)disabled="disabled" @endif value="{{$final_total_price}}"   name="final_total_amount" class="form-control">

            <input type='text' value="{{$final_total_price}}" disabled="disabled"  name="name_en" class="form-control">
          </div>
          @if(isset($total_amount_after_discount))
          <div class="form-group">
            <label style="margin-bottom: 0;" class="form-group" for="from">Grand Total After Discount
                                                    </label>
            <input type='text' id="final_total_amount_after_discount" hidden="hidden" @if(isset($payment_exist) && $payment_exist->status != 0)disabled="disabled" @endif value="{{$total_amount_after_discount}}"   name="final_total_amount_after_discount" class="form-control total_amount_after_discount">

            <input type='text' value="{{$total_amount_after_discount}}" disabled="disabled"  name="" class="form-control total_amount_after_discount">
          </div>
          @endif
              <div class="form-group">
                <label style="margin-bottom: 0;" class="form-group" for="from">Unallocated Amount
                </label>
                <input type='text' hidden="hidden" @if(isset($payment_exist) && $payment_exist->status != 0)value="{{$payment_exist->unallocated_amount}}" disabled="disabled" @endif  id="unallocated-"   name="unallocated_amount" class="form-control">                
                <input type='number'  step="0.01" @if(isset($payment_exist) && $payment_exist->status != 0)value="{{$payment_exist->unallocated_amount}}" disabled="disabled" @endif  id="unallocated" name="unallocated_amount" class="form-control">
              </div>

          @if(isset($payment_exist) && $payment_exist->status != 0)
          <input type="text" hidden="hidden" id="payment_exist" name="payment_exist" value="{{$payment_exist->id}}">

          @endif

          <input type="text" hidden="hidden" id="delivery_order_id" name="delivery_order_id" value="{{$delivery_order_id}}">
                     <div class="row">
                          <div class="col-sm-32"><button type="button" id="cancel" style="margin-left: 12px" class="btn btn-warning save">Cancel</button>
                          <button  type="button"  style="margin-left: 12px" id="save" class="btn btn-primary save">Save</button>
</div>
                         

                    </div>
          </div>
    </div>
  </div>
<script type="text/javascript">
  
  <?php $payment_url = url('runsheet/sales_order_id/payment');?>
 
      $(document).on('input', '.paid_amount', function(e){
                    var attr_id = $(this).attr('id');      
                    var paid_value = $(this).val();
                    var total_amount = $('.total_amount_after_discount').val();
                    var unallocated_amount = paid_value - total_amount;
                    if(unallocated_amount < 0){
                       $('#unallocated').val(0); 
                       $('#unallocated-').val(0); 
                    }else{
                     $('#unallocated').val(Math.round(unallocated_amount));   
                     $('#unallocated-').val(Math.round(unallocated_amount));   
                    }
                });
 
      $(document).on('click','.save',function(event){

      
        
        var salesorder_id = $('#sales_order_id').val();
        var payment_mode_id=$('#payment_mode_id').val();
        var posting_date=$('#posting_date').val();
        var paid_amount=$('#paid_amount').val();
        var final_total_amount=$('#final_total_amount').val();
        var final_total_amount_after_discount=$('#final_total_amount_after_discount').val();
        var unallocated=$('#unallocated').val();
        var payment_exist=$('#payment_exist').val();
        var delivery_order_id=$('#delivery_order_id').val(); 
        $.ajax({          
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{URL::to('admin/runsheet/orders/payment')}}"+'/' + salesorder_id,
            type: "POST",
            data: {'payment_mode_id': payment_mode_id,
            'posting_date':posting_date,
            'paid_amount':paid_amount,
            'final_total_amount':final_total_amount,
            'final_total_amount_after_discount':final_total_amount_after_discount,
            'unallocated_amount':unallocated,
            'payment_exist':payment_exist,
            'delivery_order_id':delivery_order_id
            },
            success: function (data) {
              // console.log($('#unallocated-').val());
              if(parseInt(paid_amount) < parseInt(final_total_amount_after_discount) || data=='check paid amount'){
                  alert('Paid Amount is not enough');
                  $('#myModal1').modal('hide');
              }
              if(!payment_mode_id){
                alert('please select payment mode');
                $('#myModal1').modal('hide');
              }
              if(data == 'cancelled'){
                $('#myModal1').modal('hide');
                if(delivery_order_id ==0){
                  var url = window.location.href.split('?')[0];

                  var cour= document.getElementById("courier_id").value;
                  var ware= document.getElementById("warehouse_id").value;
                  var sheet= document.getElementById("run_sheet_id").value;
                  history.pushState(null, null, url);
                  window.location.search += 'courier_id='+cour+'&warehouse_id='+ware+'&run_sheet_id='+sheet;

                }else{
                  location.reload();
                }
              }
              if(data == 'true++'){
                var url = window.location.href.split('?')[0];

                var cour= document.getElementById("courier_id").value;
                var ware= document.getElementById("warehouse_id").value;
                var sheet= document.getElementById("run_sheet_id").value;
                  $('#myModal1').modal('hide');
                  history.pushState(null, null, url);
                  window.location.search += 'courier_id='+cour+'&warehouse_id='+ware+'&run_sheet_id='+sheet;

              }else if(data =='true'){
                  $('#myModal1').modal('hide');
                  location.reload();   // then reload the page.(3)
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });

          


      });


</script>