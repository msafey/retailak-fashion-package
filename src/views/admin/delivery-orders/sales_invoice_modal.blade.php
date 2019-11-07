  <div class="modal-dialog modal-lg invoice_modal_exist">
    <div class="modal-content">
    <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Sales Invoice</h5>
            
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
                {!! Form::open(['url' => 'admin/runsheet/'.$sales_order_id[0].'/sales-invoice', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}
        
          <div class="form-group">
              <label style="margin-bottom: 0;"  class="form-group" for="from">Customer Name: </label>    
              
                <input type='text' id="customer_name" disabled="disabled"  value="{{$user->name}}" class="form-control"/>

          </div>
          <div class="form-group">
              <label style="margin-bottom: 0;"  class="form-group" for="from">Date: </label>    
              <div class='input-group date' id='datetimepicker1'>

                <input type='text' id="posting_date" disabled="disabled" name="posting_date" value="@if(isset($sales_invoice) && $sales_invoice->status != 0){{$sales_invoice->date}} @else {{old('date_created',Carbon\Carbon::today()->format('Y-m-d'))}}@endif" class="form-control"/>
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

                                            @foreach (json_decode($productlist) as $product)

                                                <tr class="removeborder">
                                                    <td>{{$product->item_name}}</td>
                                                </tr>

                                            @endforeach
                                        </table>
                                    </td>

                                    <td>
                                        <table class="remove">

                                            @foreach (json_decode($productlist) as $product)

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
             <label style="margin-bottom: 0;"  class="form-group" for="from">Shipping Rate: </label>   
             <input type="text" disabled="disabled" class="form-control" id="shipping_rate" value="{{$shipping_rate}}"> 
           <!--    <select required id="shipping_role_id" disabled="disabled" name="shipping_role_id" @if(isset($sales_invoice) && $sales_invoice->status != 0) disabled="disabled" @endif class="form-control" >
                  <option value="-1" disabled selected></option>
             <?php foreach ($shipping_roles as $role) { ?>
                  <option value="{{$role->id}}" @if($selected_shipping_role->id == $role->id)selected @endif >{{$role->name}}</option>
              <?php } ?>
              </select> -->
          </div>

          @if(isset($final_price_after_discount))
          <div class="form-group">
             <label style="margin-bottom: 0;"  class="form-group" for="from">Promo Code Discount: </label>   
             <input type="text" disabled="disabled" class="form-control" value="{{$promocode_msg}}"> 
           <!--    <select required id="shipping_role_id" disabled="disabled" name="shipping_role_id" @if(isset($sales_invoice) && $sales_invoice->status != 0) disabled="disabled" @endif class="form-control" >
                  <option value="-1" disabled selected></option>
             <?php foreach ($shipping_roles as $role) { ?>
                  <option value="{{$role->id}}" @if($selected_shipping_role->id == $role->id)selected @endif >{{$role->name}}</option>
              <?php } ?>
              </select> -->
          </div>
          <div class="form-group">
            <label style="margin-bottom: 0;" class="form-group" for="from">Grand Total
            </label>
            <input type='text' value="{{$final_total_price}}" disabled="disabled"  name="name_en" class="form-control">
          </div>
          <div class="form-group">
            <label style="margin-bottom: 0;" class="form-group" for="from">Grand Total After Discount
            </label>
            <input type='text' value="{{$final_price_after_discount}}" disabled="disabled"  name="name_en" class="form-control">
          </div>

          <input type="text" hidden="hidden" id="final_price_after_discount"  name="final_price_after_discount" value="{{$final_price_after_discount}}">
          <input type="text" hidden="hidden" id="promocode_msg"  name="promocode_msg" value="{{$promocode_msg}}">

          

          @else

          <div class="form-group">
            <label style="margin-bottom: 0;" class="form-group" for="from">Grand Total
            </label>
            <input type='text' value="{{$final_total_price}}" disabled="disabled"  name="name_en" class="form-control">
          </div>
          
          @endif

          
          
          @if(isset($sales_invoice) && $sales_invoice->status != 0)
          <input type="text" hidden="hidden" id="sales_invoice_exist" name="sales_invoice_exist" value="{{$sales_invoice->id}}">

          @endif
          <input type="hidden" name="sales_order_id" id="sales_order_id" value="{{$sales_order_id[0]}}">
          <input type="text" hidden="hidden" id="delivery_order_id" name="delivery_order_id" value="{{$delivery_order_id}}">
          <div class="row">
                 <div class="col-sm-32"><button type="button" id="invoice_cancel" style="margin-left: 12px" class="btn btn-warning save_invoice">Cancel</button>
                <button  type="button"  style="margin-left: 12px" id="invoice_save" class="btn btn-primary save_invoice">Save</button>
           </div>
          </div>
                {!! Form::close() !!}
          </div>
    </div>
  </div>
<script type="text/javascript">
 
  <?php $invoice_url = url('runsheet/$sales_order_id/sales-invoice');?>
        var url = window.location.href.split('?')[0];

      $(document).on('click','.save_invoice',function(event){
        var sales_invoice_exist=$('#sales_invoice_exist').val();
        var delivery_order_id=$('#delivery_order_id').val();     
        var salesorder_id = $('#sales_order_id').val();
        var promocode_msg = $('#promocode_msg').val();
        var shipping_rate = $('#shipping_rate').val();
        var final_price_after_discount = $('#final_price_after_discount').val();
 
         $.ajax({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{URL::to('admin/runsheet/orders/invoice')}}"+'/' + salesorder_id,
            type: "POST",
            data: {
            'sales_invoice_exist':sales_invoice_exist,
            'delivery_order_id':delivery_order_id,'final_price_after_discount':final_price_after_discount,'promocode_msg':promocode_msg,'shipping_rate' : shipping_rate
            },
            success: function (data) {
              if(data=='cancel payment first'){
                alert('Cancel Payment Entry Of This Order First');
                $('#myModal3').modal('hide');
              }else{
                if(data == 'deactivated'){

                  $('#myModal3').modal('hide');
                   
                  if(delivery_order_id ==0){
                    var cour= document.getElementById("courier_id").value;
                    var ware= document.getElementById("warehouse_id").value;
                    var sheet= document.getElementById("run_sheet_id").value;
                    
                        // function getUrlParam(name) {
                        //     var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
                        //     return (results && results[1]) || undefined;
                        // }

                        // var courier_id_check = getUrlParam('courier_id');
                        // var warehouse_id_check = getUrlParam('warehouse_id');
                        // var runsheet_id_check = getUrlParam('run_sheet_id');

                        // if (runsheet_id_check > "" || warehouse_id_check > "" || courier_id_check > "") {
                          history.pushState(null, null, url);


                             window.location.search += 'courier_id='+cour+'&warehouse_id='+ware+'&run_sheet_id='+sheet;  
                        // }
                      }else{
                        location.reload(); // then reload the page.(3)
                      }

                }
                $('#myModal3').modal('hide');

                if($('.email_modal_exist').length <= 0){
                    $('#myModal4').append(data.data);
                }

                  $('#myModal4').modal('show')

            
              }
            
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });

          


      });


</script>