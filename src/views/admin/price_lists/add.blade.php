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
                       Add Price List
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Price List
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
                {!! Form::open(['url' => '/admin/price-list', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="col-md-12 card card-block ">

                    <div class='col-md-3'>
                        <label>currency</label>
                        <input class="form-control" type="text" name="currency_code" value="EGP"/>
                    </div>
                    <div class='col-md-3'>
                        <label>Price List Name </label>
                        <input class="form-control" type="text" name="price_list_name" required value="{{ old('price_list_name') }}" />
                    </div>
                    <div class='col-md-3'>
                        <label>type</label>
                        <div class='col-md-12'>

                                <input  value="0" type="checkbox" name="type[]"/> Selling
                                <input value="1" type="checkbox" name="type[]"/> Buying
                                <input value="2" type="checkbox" name="type[]"/> Both
                        </div>
                    </div>

                    <div class='col-md-3'>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>

                </div>
                {!! Form::close() !!}
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
            reader.onload = function (e) {
                $('#img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#upload").change(function () {
        $("#img").css({top: 0, left: 0});
        preview(this);
        $("#img").draggable({containment: 'parent', scroll: false});
    });
</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>