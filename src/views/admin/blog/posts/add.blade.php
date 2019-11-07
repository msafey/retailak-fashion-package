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
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" />

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>

    {{-- <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.css" />
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

                {!! Form::open(['url' => '/admin/posts', 'class'=>'form-hirozontal ',
                'id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">

                     <div style="margin-left: 5px" class="card-text">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="col-lg-12">
                                            <label style="margin-bottom: 0;" class="form-group"
                                             for="from">Title: <span style="color:red;">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-12" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id=''>
                                                <input type='text' required name="name_en" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                        <div class="col-lg-6">
                                            <div class="col-lg-12">
                                                <label style="margin-bottom: 0;" class="form-group"
                                                 for="from">Tags: <span style="color:red;">*</span>
                                                </label>
                                            </div>
                                            <div class="col-lg-12" style="margin-top: 0px">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-xl-6">
                                                            <div class="tags-default">
                                                                <input type="text"
                                                                value="Amsterdam,Washington,Sydney"
                                                                 data-role="tagsinput"
                                                                 placeholder="add tags"/>
                                                            </div>

                                                        </div>
                                            </div>
                                        </div>
                                    </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <textarea class=""
                                     id="body-ckeditor"
                                     name="body-ckeditor"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 ">
                                     <br>
                                            <label for="image">Image : <span style="color:red;">*</span></label>

                                       <input type="file" name="images"  id="upload" class="form-group" >
                                         <div id="image_upload" style="">

                                     <div class="col-md-3" >
                                        <output id="result" />

                                         <br>
                                         <img style="height: 150px;width: 200px;" id="img" src="{{asset('public/imgs/default.jpg')}}" />
                                     </div>
                                     <div id="multi_images" style="display: none;">
                                        <div class="uploader__box js-uploader__box l-center-box" >

                                          <div class="uploader__contents">
                                              <label class="button button--secondary" for="fileinput">Select Files</label>
                                              <input id="fileinput" name="images[]" class="uploader__file-input" type="file" value="Select Files">
                                          </div>

                                        </div>
                                     </div>


                                 </div>

                            </div>

                                <div class="row">
                                    <div class="col-sm-32"><button type="submit" style="margin-left: 12px" class="btn btn-primary">Save</button></div>
                                </div>
                            </div>

                {!! Form::close() !!}
            </div>
        </div>


    </div>


</div>


-</div>


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

<!-- <script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.4/select2.js"></script>
<script src="{{url('public/admin/plugins/multiselect/js/jquery.multi-select.js')}}" type="text/javascript"></script>
<script src="{{url('public/admin/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.js')}}"></script>

<script src="{{asset('vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
<script>
    CKEDITOR.replace('body-ckeditor');
</script>
<script>
</script>
<!-- JAVASCRIPT AREA -->
</body>
</html>
