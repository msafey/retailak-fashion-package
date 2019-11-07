<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head') 
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

</script>
<!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
 -->

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<!-- App Favicon -->
<!-- <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/> -->

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />



       <!-- Latest compiled and minified bootstrap-select CSS -->
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
</script>

<style> 
.circle {
        border-radius:100%;        
        background: #bbb2b2;
        display:inline-block;
        line-height:20px;
        width:20px;
        text-align:center;
}
</style>
<body class="fixed-left">
<!-- Begin page -->
<div id="wrapper">
    <!-- Top Bar Start -->
@include('layouts.admin.topbar')
<!-- Top Bar End -->
    <!-- ========== Left Sidebar Start ========== -->
@include('layouts.admin.sidemenu')
<!-- Left Sidebar End -->

    <!-- Start right Content here -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <!-- Bread Crumb And Title Section -->
@component('layouts.admin.breadcrumb')
                @slot('title')
                      Purchase Order Details
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Purchase Orders
                @endslot
                You are not allowed to access this resource!
                @endcomponent             <!--End Bread Crumb And Title Section -->
                <div class="row">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                      <div class="modal fade bd-example-modal-lg" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-backdrop="false" aria-hidden="true">
                        <div class="modal-dialog modal-lg email_modal_exist">
                          <div class="modal-content">
                          <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Send PO by Email</h5>
                                </div>
                                <div class="modal-body">
                                <div class="form-group">
                                   <label style="margin-bottom: 0;"  class="form-group" for="from">Email: </label>    
                                    <input required id="email" name="email"  class="form-control" >
                                        
                                </div>
                                <div class="form-group">
                                    <label style="margin-bottom: 0;"  class="form-group" for="from">Posting Date: </label>    
                                    <div class='input-group date' id='datetimepicker1'>

                                      <input type='text' id="posting_date" disabled="disabled" name="posting_date" value="{{old('date_created',Carbon\Carbon::today()->format('Y-m-d'))}}" class="form-control"/>
                                      <span class="input-group-addon">
                                        <span class="zmdi zmdi-calendar"></span>
                                      </span>
                                    </div>

                                </div>
                                <div class="form-group">
                                <label for="">Body Of Email</label>
                                 <textarea name="body"  id="editor" class="form-control col-sm-6" cols="7"></textarea>
                                </div>
                                <br>
                                <br>      
                                           <div class="row">
                                                <div class="col-sm-32" id="saved">
                                                <button  type="submit"  style="margin-left: 12px" id="save" class="btn btn-primary save">Send Email</button>
                                                <button  type="cancel"  style="margin-left: 12px" id="thanks" class="btn btn-warning cancel">No Thanks</button>
                                                </div>
                                          </div>
                                </div>
                          </div>
                        </div>
                      </div>
  

                <div class="card card-block" style="width: 82%">
                    
                  <div class="row">


                        <div class="col-lg-6">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">Company
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class='input-group date' id='' style="display: inline;">
                                <input type="" disabled="disabled" class="form-control" value="@if(isset($selected_company->name_en)){{$selected_company->name_en}} @endif">
                              </div>
                            </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">Warehouse
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class='input-group date' id='' style="display: inline;">
                                <input type="" disabled="disabled" class="form-control" value="@if(isset($selected_warehouse->name_en)){{$selected_warehouse->name_en}} @endif">
                              </div>
                            </div>
                        </div>


</div>
                  <div class="row">                         
                        <div class="col-lg-6">
                          <div class="col-sm-12">
                            <label style="margin-bottom: 0;"  class="form-group" for="from">Date of Delivery:
                            </label>
                          </div>
                          <div class="col-sm-12" >
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' autocomplete="off" disabled="disabled" name="required_by_date" value="{{date ('m/d/Y h:i:m',strtotime($order->required_by_date))}}" class="form-control"/>
                                <span class="input-group-addon">
                                    <span class="zmdi zmdi-calendar"></span>
                                </span>
                            </div>
                          </div>
                        </div>
                       
                  </div>

<hr>  

                      <div class="row">
                            <div class="col-lg-12">
                              <div class="col-sm-12">

                            <table id="myTable"  class=" table order-list">
                            <thead>
                                <tr>
                                    <td>Items</td>
                                    <td>Quantity</td>
                                    <td>rate</td>
                                    <td>Total Amount</td>
                                    <td colspan="1" style="text-align: right;float: center;margin-top: 10px;padding-right: 150px;">
                                        <!-- <input type="button" class="btn btn-lg btn-primary " id="addrow" value="+" /> -->
                                    </td>
                                </tr>
                            </thead>
                            <tbody id="tblrownew0">
                                <?php $i=1; ?>
                            @if(count($item_details)>0)
                                @forelse($item_details as $order_item)
                                    <tr id="field{{$i}}">
                                        <td class="col-sm-3">
                                          <div class='input-group date' id='' style="display: inline;">
                                                 <select required name="items[]" style="width:300px;" id="items{{$i}}" class="form-control selectpickerr select2" style="" >
                                                   @foreach ($products as $product)
                                                  <option value="{{$product->id}}" 
                                                    @if($order_item->item_id == $product->id)
                                                      selected disabled
                                                     @endif 
                                                     data-foo="{{$product->name_en}}"  id="option-{{$product->id}}">{{$product->name}}</option>
                                                  @endforeach
                                                  </select>

                                          </div>                                    
                                        </td>

                                        <td class="col-sm-2">
                                            <input type="number"  min="1"    name="quantity[]" value="{{$order_item->qty}}" id="qty" disabled="disabled"  class="form-control    qty"/>
                                        </td>
                                        <td class="col-sm-2">
                                            <input  type="number" step="0.1" min="0"  value="{{$order_item->rate}}"    name="rate[]" id="rate"  disabled="disabled"  class="form-control item_rate"/>
                                        </td>
                                        <td class="col-sm-3">
                                            <input type="number"  disabled="disabled"  min="0"  step="0.01" name="total_amount[]"  value="{{$order_item->total_amount}}"    id="total_amount" class="form-control total"/>
                                        </td>
                                        <td class="col-sm-1">
                                          <!-- <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"> -->
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @empty
                          
                                @endforelse
                            @endif
                            @if(count($parent_array)>0)                            
                                @forelse($parent_array as $key => $parent_products)
                                <tr>
                                  <td class="col-sm-6"><label>Variations Of ( <span style='color: red;'><b>'#{{$key}}'</b></span> )</label></td>
                                </tr>

                                    @foreach ($parent_products as $product)
                                    <tr id="field{{$i}}">
                                        <td class="col-sm-3">
                                          <div class='input-group date' id='' style="display: inline;">
                                                 <select required name="items[]" style="width:300px;" id="items{{$i}}" class="form-control selectpickerr select2" style="" >
                                                  <option value="{{$product->item_id}}" 
                                              
                                                     data-foo="{{$product->item_name}}"  id="option-{{$product->item_id}}">{{$product->item_name}}</option>
                                                  </select>

                                          </div>                                    
                                        </td>

                                        <td class="col-sm-2">
                                            <input type="number"  min="1"    name="quantity[]" value="{{$product->qty}}" id="qty" disabled="disabled"  class="form-control    qty"/>
                                        </td>
                                        <td class="col-sm-2">
                                            <input  type="number" step="0.1" min="0"  value="{{$product->rate}}"    name="rate[]" id="rate"  disabled="disabled"  class="form-control item_rate"/>
                                        </td>
                                        <td class="col-sm-3">
                                            <input type="number"  disabled="disabled"  min="0"  step="0.01" name="total_amount[]"  value="{{$product->total_amount}}"    id="total_amount" class="form-control total"/>
                                        </td>
                                        <td class="col-sm-1">
                                          <!-- <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"> -->
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                    @endforeach
                                @empty
                                
                                @endforelse
                            @endif

                                
                            </tbody>
                            <tfoot>
                                <tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <br>
                                  
                                </tr>
                                <tr>
                                </tr>
                            </tfoot>
                            </table>

                            </div>
                            </div>
                      </div>
                     
                      @if($order->discount != 0)
                      <hr>
                        <div class="row">
                          <div class="col-sm-2 form-group" style="margin-left: 20px;">
                            <b>Discount Type :</b> {{$order->discount_type}}
                          </div>
                          <div class="col-sm-6" id="discountBox">
                              <div class="row">
                                  <div class="col-sm-3" style="margin:0">
                                      <label for="persentage"></label>
                                  </div>
                                    @if($order->discount_type == "persentage")
                                  <div class="col-sm-2" style="margin-right: 10px;">
                                      <input type="number" step="any" class="form-control" disabled="disabled" value="{{$discount_rate}}" name="discount" style="width:80px;">

                                  </div>
                                  <div class="col-sm-2" id="typeSympole" >

                                          <h5> Pound(s)</h5>
                                  </div>
                                  <div class="col-sm-2" style="margin-right: 10px;">
                                      <input type="number" step="any" class="form-control" disabled="disabled" value="{{$order->discount}}" name="discount" style="width:80px;">
                                    </div>
                                    @else
                                    <div class="col-sm-2" style="margin-right: 10px;">

                                    <input type="number" step="any" class="form-control" disabled="disabled" value="{{$discount_rate}}" name="discount" style="width:80px;">
                                  </div>

                                    @endif
                                  <div class="col-sm-2" id="typeSympole" >
                                      @if($order->discount_type == 'amount')
                                          <h5> Pound(s)</h5>
                                      @else
                                          <h5> %</h5>
                                      @endif

                                  </div>

                              </div>
                          </div>
                      </div>
                      @endif
                      <hr>
                      <div class="row">
                          <div class="col-lg-6">
                              <div class="col-lg-12">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">Taxes & Charges
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px">
                                  <div class='input-group date' style="display: inline;" id=''>
                                     <input type="" class="form-control" disabled="disabled" value="@if(isset($selected_tax->type)){{$selected_tax->type}} - with  @endif @if($taxs_amount>0){{$taxs_amount}} Amount @elseif($taxs_rate>0) {{$taxs_rate}} % @endif">
                                  </div>
                          </div>
                        </div>
                          <div class="col-lg-6">
                              <div class="col-lg-12" style="">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">Shipping Rule
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px">
                                              <div class='input-group date' style="display: inline;" id=''>
                                                <input type="" disabled="disabled" class="form-control" value="@if(isset($shipping_rule->shipping_rule_label)){{$shipping_rule->shipping_rule_label}} - @endif {{$shipping_rule_rate}} LE">
                                              </div>
                                          </div>
                          </div> 
                        </div>

                      



                     <div class="row">
                       <div class="col-lg-5" style="left:20px;width: 400px;margin-right: 100px; ; height:150px;padding:10px;border: 2px solid gray; margin-top: 100px;">

                              <div class="col-lg-12" style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                  <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Grand Total <a style="width: 100px;" href="#/" title="Total Price After Adding Taxes & Shipping Rate & discount"  data-toggle="popover" data-trigger="focus" data-placement="top"
                                      data-content="Grand Total Amount = Total Of Orders {{$total_on_order}}  
                                     + Taxes
                                      @if(isset($selected_tax->type)){{$selected_tax->type}} @endif 
                                       @if($taxs_amount>0){{$taxs_amount}} LE @elseif($taxs_rate>0) {{$taxs_rate}} % ({{$total_on_order}} / 100 * {{$taxs_rate}}) @endif + Shipping {{$shipping_rule_rate}} LE "><span class="circle">?</span></a>
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0">
                                  <div class='input-group' id='' style="display: inline;  text-align: right">
                                    <input readonly type="text" name="grand_total_amount" style="height: 40px;text-align: center; "  id="grand_total" class="form-control" value="{{$grand_total_amount}}">

                                  </div>
                              </div>


                          </div>
                     
                          <div class="col-lg-5" style="left:10px;width: 400px; ; height:150px;padding:10px;border: 2px solid gray; margin-top: 100px;">
                                 <div class="col-lg-12" style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                     <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Grand Total After Discount &nbsp;&nbsp;&nbsp;<a style="width: 100px;" href="#/" title="Total Price After Adding Taxes & Shipping Rate & discount"  data-toggle="popover" data-trigger="focus" data-placement="top"
                                      data-content="Grand Total Amount = Total Of Orders {{$total_on_order}} - Discount {{$discount_rate}} 
                                      @if($order->discount_type == 'persentage')% @else LE @endif + Taxes
                                      @if(isset($selected_tax->type)){{$selected_tax->type}} @endif 
                                       @if($taxs_amount>0){{$taxs_amount}} LE @elseif($taxs_rate>0) {{$taxs_rate}} % (After Discount : {{$total_on_order-$discount_rate}} / 100 * {{$taxs_rate}}) @endif + Shipping {{$shipping_rule_rate}} LE "><span class="circle">?</span></a>
                                     </label>
                                 </div>
                                 <div class="col-lg-12" style="margin-top: 0">
                                     <div class='input-group' id='' style="display: inline;  text-align: right">
                                       <input readonly type="text" name="grand_total_amount" style="height: 40px;text-align: center; "  id="grand_total" class="form-control" value="{{$total_after_discount}}">

                                     </div>
                                 </div>
                             </div>   
                          </div>


                             <hr>  

                                 <div class="row"> 
                                   <div class="col-lg-1">  
                                   </div>
                                   <div class="col-lg-9">  
                                        <?php $purchase_orders_url = url('/admin/purchase-orders/');?>
                                     <a href="{{url('/admin/purchase-orders/'.$order->id.'/purchase-receipts')}}"
                                                 class='btn btn-primary' target="_blank" title='Purchase Receipts'>Purchase Receipts ({{$order->count_purchase_receipts}})</a>
                                                           <a href="#" data-toggle="modal" data-target="#myModal4" class='btn btn-primary' target="_blank" title='Purchase Receipts'>Email PO</a>
                                                 <a href="{{url('admin/purchase-orders/download-file/'.$order->id)}}" class='btn btn-warning'>Download PO PDF</a>

                                                 <a href="{{url('admin/purchase-orders/download-file/'.$order->id.'?query=1')}}" class='btn btn-warning'>Download PO Without Prices PDF</a>
                                   </div>
                                   <div class="col-lg-2">  
                                   </div>
                                  </div>
                      <hr>
                      <div class="row">
                        <div class="col-lg-12">  
                            <div class="col-lg-4 form-group" style="margin-left: 20px;">
                                <b>Activities Of This Order</b>
                            </div>
                            <div class="col-lg-12 form-group" >  

                                      <table class=" table order-list"> 
                                           <thead>
                                                <tr>
                                                    <td>User Name</td>
                                                    <td>Action</td>
                                                    <td>Time</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              @foreach($activites as $activity)
                                              <tr>
                                                  <td>{{$activity->user_name}}</td>
                                                  <td>{{$activity->action}}</td>
                                                  <td>{{$activity->created_at}}</td>
                                              </tr>
                                              @endforeach
                                            </tbody>
                                            <tfoot> 
                                              <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <br>
                                                
                                              </tr>
                                              <tr>
                                              </tr>
                                            </tfoot>
                                      </table>
                            </div>
                        </div>  
                                               
                      </div>
                      <div class="row">
                          <div class="col-sm-32"><button id="save" type="submit" style="margin-left: 12px" class="btn btn-primary">Save</button></div>
                      </div>


                </div>
                <!-- {!! Form::close() !!} -->
            </div>
        </div>


    </div>
</div>
</div>

</div>
</div> <!-- container -->
</div> <!-- content -->
</div>
<!-- End content-page -->
<!-- Footer Area -->
@include('layouts.admin.footer')

<!-- End Footer Area-->
</div>
<!-- END wrapper -->
<script>
    var resizefunc = [];
</script>

<!-- JAVASCRIPT AREA -->


@include('layouts.admin.javascript')
<script src="{{url('/public/')}}/prasley/parsley.js"></script>
<script src="{{url('/public/admin/plugins/moment/')}}/moment.js"></script>
<script src="{{url('/public/admin/')}}/js/bootstrap-datetimepicker.js"></script>
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>



    <script src="{{url('components/components/select2/dist/js/select2.js')}}"></script>

<script type="text/javascript">
  var select_ids = $('.selectpickerr').map(function() {
    return $(this).attr('id');
  });
 $.each(select_ids,function(index,value){
          
          $('#'+value).attr('disabled',true);
      });
</script>



<script type="text/javascript">
   
      var company_name = '<?php echo $company_name;?>';

      var purchase_order_id = <?php echo $order->id ;?>;
    $(document).on('click','.cancel',function(event){
      $('#myModal4').modal('hide');
      // <?php $purchase_order = url('/admin/purchase-orders');?>
      // $('#myModal4').modal('hide')
      // window.location.href='<?php echo $purchase_order ?>';
    });
      
      $(document).on('click','.save',function(event){
        var email = $('#email').val();
        var body = $('#editor').val();
        if (email == "") {
                alert("Email must be filled out");
                return false;           
        }else{
            if($('.saved').length <= 0){
                $('#saved').append('<p class="saved" style="margin-left:10px;"><span style="color:red;">     loading....</span><p>');
            }

            
            $.ajax({          
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{URL::to('admin/sendEmail')}}",
                type: "POST",
                data: {'email': email,'body':body,'company_name':company_name,'purchase_order_id':purchase_order_id            },
                success: function (data) {
                  // $('#saved').append('<p>loading....<p>');
                  if(data == 'true'){
                    $('#myModal4').modal('hide');
          <?php $purchase_order = url('/admin/purchase-orders');?>
                        location.reload();
                // window.location.href='<?php echo $purchase_order ?>';

                  }
              
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });  
        }
               
      });


</script>
<!-- JAVASCRIPT AREA -->
</body>
</html>