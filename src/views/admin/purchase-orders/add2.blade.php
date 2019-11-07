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
                      Add Purchase Order
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

               
                <div class="card card-block" style="width: 92%">
                    
                  <div class="row">


                        <div class="col-lg-6">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">Company :<span style="color:red;">*</span>
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class='input-group date' id='' style="display: inline;">
                                <select required name="company_id" id="company_id" class="form-control" >
                                    <option value="-1" disabled selected>Select Company</option>
                                    <?php foreach ($companies as $company) { ?>
                                    <option value="{{$company->id}}">{{$company->name_en}}</option>
                                    <?php } ?>
                                  </select>
                              </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">Warehouse : <span style="color:red;">*</span>
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class='input-group date' id='' style="display: inline;">
                                <select required name="warehouse_id" id="warehouse_id" class="form-control" >
                                    <option value="-1" disabled selected>Select Warehouse</option>
                                    <?php foreach ($warehouses as $warehouse) { ?>
                                    <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                    <?php } ?>
                                  </select>
                              </div>
                            </div>
                        </div>


</div>

                  <div class="row"> 
                        <div class="col-lg-6">
                          <div class="col-sm-12">
                            <label style="margin-bottom: 0;"  class="form-group" for="from">Date of Delivery:<span style="color:red;">*</span>
                            </label>
                          </div>
                          <div class="col-sm-12" >
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' autocomplete="off" name="required_by_date" id="required_by_date" placeholder='dd/mm/yyyy  h:i:m'  class="form-control"/>
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
                                <tr id="field1">
                                    <td class="col-sm-4">
                                      <div class='input-group date' id='' style="display: inline;">
                                             <select required name="items[]"  style="width:300px;" id="items1" class="form-control selectpickerr select2" style="" >
                                              <option value="-1" disabled  data-foo="Select Item" selected>Select Item</option>
                                                @foreach ($products as $product)
                                              <option value="{{$product->id}}" @if($item->id == $product->id)selected @endif data-foo="{{$product->name_en}}" id="option-{{$product->id}}">{{$product->name}}</option>
                                              @endforeach
                                              </select>
                                      </div>  
                                                                  
                                    </td>
                                    <td class="col-sm-2">
                                        <input type="number"  min="1"  @if(count($variation_childs)>0) disabled  min="0" value="0" @endif   name="quantity[]" value="1" id="qty"  class="form-control qty"/>
                                    </td>
                                    <td class="col-sm-2">
                                        <input @if(count($variation_childs)>0) value="0" disabled @else value="{{$item_rate}}" @endif  step="0.1" min="0" type="number"  name="rate[]" id="rate"   class="form-control item_rate "/>
                                    </td>
                                    <td class="col-sm-3">
                                        <input type="number" @if(count($variation_childs)>0) value="0" disabled @else value="{{$item_rate}}" @endif  disabled="disabled"  name="total_amount[]" id="total_amount" class="form-control total"/>
                                    </td>
                                    <!-- <td class="col-sm-2"><a class="deleteRow"></a>

                                    </td> -->
                                  </tr>

                                 
                                  @if(count($variation_childs)>0)
                                    <tr id="label"> 
                                      <td>      
                                          <label for="">Variations Of ( <span style="color: red;"><b>#{{$item->name_en}}</b></span> )</label>
                                      </td>
                                    </tr>
                                    <?php $i = 2; ?>      
                                     @foreach($variation_childs as $child)
                                         <tr id="field{{$i}}" style="background-color: #fbfbfb;">
                                              <td class="col-sm-3">
                                                  <div class='input-group date' id='' style="display: inline;">
                                                         <select required name="items[]" style="width: 240px;margin-left: 80px;" id="items{{$i}}" class="form-control selectpickerr select2" style="" >
                                                          <option value="{{$child->id}}" @if($item->id == $child->id)selected @endif data-foo="{{$child->name}}" id="option-{{$child->id}}">{{$child->name_en}}</option>
                                                          </select>
                                                  </div>                                                     
                                              </td>
                                              <td class="col-sm-2">
                                                  <input type="number"  min="1"    name="quantity[]" value="1" id="qty"  class="form-control qty"/>
                                              </td>
                                              <td class="col-sm-2">
                                                  <input  value="{{$item_rate}}" step="0.1" min="0" type="number"  name="rate[]" id="rate"   class="form-control item_rate "/>
                                              </td>
                                              <td class="col-sm-3">
                                                  <input type="number"  disabled="disabled"  name="total_amount[]" value="{{$item_rate}}" id="total_amount" class="form-control total"/>
                                              </td>
                                              <td class="col-sm-1"><input type="button" id="variation_child_del{{$i}}" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>
                                         </tr>
                                         <?php $i++;?>
                                     @endforeach
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

                                            <div class="row">
                                                <div class="col-sm-2 form-group" style="margin-left: 20px;">
                                                    <b>Discount</b>
                                                    <input type="checkbox" id="discountEnabler"  data-plugin="switchery" data-color="#ff5d48"/>

                                                </div>
                                                <div class="col-sm-6" id="discountBox" style="display: none;">
                                                    <div class="row">
                                                        <div class="col-sm-6" style="margin:0">
                                                            <label for="persentage" class="c-input c-radio">
                                                                <input type="radio" name="type" id="persentage" value="persentage" >
                                                                <span class="c-indicator"></span>Persentage </label>
                                                            <label for="amount"  class="c-input c-radio">
                                                                <input type="radio" name="type" checked="" id="amount" value="amount" >
                                                                <span class="c-indicator"></span>Amount </label>

                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="number" step="any" id="discount_rate" value="0" name="discount_rate" style="width:80px;">
                                                        </div>
                                                        <div class="col-sm-2" id="typeSympole" >

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                      <hr>
                      <div class="row">
                          <div class="col-lg-6">
                              <div class="col-lg-12">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">Taxes & Charges
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px">
                                  <div class='input-group date' style="display: inline;" id=''>
                                       <select  id="tax_and_charges" name="tax_and_charges" class="form-control" >
                                                        <option disabled selected>Select Taxs and Charges</option>

                                              @foreach($taxs as $tax)
                                                      <option  value="{{$tax->id}}">{{$tax->title}}</option>
                                              @endforeach
                                                    <input type="number" min="0" step="0.01" value="0" hidden="hidden" id="taxs_rate">
                                                    <input type="number" min="0" step="0.01" value="0" hidden="hidden" id="taxs_amount">

                                                  </select>
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
                                                      <select  id="shipping_rule" name="shipping_rule" class="form-control" >
                                                        <option disabled selected>Select Shipping Rule</option>

                                              @foreach($shipping_rules as $rule)
                                                      <option  value="{{$rule->id}}">{{$rule->shipping_rule_label}}</option>
                                              @endforeach
                                      <input type="number" min="0" step="0.01" value="0" hidden="hidden" id="shipping_rule_rate">
                                                  </select>
                                              </div>
                                          </div>
                          </div> 

                      </div>
<hr>
              
                     <div class="row">
                       <div class="col-lg-6" style="left:250px;width: 515px ; height:150px;padding:10px;border: 2px solid gray; margin-top: 100px;">

                              <div class="col-lg-12" style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                  <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Grand Total
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0">
                                  <div class='input-group' id='' style="display: inline;  text-align: right">
                                    <input readonly type="text" name="grand_total_amount" style="height: 40px;text-align: center; "  id="grand_total" class="form-control" value="0">

                                  </div>
                              </div>


                          </div>
                      </div>

                      <div class="modal fade bd-example-modal-lg" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" data-backdrop="false" aria-hidden="true">

                      </div>
                      <input type="text" hidden="" name="product_id" id="product_id" @if(isset($_GET['product'])) value="{{$_GET['product']}}" @else value="0" @endif>

                      <div class="row">
                          <div class="col-sm-32"><button type="button" id="save" style="margin-left: 12px" class="btn btn-primary">Save</button>
                            <img src="{{url('public/admin/images/pleasewait.gif')}}" id="myElem" style="display: none;width: 50px;height: auto;" alt=""></div>
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
  var has_variant_items_id = <?php  if(isset($item->has_variants) && $item->has_variants ==1){
            echo 1;
  }else{
    echo 0;
  } ?>;
  if(has_variant_items_id  === 1){
    $('#item1').val("");
  }
    $(document).ready(function() {
      $('#typeSympole').html('<h5> Pound(s)</h5>');
        $('input[name=type]').change(function() {
              if (this.value == 'persentage') {
                $('#discount_rate').attr({
                    "max" : 100        // values (or variables) here
                });
                $('#typeSympole').html('<h5> %</h5>');
            }
            else if (this.value == 'amount') {
                $('#discount_rate').removeAttr( "max" );
                $('#typeSympole').html('<h5> Pound(s)</h5>');
            }

            grandTotalAmount();

        });
        $(document).on('keydown','#discount_rate',function () {
            // Save old value.
            if($('input[name=type]:checked').val() == 'persentage' && $(this).val() >= 0 && $(this).val() <= 100)
            {
                $(this).data("old", $(this).val());
            }
            grandTotalAmount();
        });


        $(document).on('keyup','#discount_rate',function () {
            if($('input[name=type]:checked').val() == 'persentage')
            {
                if ( (parseInt($(this).val()) <= 100 && parseInt($(this).val()) >= 0) || $(this).val() == '' )
                    ;
                else
                {
                    $(this).val($(this).data("old"));
                }
            }
            grandTotalAmount();
        });

        $('#discountEnabler').change(function(){
            $('#discountBox').toggle();
        });
    });



  var purchase_product_id = <?php echo json_encode($item);?>;
  var response_array=[];
  var items_array = <?php echo json_encode($products); ?>;
  var selected =[];
  var items_options=[];
  var selected_option = $(".selectpickerr:first").attr('id');
$('#save').on('click',function(){
    var discount_rate = $('#discount_rate').val();
    var discount_type = $('input[name=type]:checked').val();
    var company_id=$('#company_id').val();
    var warehouse_id = $('#warehouse_id').val();
    var required_by_date = $('#required_by_date').val();
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

    var rate = [];
    $(".item_rate").each(function(i, sel){
        var selectedVal = $(sel).val();
        rate.push(selectedVal);
     });
    var tax = $('#tax_and_charges').val();
    var shipping_rule = $('#shipping_rule').val();
    if(company_id == -1 || warehouse_id == -1 || required_by_date =='' || items.length == 0 || quantity.length == 0 || rate.length ==0){
      alert('Check Required Inputs');
      return false;
    }
   
    if($('#items1').val() == null){
          alert('Please Enter Items');
          return false;
    }

var product_id = document.getElementById('product_id').value;
       $.ajax({          
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{URL::to('admin/postPurchaseOrder')}}",
        type: "POST",
        data: {'company_id':company_id,'warehouse_id':warehouse_id,'required_by_date':required_by_date,'items':items,'quantity':quantity,'discount':discount_rate,'discount_type':discount_type,'tax_and_charges':tax,'shipping_rule':shipping_rule,'rate':rate,'product_id':product_id
        },
        success: function (response) {
          if(response.data == 'required_by_date_incorret'){
            alert('Required By Date Field Must Be A Date After Today');
            return false;
          }
          if(response.data == 'grand_total_amount_incorrect'){
            alert('Grand Total amount is in-correct');
            return false;
          }
          if(response.data == 'total_amount_incorrect'){
            alert('Total Amount Of Item Is In-correct');
            return 'false';
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
                      
            // location.reload(); // then reload the page.(3)
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }
    }); 

});
  $(document).ready(function() {
    grandTotalAmount();
var variations = <?php echo json_encode($variation_childs); ?>;
      $('#items1').select2({
        matcher: matchCustom,
              templateResult: formatCustom
      });

      // if($('#items1').val()){
      //   if(variations.length > 0){
      //     $('#variant_select_div1').css('display','block');
      //         $.each(variations, function( index, value ) {
      //           var optionExists = ($('#variant_select1'+' option[value=' + value.id + ']').length > 0);
      //           if(!optionExists)
      //           {
      //               $('#variant_select1').append('<option value="'+value.id+'" id="variant'+value.id+'">'+value.name_en+'</option>'); 
      //           }  
      //         });           
      //     // $('#variant_select1').append()
      //     // cols += '<select required name="items[]" style="width:300px;"  id="items1" class="form-control selectpickerr select2" /></td>';

      //   }
      if($('#items1').val()){
        $('#items1').attr('disabled',true);
      }
  });


  $.each(items_array, function( index, value ) {
       items_options.push('<option id="option-'+value.id+'" data-foo="' +value.name_en +'" value="' + value.id + '">' + value.name + '</option>');
  });

if (selected_option === undefined){
   selected_option = $(".selectpickerr:last").attr('id');
}




    $(document).on('change', '.selectpickerr', function(e){
     
      var attr_id = $(this).closest('tr').attr('id');
      var item= e.target.value;
      var cTypeIncrementNum = parseInt(attr_id.match(/\d+/g), 10) + 1;      
      $.ajax({
        method: 'GET',
        url: '{!! route('getItemDetails') !!}',
        data: {'item' : item},
        success: function(response){
          if(response.product_variations.length > 0){
            var newRow = $("<tr class='"+item+"'>");
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
            $("#"+last_rate_id).val(0);

            $.each(response.product_variations,function(index,value){
               var newRow = $("<tr style='background-color: #fbfbfb;' class='"+item+"' id='field"+cTypeIncrementNum+"'>");
               var cols = "";
               var lastid = $("#" + id+" .selectpickerr").attr("id");
               selected.push($('#'+lastid).val());
               cols += '<td><select required name="items[]"   style="width: 240px;margin-left: 80px;"  id="items'+cTypeIncrementNum+'" class="form-control selectpickerr select2"><option value="'+value.item_id+'" data-foo="'+value.item_name+'" selected id="option-'+value.item_id+'">'+value.item_name+'</option></select></td>';
               cols += '<td><input type="number" class="form-control qty" min="0" value="1"   id="qty" name="quantity[]"/></td>';
               cols += '<td><input type="number" class="form-control item_rate" min="0" value="'+value.rate+'" step="0.01"  id="rate" name="rate[]"/></td>';
               cols += '<td><input type="number" class="form-control total" min="0" value="'+value.rate+'" step="0.01" disabled id="total_amount" name="total_amount[]"/></td>';

               cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
               newRow.append(cols);
               $("table.order-list").append(newRow);
               $('#items'+cTypeIncrementNum).select2({
                 matcher: matchCustom,
                       templateResult: formatCustom
                });
                    cTypeIncrementNum++;
                // }
                $('#'+attr_id+' #rate').val('0');
                $('#'+attr_id+' #qty').val('0');
                $('#'+attr_id+' #rate').attr('disabled',true);
                $('#'+attr_id+' #qty').attr('disabled',true);
                if($('#'+attr_id +' .ibtnDel').length ==1){
                  $('#'+attr_id +' .ibtnDel').attr('id','del'+item);
                }else{
                    $('#'+attr_id).append('<td><input type="button" id="del'+item+'"  class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>');
                }
            });
          }else{
             $('#'+attr_id+' #rate').val('');
             $('#'+attr_id+' #rate').val(response.item_rate); 
             var rate = $('#'+attr_id+' #rate').val();
             var qty = $('#'+attr_id+' #qty').val();
             var total_amount = rate * qty;
             $('#'+attr_id+' #total_amount').val(Math.round(total_amount * 100) / 100);
          }

          
        
          grandTotalAmount();
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
      });
    });


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
    // console.log(shipping_rule_rate);
    });
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
        grand_total_amount -= discount_rate_value;
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