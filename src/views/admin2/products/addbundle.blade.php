<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head') 
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

</script>

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
                        Products
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Products
                @endslot
                You are not allowed to access this resource!
                @endcomponent            <!--End Bread Crumb And Title Section -->
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
                {!! Form::open(['url' => '/admin/product_bundle/manage_bundle/'.$bundle_id,
                'method'=>'PATCH',
                 'class'=>'form-hirozontal ',
                 'id'=>'demo-form','files' => true,
                 'data-parsley-validate'=>'']) !!}
                <div class="card card-block">
                    
                     <div style="margin-left: 5px" class="card-text">


                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Name
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id=''>
                                                <input type='text' required name="name" @if(isset($bundle_data->description)) value="{{$bundle_data->description}}" @else value=""  @endif class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Description
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id=''>
                                            <textarea class="form-control"  id="" name="description" rows="3">@if(isset($bundle_data->description)){{$bundle_data->description}} @endif</textarea>                                            </div>
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
                                    <td colspan="1" style="text-align: right;float: center;margin-top: 10px;padding-right: 150px;">
                                        <input type="button" class="btn btn-lg btn-primary " id="addrow" value="+" />
                                    </td>
                                </tr>
                            </thead>
                            <tbody id="tblrownew0">
                              <?php $i=1; ?>
                          @forelse($bundle_meta as $order_item)
                                <tr id="field{{$i}}">
                                    <td class="col-sm-3">
                                      <div class='input-group date' id='' style="display: inline;">
                                             <select required name="items[]"  id="items{{$i}}" class="form-control selectpickerr select2" style="" >
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
                                        <input type="number"  min="1"    name="qty[]" value="{{$order_item->qty}}" id="qty"  class="form-control    qty"/>
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
                                            <input type="number"  min="1"    name="qty[]" value="1" id="qty"  class="form-control qty"/>
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

                      </div>

                      <input type="hidden" value="{{$_GET['product']}}" name="product">


                                <div class="row">
                                    <div class="col-sm-32"><button type="submit" style="margin-left: 12px" class="btn btn-primary">Save</button></div>
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
  // $("#item_group").change(function(e){
  //       var item_group= e.target.value;
  //           $.ajax({
  //               method: 'GET', 
  //               url: '{!! route('getItems') !!}', 
  //               data: {'item_group' : item_group}, 
  //               success: function(response){ 
  //                // response_array.pushValues(response);
  //                if(!$('.selectpickerr:last').is(':selected')){
  //                     $('.selectpickerr:last').select2("destroy").select2({
  //                       matcher: matchCustom,
  //                       templateResult: formatCustom
  //                     });  
  //                     // $('.selectpickerr:last').append('<option value="-1" disabled selected>Select Item</option>' );
  //                     $.each(response, function( index, value ) {
  //                       response_array.push(value);
  //                       items_array.push(value);
  //                          $('.selectpickerr:last').append('<option data-foo="' +value.name_en +'" value="' + value.id + '">' + value.name + '</option>' ).selectpicker("refresh");

  //                     });
  //                }
  //                   // $("#items").val('default');
  //                   // $('#items'+iv).append('<option value="-1" disabled selected>Select Item</option>' );
                  
  //               },
  //               error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
  //                   console.log(JSON.stringify(jqXHR));
  //                   console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
  //               }
  //             });
  // });
  var response_array=[];
  var items_array = <?php echo json_encode($products); ?>;
  var selected =[];
  var items_options=[];
  var selected_option = $(".selectpickerr:first").attr('id');
// $('#save').on('click',function(){
//   // $('#items1').attr('disabled',false);
// });
  $(document).ready(function() {
      $('#items1').select2({
          matcher: matchCustom,
          templateResult: formatCustom
      });
      // if($('#items1').val()){
        // $('#items1').attr('disabled',true);
      // }
  });


  $.each(items_array, function( index, value ) {
       items_options.push('<option id="option-'+value.id+'" data-foo="' +value.name_en +'" value="' + value.id + '">' + value.name + '</option>');
  });

// console.log()
if (selected_option === undefined){
   selected_option = $(".selectpickerr:last").attr('id');
}   

       $(document).ready(function () {
           var counter = 0;

           $("#addrow").on("click", function () {
               var newRow = $("<tr>");
               var cols = "";
               var id = $('#tblrownew0 tr:last').attr('id');
               // console.log(id);
               var lastid = $("#" + id+" .selectpickerr").attr("id");
               selected.push($('#'+lastid).val());

              // var tr_id = $(this).closest("tr").attr('id');
              // var selectpicker_id =$("#" + tr_id+" .selectpicker").attr("id");
              //  var removed_option_id = document.getElementById(selectpicker_id).value;
              // $("#edit-field-service-sub-cat-value option[value=" + title + "]").hide();
               if($('#' + id + ' #'+lastid).val() == null || $('#'+id+' #qty:last').val() == null){
                }else{
               cols += '<td><select required name="items[]" style="width:300px;"  id="items1" class="form-control selectpickerr select2" /></td>';
               cols += '<td><input type="number" class="form-control qty" min="1" value="1"   id="qty" name="qty[]"/></td>';
               cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
               newRow.append(cols);
               $("table.order-list").append(newRow);
               counter++;
               var contentTypeInput = $('#tblrownew0 tr:last').prop('id');
               var cTypeIncrementNum = parseInt(id.match(/\d+/g), 10) + 1;
               $('#tblrownew0 tr:last').attr('id', 'field' + cTypeIncrementNum);
               $('#tblrownew0 tr:last #items1').attr('id','items' + cTypeIncrementNum);          
                $('#items'+cTypeIncrementNum).select2({
                     // data: new_items_options,
                      matcher: matchCustom,
                      templateResult: formatCustom

               });
               var new_items_options=[];
               var selected_item_to_remove=[];
                $.each(selected, function( index, value ) {
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