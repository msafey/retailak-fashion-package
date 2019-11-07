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
                      Add Time Section
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Time Sections
                @endslot
                You are not allowed to access this resource!
                @endcomponent               <!--End Bread Crumb And Title Section -->
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
                {!! Form::open(['url' => '/admin/time/section', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="crewname">Name English: <span style="color:red;">*</span></label>
                                <input  data-parsley-maxlength="50" name="name_en" type="text"
                                       class="form-control" id="crewname"
                                       placeholder=""/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="crewname">Name Arabic: <span style="color:red;">*</span></label>
                                <input required data-parsley-maxlength="50" name="name" type="text"
                                       class="form-control" id="crewname"
                                       placeholder=""/>
                            </div>
                        </div>

                        



                        <div class="row">
                            <div class="col-sm-4">
                                <label for="crewname">From:  <span style="color:red;">*</span></label>
                                <div class="input-group clockpicker">
                                    <input id="fromm" required name="from" type="text" class="form-control"
                                           value="12:00">
                                    <span class="input-group-addon">
                                             <span class="glyphicon glyphicon-time"></span>
                                         </span>
                                </div>

                            </div>
                            <div class="col-sm-2" style="right: 130px;">
                                <label for="crewname">To:  <span style="color:red;">*</span></label>
                                <div class="input-group clockpicker">
                                    <input id="to" required data-parsley-lt="#from" name="to" type="text"
                                           class="form-control" value="12:00">
                                    <span class="input-group-addon">
                                             <span class="glyphicon glyphicon-time"></span>
                                         </span>
                                </div>

                            </div>

                        </div>



                        <div class="row">
                           
                        </div>

                        <div class="row">
                            <button style="margin-left: 15px" type="submit" class="btn btn-primary"><i
                                        class="zmdi zmdi-plus-circle-o"></i>
                                Add Time Section
                            </button>
                        </div>

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


<script type="text/javascript" src="{{url('public/clock/assets/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{url('public/clock/dist/bootstrap-clockpicker.min.js')}}"></script>
<script type="text/javascript">
    $('.clockpicker').clockpicker()
        .find('input').change(function () {
        console.log(this.value);
    });
    var input = $('#single-input').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });

</script>
<script type="text/javascript" src="{{url('public/clock/assets/js/highlight.min.js')}}"></script>


<script>

    $.validator.addMethod("greaterThan",

        function (value, element, param) {
            var $min = $(param);
            if (this.settings.onfocusout) {
                $min.off(".validate-greaterThan").on("blur.validate-greaterThan", function () {
                    $(element).valid();
                });
            }
            return parseInt(value) > parseInt($min.val());
        }, "Max must be greater than min");

    $('#sales').validate({
        rules: {
            maxTruck: {
                greaterThan: '#minTruck'
            }
        }
    });
</script>


<!-- JAVASCRIPT AREA -->
</body>
</html>