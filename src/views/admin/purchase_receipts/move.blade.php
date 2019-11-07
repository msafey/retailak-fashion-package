<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

<!-- App Favicon -->
    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>


    {{--Clock Picker--}}
    <link rel="stylesheet" type="text/css" href="{{url('public/clock/dist/bootstrap-clockpicker.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="{{url('public/clock/assets/css/github.min.css')}}">--}}
    <style type="text/css">

        .hljs-pre {
            background: #f8f8f8;
            padding: 3px;
        }

        .input-group {
            width: 110px;
            margin-bottom: 10px;
        }

    </style>
    <!--[if lt IE 9]>
    <script src="{{url('public/clock/assets/js/html5shiv.js')}}"></script>
    <script src="{{url('public/clock/assets/js/respond.min.js')}}"></script>
    <![endif]-->


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
                {!! Form::open(['url' => '/admin/move_stock', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    
             

                        <div class="row">
                            

                            <div class="col-lg-6">
                              <div class="col-sm-12">
                                <label style="margin-bottom: 0;"  class="form-group" for="from">Warehouse
                                </label>
                              </div>
                              <div class="col-sm-12" >
                                <div class='input-group date' id='' style="display: inline;">
                                   <select name="warehouse" id="warehouse" class="form-control" required="required">
                                    <option value="-1" disabled selected>Select Warehouse</option>
                                          @foreach($warehouses as $warehouse )
                                          <option value="{{$warehouse->id}}">{{ $warehouse->name}}</option>
                                          @endforeach
                                    </select>
                                </div>
                              </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="col-lg-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Warehouse quantity
                                    </label>
                                </div>
                                <div class="col-lg-12" style="margin-top: 0px">
                                    <div class='input-group' style="display: inline;" id=''>
                                    <input class="form-control" placeholder="" id="stock_qty" name="stock_qty" required="required" type="number" disabled="disabled" />
                                    </div>
                                </div>
                            </div>
                          </div>



                        <div class="row">
                          <div class="col-lg-6">
                              <div class="col-lg-12">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">Quantity To Be Moved
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px">
                                  <div class='input-group' style="display: inline;" id=''>
                                  <input class="form-control" placeholder="" name="moved_qty" required="required" type="number"  />
                                  </div>
                              </div>
                          </div>



                          <div class="col-lg-6">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">Destination Warehouse
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class='input-group date' id='' style="display: inline;">
                                <select required name="dest_warehouse" id="dest_warehouse" class="form-control" >
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
                                    <div class="col-sm-32"><button type="submit" style="margin-left: 12px" class="btn btn-primary">Save</button></div>
                                </div>

                                <input type="hidden" id="product" value="{{$_GET['product']}}" name="product">

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

<script>
  var product = document.getElementById('product').value;


  $("#warehouse").change(function(e){
     var warehouse= e.target.value;

         $.ajax({
             method: 'GET', 
             url: '{!! route('warehouses_qty') !!}', 
             data: {'warehouse' : warehouse,'product':product}, 
             success: function(response){ 
                 // console.log(response[0].id); 
                 $('#dest_warehouse').empty(); 
                  $('#stock_qty').val(response);
           
             },
             error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                 console.log(JSON.stringify(jqXHR));
                 console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
             }
         });

         $.ajax({
             method: 'GET', 
             url: '{!! route('dest_warehouse') !!}', 
             data: {'warehouse' : warehouse}, 
             success: function(response){ 
                 // console.log(response[0].id); 
                 $('#dest_warehouse').empty(); 
                 $('#dest_warehouse').append('<option value="-1" disabled selected>Select Warehouse</option>' );
                 $.each(response, function( index, value ) {
                      $('#dest_warehouse').append('<option value="' + value.id + '">' + value.name + '</option>' );
                 });

           
             },
             error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                 console.log(JSON.stringify(jqXHR));
                 console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
             }
         });

     });



</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>