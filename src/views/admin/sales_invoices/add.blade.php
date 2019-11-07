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
                        Purchase Invoices
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Purchase Invoices
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

                <div class="card card-block" style="width: 82%">
                    
                  <div class="row">


                        <div class="col-lg-5">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">Company
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class='input-group date' id='' style="display: inline;">
                                <select required name="company_id" id="company_id" class="form-control" >
                                    <option value="-1" disabled selected>Select Company</option>
                                    <?php foreach ($companies as $company) { ?>
                                    <option value="{{$company->id}}" @if($purchase_order->company_id == $company->id)selected @endif>{{$company->name_en}}</option>
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
                                <input type='text'  disabled="disabled" value="{{date ('m/d/Y h:i:m',strtotime($purchase_order->required_by_date))}}" class="form-control"/>
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

                            <table id="myTable"  class=" table order-list" style="width: 90%;">
                            <thead>
                                <tr>
                                    <td>Items</td>
                                    <td>Quantity</td>
                                    <td>rate</td>
                                    <td>Total Amount</td>
                                  
                                </tr>
                            </thead>
                            <tbody id="tblrownew0" >
                                <?php $i=1; ?>
                            @foreach($item_details as $order_item)
                                <tr id="field{{$i}}">
                                    <td class="col-sm-3">
                                      <div class='input-group date' id='' style="display: inline;">
                                        <input type="text" class="selectpickerr"  hidden="hidden" name="items[]" value="{{$order_item->item_id}}">
                                          <input type="text" disabled="disabled" name="" class="form-control" value="{{$order_item->product_name}}">
                                      </div>                                    
                                    </td>

                                    <td class="col-sm-2">
                                        <input type="number" disabled="disabled"  min="1"    name="" value="{{$order_item->qty}}" id="qty"  class="form-control    qty"/>
                                        <input type="text"  hidden="hidden" name="qty[]" value="{{$order_item->qty}}">

                                    </td>
                                   
                                    <td class="col-sm-2">
                                        <input  disabled="disabled"   value="{{$order_item->rate}}"    name="rate[]" id="rate"   class="form-control"/>
                                    </td>
                                    <td class="col-sm-2">
                                        <input type="number"  disabled="disabled"  min="0"  step="0.01" name="total_amount[]"  value="{{$order_item->total_amount}}"    id="total_amount" class="form-control total"/>
                                    </td>
                                 </tr>
                                <?php $i++; ?>


                            @endforeach

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
                   <div class="row" >
                        <div class="col-lg-12">

                        <div class="col-lg-2">
                            <label style="margin-bottom: 0;" class="form-group" for="from">Discount
                            </label>
                        </div>
                        <div class="col-lg-8" style="margin-top: 0px">
                                <div class="col-lg-4">
                                  <label for="">Total Discount</label>
                                  <input type="text" disabled="disabled" class="form-control" value="{{$discount_rate}} LE">
                                </div>
                                <div class="col-lg-4">
                                  <label for="">Discount Per Unit</label>
                                  <input type="text" disabled="disabled"  class="form-control" value="{{$discount_per_unit}} LE">
                                  <input type="text" hidden="hidden" name="discount_per_unit" id="discount_per_unit"  class="form-control" value="{{$discount_per_unit}}">
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
                                          
                                        <option  value="{{$tax->id}}" @if($purchase_order->tax_and_charges == $tax->id)selected @endif>{{$tax->title}}</option>
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
                                                  <?php $shipping_rule_rate = 0; 
                                                    if($purchase_order->shipping_rule == $rule->id){
                                                        $shipping_rule_rate = $rule->rate;
                                                    }
                                                  ?>
                                                <option  value="{{$rule->id}}" @if($purchase_order->shipping_rule == $rule->id)selected @endif>{{$rule->shipping_rule_label}}</option>
                                              @endforeach
                                      <input type="number" min="0" step="0.01" value="{{$shipping_rule_rate}}" hidden="hidden" id="shipping_rule_rate">
                                                  </select>
                                              </div>
                                          </div>
                          </div> 

                      </div>

                     <div class="row">
                       <div class="col-lg-6" style=" left:250px;width: 515px ; height:150px;padding:10px;border: 2px solid gray; margin-top: 100px;">

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
                       <div class="modal fade bd-example-modal-lg" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-backdrop="false" aria-hidden="true">

                      </div>

                      <div class="row">
                          <div class="col-sm-32"><button id="save" type="submit" style="margin-left: 12px" class="btn btn-primary">Save</button><img src="{{url('public/admin/images/pleasewait.gif')}}" id="myElem" style="display: none;width: 50px;height: auto;" alt=""></div>

                      </div>


                </div>
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
  var selected =[];
  var items_options=[];
  // var selected_option = $(".selectpickerr:first").attr('id');
  var count_of_selectpickers = '<?php echo $i;?>';
  var select_ids = $('.selectpickerr').map(function() {
    return $(this).attr('id');
  });




  $('#save').on('click',function(){
      var company_id=$('#company_id').val();
      var grand_total_amount=$('#grand_total').val();
      var purchase_receipt_id = <?php echo $purchase_receipt_id; ?>;
      var items = [];
      $(".selectpickerr").each(function(i, sel){
          var selectedVal = $(sel).val();
          items.push(selectedVal);
      });
      var quantity = [];
      $(".qty").each(function(i, sel){
          var selectedVal = $(sel).val();
          quantity.push(selectedVal);
       });
      var tax = $('#tax_and_charges').val();
      var shipping_rule = $('#shipping_rule').val();

      $.ajax({          
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "{{URL::to('admin/postPurchaseInvoice')}}",
          type: "POST",
          data: {'company_id':company_id,'items':items,'qty':quantity,'tax':tax,'shipping_rule':shipping_rule,'grand_total_amount':grand_total_amount,'purchase_receipt_id':purchase_receipt_id
          },
          success: function (response) {
            if(response.data == 'purchase_invoice_already_exist'){
              alert('You have purchased an invoice for this item before');

            }

            if(response.data == 'grand_total_incorrect'){
              alert('Grand Total amount is in-correct');
              
            }
       $('#save').attr('disabled',true);
$("#myElem").show().delay(5000).queue(function(n) {
  $(this).hide(); n();
  if($('.email_modal_exist').length <= 0){
      $('#myModal4').append(response.data);
  }
  $('.modal-backdrop.in').modal('hide');
  $('#myModal4').modal('show');
});

              // if($('.email_modal_exist').length <= 0){
              //     $('#myModal4').append(response.data);
              // }
              // $('.modal-backdrop.in').modal('hide');
              // $('#myModal4').modal('show');
              // location.reload(); // then reload the page.(3)
          },
          error: function (jqXHR, textStatus, errorThrown) {
          }
      }); 

  });







  $(document).ready(function() {
      
      grandTotalAmount();
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

      var quantity = [];
      $(".qty").each(function(i, sel){
          var selectedVal = $(sel).val();
          quantity.push(selectedVal);
       });


        var total_accepted_qty=0;
        $.each(quantity,function(index,value){
          total_accepted_qty += parseInt(value);
        });
        var discount = $('#discount_per_unit').val() * total_accepted_qty;
        grand_total_amount -= parseFloat(discount);   


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

 





</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>