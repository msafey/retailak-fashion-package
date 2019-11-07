<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')
<script src="http://malsup.github.com/jquery.form.js">
</script>
<link href="{{url('public/multi-images/dist/styles.imageuploader.css')}}" rel="stylesheet">
<script src="{{url('public/multi-images/dist/jquery.imageuploader.js')}}"></script>
<!-- App Favicon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" />

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>

    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    {{-- <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.css" />
    <link href="{{url('public/lou/css/multi-select.css')}}" media="screen" rel="stylesheet" type="text/css">

    <style type="text/css">

    body{
        font-family: 'Segoe UI';
        font-size: 12pt;
    }

    header h1{
        font-size:12pt;
        color: #fff;
        background-color: #1BA1E2;
        padding: 20px;

    }
    article
    {
        width: 80%;
        margin:auto;
        margin-top:10px;
    }




    /* The switch - the box around the slider */

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
                        Products
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Products
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
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









                {!! Form::open(['url' => '/admin/import/products/step1', 'class'=>'form-hirozontal ','id'=>'imagesForm','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    <div style="margin-left: 5px" class="card-head">
                        <h1>Import Products - Step 1 of 2 - Upload Images Zip File</h1>
                    </div>
                     <div style="margin-left: 5px; margin-top:20px;" class="card-text">
                        <div class="row">
                            <div class="col-sm-10">
                                <label style="margin-bottom: 0;" class="form-group" for="images">
                                    <b>Upload Product's Images Zip File</b>
                                </label>
                                 <input type="file" name="imagesZipFile"  id="uploadImagesZipFile"  class="form-group" >
                            </div>


                      </div>

                      <div class="row">
                          <div class="col-sm-10">
                              <button type="submit" id="uploadImagesBtn" class="btn btn-primary">Upload File</button>
                          </div>
                      </div>

                      <div class="row" id="uploadImagesResponseContainer">

                      </div>
                  </div>
                </div>

                {!! Form::close() !!}





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

<!-- <script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.js"></script>
<script src="{{url('public/lou/js/jquery.multi-select.js')}}" type="text/javascript"></script>
<script>









</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
