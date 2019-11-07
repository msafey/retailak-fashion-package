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
                        Purchase Receipts
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Purchase Receipts
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

                <div class="card card-block">
                    
                  <div class="row">


                        <div class="col-lg-5">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">Company
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class='input-group date' id='' style="display: inline;">
                                <select required name="company_id" id="Company" class="form-control" >
                                    <option value="-1" disabled selected>Select Company</option>
                                    <?php foreach ($companies as $company) { ?>
                                    <option value="{{$company->id}}" @if($order->company_id == $company->id)selected @endif>{{$company->name}}</option>
                                    <?php } ?>
                                  </select>
                              </div>
                            </div>
                        </div>


                        
                        <div class="col-lg-5">
                          <div class="col-sm-12">
                            <label style="margin-bottom: 0;"  class="form-group" for="from">Required By Date:
                            </label>
                          </div>
                          <div class="col-sm-12" >
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="required_by_date" value="{{date ('m/d/Y h:i:m',strtotime($order->required_by_date))}}" class="form-control"/>
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
                            @forelse($item_details as $order_item)
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
                                        <input type="number"  min="1" max="100"   name="quantity[]" value="{{$order_item->qty}}" id="qty"  class="form-control    qty"/>
                                    </td>
                                    <td class="col-sm-2">
                                        <input  disabled="disabled"   value="{{$order_item->rate}}"    name="rate[]" id="rate"   class="form-control"/>
                                    </td>
                                    <td class="col-sm-2">
                                        <input type="number"  disabled="disabled"  min="0"  step="0.01" name="total_amount[]"  value="{{$order_item->total_amount}}"    id="total_amount" class="form-control total"/>
                                    </td>
                                    <td class="col-sm-1">
                                      <input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete">
                                    </td>
                                </tr>
                                <?php $i++; ?>


                            @empty
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
                                            <input type="number"  min="1" max="100"   name="quantity[]" value="1" id="qty"  class="form-control qty"/>
                                        </td>
                                        <td class="col-sm-2">
                                            <input  disabled="disabled"  value="0.00"    name="rate[]" id="rate"   class="form-control"/>
                                        </td>
                                        <td class="col-sm-2">
                                            <input type="number"  disabled="disabled"  min="0"  step="0.01" name="total_amount[]"  value="0.00"    id="total_amount" class="form-control total"/>
                                        </td>
                                        <td class="col-sm-1"><a class="deleteRow"></a>

                                        </td>
                                    </tr>


                            @endforelse

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

                      <div class="row">
                          <div class="col-lg-5">
                              <div class="col-lg-12">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">Taxes & Charges
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px">
                                  <div class='input-group date' style="display: inline;" id=''>
                                       <select required id="tax_and_charges" name="tax_and_charges" class="form-control" >
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
                                                      <select required id="shipping_rule" name="shipping_rule" class="form-control" >
                                                        <option disabled selected>Select Shipping Rule</option>
                                              @foreach($shipping_rules as $rule)
                                                  <?php $shipping_rule_rate = 0; 
                                                    if($order->shipping_rule == $rule->id){
                                                        $shipping_rule_rate = $rule->rate;
                                                    }
                                                  ?>
                                                <option  value="{{$rule->id}}" @if($order->shipping_rule == $rule->id)selected @endif>{{$rule->shipping_rule_label}}</option>
                                              @endforeach
                                      <input type="number" min="0" step="0.01" value="{{$shipping_rule_rate}}" hidden="hidden" id="shipping_rule_rate">
                                                  </select>
                                              </div>
                                          </div>
                          </div> 

                      </div>

                     <div class="row">
                       <div class="col-lg-6" style=" float:right;width: 515px ; height:150px;padding:10px;border: 2px solid gray; margin-top: 100px;">

                              <div class="col-lg-12" style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                  <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Grand Total
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0">
                                  <div class='input-group' id='' style="display: inline;  text-align: right">
                                    <input readonly type="text" name="grand_total_amount" style="height: 40px;text-align: center; "  id="grand_total" class="form-control" value="{{$product->grand_total_amount}}">

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

    // console.log()
    if (selected_option === undefined){
       selected_option = $(".selectpickerr:last").attr('id');
    }




    $(document).on('change', '.selectpickerr', function(e){
        var attr_id = $(this).closest('tr').attr('id');
        $('#'+attr_id+' select').select2('destroy').select2({
          matcher: matchCustom,
                templateResult: formatCustom
        });
        // $('#'+value).select2('destroy').select2({
        //   matcher: matchCustom,
        //         templateResult: formatCustom
        // });
        var item= e.target.value;
        $.ajax({
            method: 'GET',
            url: '{!! route('getItemDetails') !!}',
            data: {'item' : item},
            success: function(response){
            // console.log(response.standard_rate);
              $('#'+attr_id+' #rate').val('');
              $('#'+attr_id+' #rate').val(response.standard_rate);
              var rate = $('#'+attr_id+' #rate').val();
              var qty = $('#'+attr_id+' #qty').val();
              var total_amount = rate * qty;
              $('#'+attr_id+' #total_amount').val(Math.round(total_amount * 100) / 100);
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
      $('.total').each(function() {
         idArray.push(this.value);
      });
      idArray.clean("");
      $.each(idArray, function( index, value ) {
          grand_total_amount += parseFloat(value);          
      });

      var tax_and_charges_rate = $('#taxs_rate').val();
      tax_and_charges_rate = tax_and_charges_rate/100;
      tax_and_charges_rate *= grand_total_amount;

      grand_total_amount += tax_and_charges_rate;
      shipping_rule_rate = $('#shipping_rule_rate').val();
      grand_total_amount +=parseFloat(shipping_rule_rate);
      tax_and_charges_amount=$('#taxs_amount').val();
      grand_total_amount +=parseFloat(tax_and_charges_amount);
      // console.log(Math.round(grand_total_amount*100));
      $('#grand_total').val(Math.round(grand_total_amount * 100) / 100);
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
              var numItems = $('.selectpickerr').length;
              var cols = "";
                cols += '<td><select required name="items[]" style="width:300px;"  id="items1" class="form-control selectpickerr select2" /></td>';
                cols += '<td><input type="number" class="form-control qty" min="1" value="1" max="100"  id="qty" name="quantity[]"/></td>';
                cols += '<td><input type="number" class="form-control" min="0" value="0.00" step="0.01" disabled id="rate" name="rate[]"/></td>';
                cols += '<td><input type="number" class="form-control total" min="0" value="0.00" step="0.01" disabled id="total_amount" name="total_amount[]"/></td>';

                cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';


              if(numItems == 0){
                 var newRow = $("<tr id='field1'>");
                  newRow.append(cols);
                  $("table.order-list").append(newRow);
                  counter++;
                  $('#items1').select2({
                      matcher: matchCustom,
                      templateResult: formatCustom
                  });

                  $('#items1').append('<option value="-1" data-foo="Select Item" disabled selected>Select Item</option>');
                  $.each(items_options, function( index, value ) { 
                   $('#items1').append(value);
                  });
                                      
                   $('#items1').select2("destroy").select2({
                        matcher: matchCustom,
                        templateResult: formatCustom
                    });                   
              }else{
                    var newRow = $("<tr>");
                    var select_ids = $('.selectpickerr').map(function() {
                      return $(this).attr('id');
                    });
                    $.each(select_ids,function(index,value){
                      selected.push($('#'+value).val());
                    });
                   var id = $('#tblrownew0 tr:last').attr('id');
                   var lastid = $("#" + id+" .selectpickerr").attr("id");
                    if($('#' + id + ' #'+lastid).val() == null || $('#'+id+' #qty:last').val() == null){
                    }else{
                     
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
                      $.each(selected, function( index, value ) {
                       var text_value = $('#items'+1+ ' #option-'+value).text();
                       var data_subtext = $('#items'+1+ ' #option-'+value).attr('data-foo');
                        selected_item_to_remove.push('<option id="option-'+value+'" data-foo="' + data_subtext +'" value="' + value + '">' + text_value + '</option>');
                      });
                      new_items_options = items_options.filter( function( el ) {
                        return !selected_item_to_remove.includes( el );
                      } );

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
              }         
           });



           $("table.order-list").on("click", ".ibtnDel", function (event) {

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
                // console.log(removed_option_value);
                // var count_of_options;
                // var count_of_all_options = items_options.length;
                // after remove the row looping over the ids and check if the count of the first lists > current select list i append it
                $.each(ids,function(index,value){
                   // count_of_options = document.getElementById(value).length;
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
                  // if(count_of_options < count_of_all_options){
                      
                  // }
                 
                });
                grandTotalAmount();

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








//This Functions related to Select 2 data-foo 
       function stringMatch(term, candidate) {
           return candidate && candidate.toLowerCase().indexOf(term.toLowerCase()) >= 0;
       }

       function matchCustom(params, data) {
           // If there are no search terms, return all of the data
           if ($.trim(params.term) === '') {
               return data;
           }
           // Do not display the item if there is no 'text' property
           if (typeof data.text === 'undefined') {
               return null;
           }
           // Match text of option
           if (stringMatch(params.term, data.text)) {
               return data;
           }
           // Match attribute "data-foo" of option
           if (stringMatch(params.term, $(data.element).attr('data-foo'))) {
               return data;
           }
           // Return `null` if the term should not be displayed
           return null;
       }

       function formatCustom(state) {
           return $(
               '<div><div>' + state.text + '</div><div class="foo">'
                   + $(state.element).attr('data-foo')
                   + '</div></div>'
           );
       }
</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>