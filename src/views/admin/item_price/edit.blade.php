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
                        Edit Item Price For Product {{$_GET['product_id']}}
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Product Item Price
                @endslot
                You are not allowed to access this resource!
                @endcomponent                 <!--End Bread Crumb And Title Section -->
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
        {!! Form::open(['url' => ['/admin/item-price', $item_price->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}

                        <div class="card card-block">
              
                        <div class="row">
                            

                            <div class="col-lg-6">
                              <div class="col-sm-12">
                                <label style="margin-bottom: 0;"  class="form-group" for="from">Price List
                                </label>
                              </div>
                              <div class="col-sm-12" >
                                <div class='input-group date' id='' style="display: inline;">
                                  <select required name="price_list_id" id="price_list" class="form-control" >
                                    <option value="-1" disabled selected>Select Price List</option>
                                    <?php foreach ($price_list as $list) { ?>
                                    <option value="{{$list->id}}" @if($item_price->price_list_id == $list->id)selected @endif>{{$list->price_list_name}}</option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                            </div>

                    </div>

                      <div class="row">
                            <div class="col-lg-6">
                              <div class="col-sm-12">
                                <label style="margin-bottom: 0;"  class="form-group" for="from">Currency
                                </label>
                              </div>
                              <div class="col-sm-12" >
                                <div class='input-group date' id='' style="display: inline;">
                                  <input type="text" disabled="disabled"  class="form-control" value="EGP">
                                </div>
                              </div>
                            </div>
                        </div>


                        <div class="row">
                              <div class="col-lg-6">
                                <div class="col-sm-12">
                                  <label style="margin-bottom: 0;"  class="form-group" for="from">Rate
                                  </label>
                                </div>
                                <div class="col-sm-12" >
                                  <div class='input-group date' id='' style="display: inline;">
                                    <input type="number" min="0" step="0.1"  class="form-control" value="{{$item_price->rate}}" name="rate">
                                  </div>
                                </div>
                              </div>
                          </div>



                                <div class="row">
                                    <div class="col-sm-32"><button type="submit" style="margin-left: 12px" class="btn btn-primary">Save</button></div>
                                </div>

                                <input type="hidden" value="{{$_GET['product_id']}}" name="product_id">

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


<script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{url('public/clock/dist/bootstrap-clockpicker.min.js')}}"></script>

<script type="text/javascript" src="{{url('public/clock/assets/js/highlight.min.js')}}"></script>



 

<script type="text/javascript">
        function preview(input) {
         if (input.files && input.files[0]) {
           var reader = new FileReader();
           reader.onload = function (e) { $('#img').attr('src', e.target.result);  }
           reader.readAsDataURL(input.files[0]);     }   }

       $("#upload").change(function(){
         $("#img").css({top: 0, left: 0});
           preview(this);
           $("#img").draggable({ containment: 'parent',scroll: false });
       });
    </script>

<!-- JAVASCRIPT AREA -->
</body>
</html>