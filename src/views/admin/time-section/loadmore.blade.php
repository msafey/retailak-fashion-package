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
    <style>
        .wrapper > ul#results li {
            margin-bottom: 1px;
            background: #f9f9f9;
            padding: 20px;
            list-style: none;
        }
        .ajax-loading{
            text-align: center;
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
                        Time Sections
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
                {!! Form::open(['url' => '/admin/time/section', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="wrapper">
                    <ul id="results"><!-- results appear here --></ul>
                    <div class="ajax-loading"><img  style="width: 20px;" height="20px;" src="{{ url('public/imgs/loading.gif') }}" /></div>
                </div>
                <script>
                    var page = 1; //track user scroll as page number, right now page number is 1
                    load_more(page); //initial content load
                    $(window).scroll(function() { //detect page scroll
                        if($(window).scrollTop() + $(window).height() >= $(document).height()) { //if user scrolled from top to bottom of the page
                            page++; //page number increment
                            load_more(page); //load content
                        }
                    });
                    function load_more(page){
                        $.ajax(
                            {
                                url: '?page=' + page,
                                type: "get",
                                datatype: "html",
                                beforeSend: function()
                                {
                                    $('.ajax-loading').show();
                                }
                            })
                            .done(function(data)
                            {
                                if(data.length == 0){
                                    console.log(data.length);

                                    //notify user if nothing to load
                                    $('.ajax-loading').html("No more records!");
                                    return;
                                }
                                $('.ajax-loading').hide(); //hide loading animation once data is received
                                $("#results").append(data); //append data into #results element
                            })
                            .fail(function(jqXHR, ajaxOptions, thrownError)
                            {
                                alert('No response from server');
                            });
                    }
                </script>


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