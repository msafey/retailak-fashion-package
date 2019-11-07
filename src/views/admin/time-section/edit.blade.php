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
                     Edit Time Section
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Time Sections
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

                {!! Form::open(['url' => '/admin/time/section/'.$time_section->id,
                'method'=>'PATCH',
                 'class'=>'form-hirozontal ',
                 'id'=>'demo-form','files' => true,
                 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="crewname">Name <span style="color:red;">*</span>:</label>
                                <input required data-parsley-maxlength="50" name="name" type="text"
                                       class="form-control" id="crewname" value="{{$time_section->name}}"
                                       placeholder=""/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="crewname">Name En </label>
                                <input required data-parsley-maxlength="50" name="name_en" type="text"
                                       class="form-control" id="crewname" value="{{$time_section->name_en}}"
                                       placeholder=""/>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-4">
                                <label for="crewname">From <span style="color:red;">*</span>:</label>
                                <div class="input-group clockpicker">
                                    <input required name="from" type="text" class="form-control" value="{{$time_section->from}}">
                                    <span class="input-group-addon">
                                             <span class="glyphicon glyphicon-time"></span>
                                         </span>
                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-sm-4">
                                <label for="crewname">To <span style="color:red;">*</span>:</label>
                                <div class="input-group clockpicker">
                                    <input required name="to" type="text" class="form-control" value="{{$time_section->to}}">
                                    <span class="input-group-addon">
                                             <span class="glyphicon glyphicon-time"></span>
                                         </span>
                                </div>

                            </div>
                        </div>


                        <div class="row">
                            <button style="margin-left: 25px" type="submit" class="btn btn-primary"><i
                                        class="zmdi zmdi-plus-circle-o"></i>
                                Edit Time Section
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


<!-- Laravel Javascript Validation -->


<script type="text/javascript" src="{{url('public/clock/assets/js/jquery.min.js')}}"></script>
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

    $('.clockpicker-with-callbacks').clockpicker({
        donetext: 'Done',
        init: function () {
            console.log("colorpicker initiated");
        },
        beforeShow: function () {
            console.log("before show");
        },
        afterShow: function () {
            console.log("after show");
        },
        beforeHide: function () {
            console.log("before hide");
        },
        afterHide: function () {
            console.log("after hide");
        },
        beforeHourSelect: function () {
            console.log("before hour selected");
        },
        afterHourSelect: function () {
            console.log("after hour selected");
        },
        beforeDone: function () {
            console.log("before done");
        },
        afterDone: function () {
            console.log("after done");
        }
    })
        .find('input').change(function () {
        console.log(this.value);
    });

    // Manually toggle to the minutes view
    $('#check-minutes').click(function (e) {
        // Have to stop propagation here
        e.stopPropagation();
        input.clockpicker('show')
            .clockpicker('toggleView', 'minutes');
    });
    if (/mobile/i.test(navigator.userAgent)) {
        $('input').prop('readOnly', true);
    }
</script>
<script type="text/javascript" src="{{url('public/clock/assets/js/highlight.min.js')}}"></script>

<script type="text/javascript">
    hljs.configure({tabReplace: '    '});
    hljs.initHighlightingOnLoad();
</script>


<!-- JAVASCRIPT AREA -->
</body>
</html>