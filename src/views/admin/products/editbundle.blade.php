<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />

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


</head>

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
        {!! Form::open(['url' => ['/admin/product_bundles', $bundle->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}

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
                                                <input type='text' required name="name" value="{{$bundle->name}}" class="form-control">
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
                                            <textarea class="form-control"  id="" name="description" rows="3">{{$bundle->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-sm-12">
                                            <label style="margin-bottom: 0;"  class="form-group" for="from">Item Group
                                            </label>
                                        </div>
                                        <div class="col-sm-12" >
                                            <div class='input-group date' id='' style="display: inline;">
                                                <select required name="item_group"  id="item_group" class="form-control" >
                                                    <option value="All Item Groups" disabled selected>Item Group</option>
                                                    <?php foreach ($item_groups as $group) { ?>
                                                        <option value="{{$group->id}}" @if($group->id == $bundle->item_group)selected @endif >{{$group->name_en}}</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                          <!--     <div class="row">
                                <div class="col-lg-6">
                                  <div class="col-sm-12">
                                    <label style="margin-bottom: 0;"  class="form-group" for="from">Items
                                    </label>
                                  </div>
                                  <div class="col-sm-12" >
                                    <div class='input-group date' id='' style="display: inline;">
                                      <select required name="items[]" id="items" class="form-control selectpicker"  data-live-search="true"  data-show-subtext="true">
                                        <option value="-1" disabled selected>Select Item</option>
                                        <?php foreach ($products as $product) { ?>
                                        <option value="{{$product->id}}"  data-subtext="{{$product->name_en}}">{{$product->name}}</option>
                                        <?php } ?>
                                      </select>
                                    </div>
                                  </div>
                                </div>
                                
                              </div> -->
                          <input type="text" id="inc" hidden="hidden" name="inc" value="1">
                              <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12" id="text1" style="display: block">

                                  <div class="row" id="products" class="ItemRow">
                                   

                                     @forelse($bundle_item as $meta)
                                      <div class="row ItemRow" id="field1" style="margin-left: 0px;margin-top: 10px">
                              
                                          <div class="col-sm-4" >
                                            <label for="answers">Items : <span class="required-red">*</span></label>
                                            <select required name="items[]" id="items1" class="form-control selectpicker"  data-live-search="true"  data-show-subtext="true">
                                              <option value="-1" disabled selected>Select Item</option>
                                              <?php foreach ($products as $product) { ?>
                                              <option value="{{$product->id}}" @if($meta->item_id == $product->id)selected @endif data-subtext="{{$product->name_en}}">{{$product->name}}</option>
                                              <?php } ?>
                                            </select>
                                          </div>
                                          <div class="col-sm-4">
                                            <label for="answers">Quantity : <span class="required-red">*</span></label>
                                            <input name="qty[]" id="qty" value="{{$meta->qty}}" type="text" class="form-control" />
                                          </div>
                                          <div class="col-sm-1">
                                              <button class="btn btn-danger" style="margin-top:24px;" type="button" id="removeItem"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                          </div>
                                      </div>
                                      @empty
                                      <div class="row ItemRow" id="field1" style="margin-left: 0px;margin-top: 10px">
                                      
                                          <div class="col-sm-4" >
                                            <label for="answers">Items : <span class="required-red">*</span></label>
                                            <select required name="items[]" id="items1" class="form-control selectpicker"  data-live-search="true"  data-show-subtext="true">
                                              <option value="-1" disabled selected>Select Item</option>
                                              <?php foreach ($products as $product) { ?>
                                              <option value="{{$product->id}}"  data-subtext="{{$product->name_en}}">{{$product->name}}</option>
                                              <?php } ?>
                                            </select>
                                          </div>
                                          <div class="col-sm-4">
                                            <label for="answers">Quantity : <span class="required-red">*</span></label>
                                            <input name="qty[]" id="qty" type="text" class="form-control" />
                                          </div>
                                          <div class="col-sm-1">
                                              <button class="btn btn-danger" style="margin-top:24px;" type="button" id="removeItem"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                          </div>
                                      </div>

                                      @endforelse
                                  </div>
                                  <button style="margin-left: 20px;margin-bottom: 10px;" class="btn btn-primary" type="button" id="newItem"><i class="fa fa-plus"></i></button> &nbsp;&nbsp; Add an extra attribute 
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
<script type="text/javascript">
  
 
    var response_array=[];


      $(function() {
        $('#items'+iv).selectpicker();
      });
      var iv = document.getElementById('inc').value;

     $("#item_group").attr('disabled',true);

     $(document).ready(function () {
         function getLength() {
             var numItems = $('.ItemRow').length;
             if(numItems > 1){
                 $('#removeItem').prop('disabled',false);
             }else{
                 $('#removeItem').prop('disabled',true);
             }
         }
         // getLength();
         $(document).on('click','#removeItem', function(){
             var numItems = $('.ItemRow').length;
             if(numItems > 1){
                 //$('#removeDate').prop('disabled',false);
                 $(this).parent().parent().remove();
             }
         });
         $('#newItem').click(function () {
             // getLength();
             // console.log(iv);
             var iv = document.getElementById('inc').value;
             var id = $('.ItemRow:last').attr('id');
             if($('#' + id + ' #items'+iv).val() == null || $('#'+id+' #qty').val() == null){

             }else{
                 var newClone = $('.ItemRow:last-of-type').clone();
                 newClone.appendTo('#products');
                 var contentTypeInput = $('.ItemRow:last');
                 var cTypeIncrementNum = parseInt($('.ItemRow:last').prop('id').match(/\d+/g), 10) + 1;
                 $('.ItemRow:last').attr('id', 'field' + cTypeIncrementNum);
                 $('.ItemRow:last-of-type #items'+iv).attr('id','items' + cTypeIncrementNum);
                 $('#items'+cTypeIncrementNum).selectpicker();
                 $('#inc').val(cTypeIncrementNum);
                 $('#inc').attr('value', cTypeIncrementNum);

                 $('#items' + cTypeIncrementNum).empty().selectpicker("refresh");
                    // $("#items").val('default');
                 $('#items'+cTypeIncrementNum).append('<option value="-1" disabled selected>Select Item</option>' );

                 $.each(response_array, function( index, value ) {
                     $('#items' + cTypeIncrementNum).append('<option data-subtext="' +value.name_en +'" value="' + value.id + '">' + value.name + '</option>' ).selectpicker("refresh");
                 });

                 var numItemss = $('.ItemRow:last .btn-group').length;
                 
                 if(numItemss > 1){
                  $('.btn-group:last').remove();
                 }
                 
                 // $('.ItemRow:last-of-type #items').hide();
                 // $('.ItemRow:last-of-type #items').val('default').selectpicker('refresh');
                 
                 $('.ItemRow:last-of-type #qty').val('');

             }

         });
     });

</script>
<!-- JAVASCRIPT AREA -->
</body>
</html>