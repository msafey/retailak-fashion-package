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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<!-- App Favicon -->
<!-- <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/> -->

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />



       <!-- Latest compiled and minified bootstrap-select CSS -->
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">


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
                      Edit Purchase Order
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
        {!! Form::open(['url' => ['/admin/purchase-orders', $order->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block" style="width: 92%">
                    
                  <div class="row">


                        <div class="col-lg-6">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">Company
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class='input-group date' id='' style="display: inline;">
                                <select required name="company_id" id="Company" class="form-control" >
                                    <option value="-1" disabled selected>Select Company</option>
                                    <?php foreach ($companies as $company) { ?>
                                    <option value="{{$company->id}}" @if($order->company_id == $company->id)selected @endif>{{$company->name_en}}</option>
                                    <?php } ?>
                                  </select>
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
                                <select required name="warehouse_id" id="warehouse" class="form-control" >
                                    <option value="-1" disabled selected>Select Warehouse</option>
                                    <?php foreach ($warehouses as $warehouse) { ?>
                                    <option value="{{$warehouse->id}}" @if(isset($order->warehouse_id) && $order->warehouse_id == $warehouse->id)selected @endif>{{$warehouse->name}}</option>
                                    <?php } ?>
                                  </select>
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
                                <input type='text' autocomplete="off" name="required_by_date" value="{{date ('m/d/Y h:i:m',strtotime($order->required_by_date))}}" class="form-control"/>
                                <span class="input-group-addon">
                                    <span class="zmdi zmdi-calendar"></span>
                                </span>
                            </div>
                          </div>
                        </div>
                  </div>

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
                                        <input type="button" class="btn btn-lg btn-primary " id="addrow" value="+" />
                                    </td>
                                </tr>
                            </thead>
                            <tbody id="tblrownew0">
                                <?php $i=1; ?>
                            @if(count($item_details)>0)
                                @foreach($item_details as $order_item)
                                    <tr id="field{{$i}}">
                                        <td class="col-sm-3">
                                          <div class='input-group date' id='' style="display: inline;">
                                                 <select required name="items[]" style="width:300px;" id="items{{$i}}" class="form-control selectpickerr select2" style="" >
                                                  <option value="-1" disabled  data-foo="Select Item" >Select Item</option>
                                                   @foreach ($products as $product)
                                                  <option value="{{$product->id}}"
                                                    @if($order_item->item_id == $product->id)
                                                      selected
                                                     @endif 
                                                     data-foo="{{$product->name_en}}" id="option-{{$product->id}}">{{$product->name}}</option>
                                                  @endforeach
                                                  </select>

                                          </div>                                    
                                        </td>

                                        <td class="col-sm-2">
                                            <input type="number"  min="1"    name="quantity[]" value="{{$order_item->qty}}" id="qty"  class="form-control    qty"/>
                                        </td>
                                        <td class="col-sm-2">
                                            <input  type="number" step="0.1"   value="{{$order_item->rate}}"    name="rate[]" id="rate"   class="form-control item_rate"/>
                                        </td>
                                        <td class="col-sm-3">
                                            <input type="number"  disabled="disabled"  name="total_amount[]"  value="{{$order_item->total_amount}}"    id="total_amount" class="form-control total"/>
                                        </td>
                                        <td class="col-sm-1">
                                          <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete">
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            @endif

                            @if(count($parent_array)>0)
                                @foreach($parent_array as $key => $order_item)
                                <tr>
                                  <td class="col-sm-4"><label>Variations Of ( <span style='color: red;'><b>'#{{$key}}'</b></span> )</label></td>
                                </tr>
                                  @foreach ($order_item as $product)
                                    <tr id="field{{$i}}" style="background-color: #fbfbfb;">
                                        <td class="col-sm-3">
                                          <div class='input-group date' id='' style="display: inline;">
                                                 <select required name="items[]" style="width: 240px;margin-left: 80px;" id="items{{$i}}" class="form-control selectpickerr select2" style="" >
                                                  <option value="-1" disabled  data-foo="Select Item" >Select Item</option>
                                                  <option value="{{$product->item_id}}"
                                                   
                                                     data-foo="{{$product->item_name}}" id="option-{{$product->item_id}}">{{$product->item_name}}</option>
                                                  </select>
                                          </div>                                    
                                        </td>
                                        <td class="col-sm-2">
                                            <input type="number"  min="0"    name="quantity[]" value="{{$product->qty}}" id="qty"  class="form-control    qty"/>
                                        </td>
                                        <td class="col-sm-2">
                                            <input  type="number" step="0.1"   value="{{$product->rate}}"    name="rate[]" id="rate"   class="form-control item_rate"/>
                                        </td>
                                        <td class="col-sm-3">
                                            <input type="number"  disabled="disabled"  name="total_amount[]"  value="{{$product->total_amount}}"    id="total_amount" class="form-control total"/>
                                        </td>
                                        <td class="col-sm-1">
                                          <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete">
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                    @endforeach
                                @endforeach
                            @endif


                            @if(count($parent_array) == 0 && count($item_details) == 0)
                              <tr id="field1">
                                  <td class="col-sm-4">
                                    <div class='input-group date' id='' style="display: inline;">
                                        <select required name="items[]" style="width:400px;" id="items1" class="form-control selectpickerr select2" style="" >
                                              <option value="-1" disabled  data-foo="Select Item" selected>Select Item</option>
                                            @foreach ($products as $product)
                                              <option value="{{$product->id}}" data-foo="{{$product->name_en}}" id="option-{{$product->id}}">{{$product->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>                                    
                                  </td>
                                  <td class="col-sm-2">
                                      <input type="number"  min="1"    name="quantity[]" value="1" id="qty"  class="form-control qty"/>
                                  </td>
                                  <td class="col-sm-2">
                                      <input  type="number"  step="0.1" min="0" value="0.00"    name="rate[]" id="rate"   class="form-control item_rate"/>
                                  </td>
                                  <td class="col-sm-3">
                                      <input type="number"  disabled="disabled"  min="0"  step="0.01" name="total_amount[]"  value="0.00"    id="total_amount" class="form-control total"/>
                                  </td>
                                  <td class="col-sm-1"><a class="deleteRow"></a>

                                  </td>
                              </tr>
                            @endif

                                <hr>
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
                            <hr>

                            </div>
                            </div>
                      </div>
                      <hr>
                        <div class="row">
                          <div class="col-sm-2 form-group" style="margin-left: 20px;">
                              <b>Discount</b>
                              <input type="checkbox" id="discountEnabler"  @if($order->discount != 0) checked @endif data-plugin="switchery" data-color="#ff5d48"/>

                          </div>
                          <div class="col-sm-6" id="discountBox" @if($order->discount == 0) style="display: none;" @endif>
                              <div class="row">
                                  <div class="col-sm-6" style="margin:0">
                                      <label for="persentage" class="c-input c-radio">
                                          <input type="radio" @if($order->discount_type == 'persentage') checked="checked" @endif name="type" id="persentage" value="persentage" >
                                          <span class="c-indicator"></span>Persentage </label>
                                      <label for="amount"  class="c-input c-radio">
                                          <input type="radio" @if($order->discount_type == 'amount' || $order->discount == 0) checked="checked" @endif  name="type" id="amount" value="amount">
                                          <span class="c-indicator"></span>Amount </label>

                                  </div>
                                  <div class="col-sm-2">
                                      <input type="number" step="any" id="discount_rate" value="{{$order->discount}}" name="discount" style="width:80px;">
                                  </div>
                                  <div class="col-sm-2" id="typeSympole" >
                                      @if($order->discount_type == 'amount' || $order->discount == 0)
                                          <h5> Pound(s)</h5>
                                      @else
                                          <h5> %</h5>
                                      @endif

                                  </div>

                              </div>
                          </div>
                      </div>
                      <hr>
                      <div class="row">
                          <div class="col-lg-5">
                              <div class="col-lg-12">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">Taxes & Charges
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px">
                                  <div class='input-group date' style="display: inline;" id=''>
                                       <select  id="tax_and_charges" name="tax_and_charges" class="form-control" >
                                        <option disabled selected>Select Taxs and Charges</option>
                                          
                                            @foreach($taxs as $tax)
                                          
                                        <option  value="{{$tax->id}}" @if($order->tax_and_charges == $tax->id)selected @endif>{{$tax->title}}</option>
                                              @endforeach
                                                    <input type="number" min="0" step="0.01" value="{{$taxs_rate}}" hidden="hidden" id="taxs_rate">
                                                    <input type="number" min="0" step="0.01" value="{{$taxs_amount}}" hidden="hidden" id="taxs_amount">

                                                  </select>
                                  </div>
                              </div>
                          </div>
                          <div class="col-lg-5">
                              <div class="col-lg-12" style="">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">Shipping Rule
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px">
                                              <div class='input-group date' style="display: inline;" id=''>
                                                      <select  id="shipping_rule" name="shipping_rule" class="form-control" >
                                                        <option disabled selected>Select Shipping Rule</option>
                                              @foreach($shipping_rules as $rule)
                                                
                                                <option  value="{{$rule->id}}" @if($order->shipping_rule == $rule->id)selected @endif>{{$rule->shipping_rule_label}}</option>
                                              @endforeach
                                      <input type="number" min="0" step="0.01" value="{{$shipping_rule_rate}}" hidden="hidden" id="shipping_rule_rate">
                                                  </select>
                                              </div>
                                          </div>
                          </div> 

                      </div>
                      



                     <div class="row">
                       <div class="col-lg-6" style="left:250px;width: 515px ; height:150px;padding:10px;border: 2px solid gray; margin-top: 100px;">

                              <div class="col-lg-12" style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                  <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Grand Total
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0">
                                  <div class='input-group' id='' style="display: inline;  text-align: right">
                                    <input readonly type="text" name="grand_total_amount" style="height: 40px;text-align: center; "  id="grand_total" class="form-control" value="{{$grand_total_amount}}">

                                  </div>
                              </div>


                          </div>
                      </div>

                      <div class="row">
                          <div class="col-sm-32"><button id="save" type="submit" style="margin-left: 12px" class="btn btn-primary">Save</button></div>
                      </div>


                </div>
                {!! Form::close() !!}
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>



    <script src="{{url('components/components/select2/dist/js/select2.js')}}"></script>

<script type="text/javascript">
  var response_array=[];
  var items_array = <?php echo json_encode($products); ?>;
  var selected =[];
  var items_options=[];
  var selected_option = $(".selectpickerr:first").attr('id');
  var count_of_selectpickers = '<?php echo $i;?>';
  var select_ids = $('.selectpickerr').map(function() {
    return $(this).attr('id');
  });
  $(document).ready(function() {
      $('input[type=radio][name=type]').change(function() {
            if (this.value == 'persentage') {
                $('#typeSympole').html('<h5> %</h5>');
            }
            else if (this.value == 'amount') {
                $('#typeSympole').html('<h5> Pound(s)</h5>');
            }
                    grandTotalAmount();
      });
      $('#discountEnabler').change(function(){
            if(!$(this).is(":checked")){
                $('#discount_rate').val(0);
            }
            $('#discountBox').toggle();
            grandTotalAmount();
      });
      $(document).on('keyup','#discount_rate',function () {
          if($('input[name=type]:checked').val() == 'persentage')
          {
              if ( (parseInt($(this).val()) <= 100 && parseInt($(this).val()) >= 0) || $(this).val() == '' ){
                ;
              }
              else
              {

                  $(this).val($(this).data("old"));
              }
          }else if($('input[name=type]:checked').val() == 'amount'){
              if ($(this).val() == '' ){
                $('#discount_rate').val(0);
              }
          }
          grandTotalAmount();
      });


      $.each(select_ids,function(index,value){
            $('#'+value).select2({
                  matcher: matchCustom,
                  templateResult: formatCustom
            });
          $('#'+value).attr('disabled',true);
      });
      grandTotalAmount();
  });

  $('#save').on('click',function(){
    $.each(select_ids,function(index,value){
          $('#'+value).select2({
                matcher: matchCustom,
                templateResult: formatCustom
          });
        $('#'+value).attr('disabled',false);
    });
  });


  $.each(items_array, function( index, value ) {
       items_options.push('<option id="option-'+value.id+'" data-foo="' +value.name_en +'" value="' + value.id + '">' + value.name + '</option>');
  });

    if (selected_option === undefined){
       selected_option = $(".selectpickerr:last").attr('id');
    }



    // getting product details on selecting item
    $(document).on('change', '.selectpickerr', function(e){
      var attr_id = $(this).closest('tr').attr('id');
      $('#'+attr_id+' select').select2('destroy').select2({
        matcher: matchCustom,
        templateResult: formatCustom
      });
      var item= e.target.value;
      var cTypeIncrementNum = parseInt(attr_id.match(/\d+/g), 10) + 1;      
      $.ajax({
        method: 'GET',
        url: '{!! route('getItemDetails') !!}',
        data: {'item' : item},
        success: function(response){
          if(response.product_variations.length > 0){
            var newRow = $("<tr>");
            var cols = "";
            cols += "<td ><label>Variations Of ( <span style='color: red;'><b>'#"+response.parent_name+"'</b></span> )</label></td>";
            var id = $('#tblrownew0 tr:last').attr('id');
            newRow.append(cols);
            $("table.order-list").append(newRow);
            var last_id = $("#" + id+" .selectpickerr").attr("id");
            var last_qty_id = $("#" + id+" .qty").attr("id");
            var last_rate_id = $("#" + id+" .rate").attr("id");
            $("#"+last_id).attr('disabled',true);
            $("#"+last_qty_id).attr('disabled',true);
            $("#"+last_rate_id).attr('disabled',true);

            $.each(response.product_variations,function(index,value){
               var newRow = $("<tr style='background-color: #fbfbfb;' id='field"+cTypeIncrementNum+"'>");
               var cols = "";
              var lastid = $("#" + id+" .selectpickerr").attr("id");
               selected.push($('#'+lastid).val());
               cols += '<td><select required name="items[]"   style="width: 240px;margin-left: 80px;"  id="items'+cTypeIncrementNum+'" class="form-control selectpickerr select2"><option value="'+value.item_id+'" data-foo="'+value.item_name+'" selected id="option-'+value.item_id+'">'+value.item_name+'</option></select></td>';
               cols += '<td><input type="number" class="form-control qty" min="0" value="1"   id="qty" name="quantity[]"/></td>';
               cols += '<td><input type="number" class="form-control item_rate" min="0" value="'+response.item_rate+'" step="0.01"  id="rate" name="rate[]"/></td>';
               cols += '<td><input type="number" class="form-control total" min="0" value="'+response.item_rate+'" step="0.01" disabled id="total_amount" name="total_amount[]"/></td>';

               cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
               newRow.append(cols);
               $("table.order-list").append(newRow);
                    cTypeIncrementNum++;
                // }
                $('#'+attr_id+' #rate').val('0');
                $('#'+attr_id+' #qty').val('0');

            });
          }else{
           $('#'+attr_id+' #rate').val('');
           $('#'+attr_id+' #rate').val(response.item_rate); 
           var rate = $('#'+attr_id+' #rate').val();
           var qty = $('#'+attr_id+' #qty').val();
           var total_amount = rate * qty;
           $('#'+attr_id+' #total_amount').val(Math.round(total_amount * 100) / 100);
          }

          // $('#'+attr_id+' #rate').attr('disabled',true);
          grandTotalAmount();
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
      });
    });

    // on change shipping rules
    $('#shipping_rule').on('change',function(){
        var shipping_rule = $('#shipping_rule').val();
        $.ajax({
          method: 'GET',
          url: '{!! route('shippingruleRate') !!}',
          data: {'shipping_rule' : shipping_rule},
          success: function(response){
            $('#shipping_rule_rate').val(response.rate);
            grandTotalAmount();
          },
          error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
          }
        });
    });

    // on change taxs and charges
    $('#tax_and_charges').on('change',function(){
      var tax = $('#tax_and_charges').val();
      $.ajax({
        method: 'GET',
        url: '{!! route('taxsValue') !!}',
        data: {'tax' : tax},
        success: function(response){
            if(response.type == 'Actual'){
                $('#taxs_amount').val(response.amount);
                $('#taxs_rate').val(0);
            }else{
                $('#taxs_rate').val(response.rate);
                $('#taxs_amount').val(0);

            }
            grandTotalAmount();
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
        }
      });
    });

    // on change the quantity values
    $(document).on('input', '.item_rate', function(e){
        var attr_id = $(this).closest('tr').attr('id');      
        var rate = $('#'+attr_id+' #rate').val();
        var qty = $('#'+attr_id+' #qty').val();
        var total_amount = rate * qty;
        $('#'+attr_id+' #total_amount').val(Math.round(total_amount * 100) / 100);
        grandTotalAmount();
    });

    // on change the quantity values
    $(document).on('input', '.qty', function(e){
        var attr_id = $(this).closest('tr').attr('id');      
        var rate = $('#'+attr_id+' #rate').val();
        var qty = $('#'+attr_id+' #qty').val();
        var total_amount = rate * qty;
        $('#'+attr_id+' #total_amount').val(Math.round(total_amount * 100) / 100);
        grandTotalAmount();
    });


 // getting the grand total amount
  function grandTotalAmount(){
      var grand_total_amount=0;
      var idArray = [];
      var shipping_rule_rate ;
      var tax_and_charges_amount ;
      $('.total').each(function(value) {
         idArray.push(this.value);
      });
      idArray.clean("");
      $.each(idArray, function( index, value ) {
          grand_total_amount += parseFloat(value);          
      });

      var discount_rate_value = $('#discount_rate').val();
      var discount_type = $('input[name=type]:checked').val();
      if(discount_type == 'persentage'){
          discount_rate_value = discount_rate_value/100;
          discount_rate_value *=grand_total_amount;
          grand_total_amount -=parseFloat(discount_rate_value);
      }else if(discount_type =='amount'){
        grand_total_amount -= parseFloat(discount_rate_value);
      }
      var tax_and_charges_rate = $('#taxs_rate').val();
      tax_and_charges_rate = tax_and_charges_rate/100;
      tax_and_charges_rate *= grand_total_amount;
      grand_total_amount += tax_and_charges_rate;
      shipping_rule_rate = $('#shipping_rule_rate').val();
      grand_total_amount +=parseFloat(shipping_rule_rate);
      tax_and_charges_amount=$('#taxs_amount').val();
      grand_total_amount +=parseFloat(tax_and_charges_amount);
      $('#grand_total').val(Math.round(grand_total_amount * 100) / 100);
        if($('#grand_total').val() <0){
          $('#grand_total').val(0);
        }
  }

      $(function () {

          $('#datetimepicker1').datetimepicker({
              icons: {
                  time: 'zmdi zmdi-time',
                  date: 'zmdi zmdi-calendar',
                  up: 'zmdi zmdi--up',
                  down: 'zmdi zmdi--down',
                  //previous: 'glyphicon glyphicon-chevron-left',
                  previous: 'zmdi zmdi-backward',
                  next: 'zmdi zmdi-right',
                  today: 'zmdi zmdi-screenshot',
                  clear: 'zmdi zmdi-trash',
                  close: 'zmdi zmdi-remove'
              }
          });
      });

       $(document).ready(function () {
           var counter = 0;

           $("#addrow").on("click", function () {
               var newRow = $("<tr>");
               var cols = "";
               var id = $('#tblrownew0 tr:last').attr('id');
               // console.log(id);
               var lastid = $("#" + id+" .selectpickerr").attr("id");
               $('.selectpickerr')
               var selected_ids=[];
               $(".selectpickerr").each(function(){
                    selected_ids[i++] =  $(this).val(); //this.id
                });
                selected_ids.clean(undefined);

             
               if($('#' + id + ' #'+lastid).val() == null || $('#'+id+' #qty:last').val() == null){
                }else{
                   cols += '<td><select required name="items[]" style="width:300px;"  id="items1" class="form-control selectpickerr select2" /></td>';
                   cols += '<td><input type="number" class="form-control qty" min="1" value="1"   id="qty" name="quantity[]"/></td>';
                   cols += '<td><input type="number" class="form-control item_rate" min="0" value="0.00" step="0.01"  id="rate" name="rate[]"/></td>';
                   cols += '<td><input type="number" class="form-control total" min="0" value="0.00" step="0.01" disabled id="total_amount" name="total_amount[]"/></td>';
                   cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
               newRow.append(cols);
               $("table.order-list").append(newRow);
               counter++;
               var contentTypeInput = $('#tblrownew0 tr:last').prop('id');
               var cTypeIncrementNum = parseInt(id.match(/\d+/g), 10) + 1;
               $('#tblrownew0 tr:last').attr('id', 'field' + cTypeIncrementNum);
               $('#tblrownew0 tr:last #items1').attr('id','items' + cTypeIncrementNum);          
                $('#items'+cTypeIncrementNum).select2({
                      matcher: matchCustom,
                      templateResult: formatCustom
               });
               var new_items_options=[];
               var selected_item_to_remove=[];
                $.each(selected_ids, function( index, value ) {
                 var text_value = $('#items'+1+ ' #option-'+value).text();
                 var data_subtext = $('#items'+1+ ' #option-'+value).attr('data-foo');
                 // console.log(data_subtext);
                  selected_item_to_remove.push('<option id="option-'+value+'" data-foo="' + data_subtext +'" value="' + value + '">' + text_value + '</option>');
                });
                new_items_options = items_options.filter( function( el ) {
                  return !selected_item_to_remove.includes( el );
                } );
                // console.log(new_items_options);

                $('#items'+cTypeIncrementNum).append('<option value="-1" data-foo="Select Item" disabled selected>Select Item</option>');
                $.each(new_items_options, function( index, value ) { 
                  $('#items'+cTypeIncrementNum).append(value);
                });
                
                $('#items'+cTypeIncrementNum).select2("destroy").select2({
                  matcher: matchCustom,
                  templateResult: formatCustom
                });
               selected = []; 

              }
           });



            $("table.order-list").on("click", ".ibtnDel", function (event) {  
             if($(this).attr('id') !=undefined){
              var parent_id = $(this).attr('id').match(/\d+/); // 123456  
              var trlength=$('tr.'+parent_id).length;  
              if($('#tblrownew0 tr').length>trlength+1){
                $('tr.'+parent_id+' .selectpickerr').each(function(){
                  var item_id = $('tr.'+parent_id+' .selectpickerr').attr('id'); 
                  $('#'+item_id).select2("destroy").select2({
                    matcher: matchCustom,
                    templateResult: formatCustom
                  });
                });
                $("tr."+parent_id).remove();   
              }else{
                  return false;
              }
             }
              var count =  $('#tblrownew0 tr').length-1;
              if(count >=1 ){
                  var tr_id = $(this).closest("tr").attr('id');
                  var selectpicker_id =$("#" + tr_id+" .selectpickerr").attr("id");
                  var removed_option_value = document.getElementById(selectpicker_id).value;
                  var text_value = $('#'+selectpicker_id+ ' #option-'+removed_option_value).text();
                  var data_subtext = $('#'+selectpicker_id+ ' #option-'+removed_option_value).attr('data-foo');
                   // var item_to_add = '<option id="option-'+removed_option_value+'" data-foo="' + data_subtext +'" value="' + removed_option_value + '">' + text_value + '</option>';
                   $(this).closest("tr").remove();  
                   var ids = [];   
                   var not_selected_options=[];
                   // getting ids of selects that have class .selectpickerr
                   $(".selectpickerr").each(function(){
                       ids[i++] =  $(this).attr("id"); //this.id
                   });
                   ids.clean(undefined);
                   // after remove the row looping over the ids and check if the count of the first lists > current select list i append it
                   $.each(ids,function(index,value){
                     var optionExists = ($('#'+value +' option[value=' + removed_option_value + ']').length > 0);
                     if(!optionExists)
                     {
                        $("#"+value).append('<option id="option-'+removed_option_value+'" data-foo="' + data_subtext +'" value="' + removed_option_value + '">' + text_value + '</option>');
                        $('#'+value).select2("destroy").select2({
                            matcher: matchCustom,
                            templateResult: formatCustom
                        });
                     }
                     counter -= 1

                   });
              } 
            });
      });


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

<!-- JAVASCRIPT AREA -->
</body>
</html>