<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

<!-- Plugins css -->
    <link href="{{url('public/admin/plugins/timepicker/bootstrap-timepicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/mjolnic-bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>


    <style>

        .table td:nth-of-type(3) {
            width: 200px;

        }

        .table td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;


        }

        .table tr:nth-child(even) {
            width: 100px;
            /*background-color: #dddddd;*/
        }

        .table2 td:nth-of-type(2) {
            width: 200px;
        }

        .table2 td:nth-of-type(3) {
            width: 170px;

        }

        .table2 td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;


        }

        .table2 tr:nth-child(even) {
            width: 100px;
            /*background-color: #dddddd;*/
        }
    </style>

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
                        Run Sheets
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Run Sheets
                @endslot
                You are not allowed to access this resource!
                @endcomponent                    <!--End Bread Crumb And Title Section -->
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
                {!! Form::open(['url' => '/admin/runsheet/'.$delivery_orders->id,
               'method'=>'PATCH',
               'class'=>'form-hirozontal ',
               'id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">

                        {{--Orders--}}
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="seasons_crew">Delivery-Man<span style="color:red;">*</span>:
                                </label>

                                <select name="delivery_man" class="select2 form-control select2-multiple"
                                        id="dropdown" id="multiple"
                                        data-placeholder="Choose Orders ...">

                                    @foreach($delivery_man as $delivery)

                                        <option value="{{$delivery->id}}"
                                                @if($delivery->id == $delivery_orders->delivery_id) selected @endif>
                                            {{$delivery->name}}</option>
                                    @endforeach
                                </select>
                                </select>
                            </div>
                        </div>

                        {{--Hidden Time Section--}}
                        <div id="" class="row">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="time_section">Time Section<span style="color:red;">*</span>:
                                    </label>

                                    <select name="time_section" class="select2 form-control select2-multiple"
                                            id="dropdown3" id="multiple"
                                            data-placeholder="Choose Time ...">
                                        @foreach($time_section as $time)

                                            <option value="{{$time->id}}"
                                                    @if($time->id == $delivery_orders->time_section_id) selected @endif>
                                                From {{$time->from}} To {{$time->to}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Date</label>
                                    <div class="input-group">
                                        <input type="text" name="date" class="form-control"
                                               value="{{$delivery_orders->date}}"
                                               placeholder="mm/dd/yyyy"
                                               id="datepicker-autoclose">
                                        <span class="input-group-addon bg-custom b-0"><i
                                                    class="icon-calender"></i></span>
                                    </div><!-- input-group -->
                                </div>
                            </div>
                        </div>


                        <div class="row">


                        </div>

                        <div class="row">

                            <div class="col-md-8">

                                <label for="time_section"> Orders<span style="color:red;">*</span>:
                                </label>
                                <div id="results">
                                    <!-- results appear here -->
                                </div>
                                <div class="ajax-loading"><img style=" margin-left: 50px; width: 50px; height:50px;"
                                                               src="{{ url('public/imgs/loading.gif') }}"/></div>
                            </div>
                            <script>
                                
                                var page = 1; //track user scroll as page number, right now page number is 1
                                load_more(page); //initial content load
                                $( document ).ready(function (){
                                    $('#loadmore').click(function()
                                        { //if user scrolled from top to bottom of the page
                                            console.log('3aaaaaaa');
                                            page++; //page number increment
                                            load_more(page); //load content
                                        }
                                    );

                                });
                                function load_more(page) {
                                    $.ajax(
                                        {
                                            url: '?page=' + page,
                                            type: "get",
                                            datatype: "html",
                                            beforeSend: function () {
                                                $('.ajax-loading').show();
                                            }
                                        })
                                        .done(function (data) {
                                            if (data.length == 0) {
                                                console.log(data.length);

                                                //notify user if nothing to load
                                                $('.ajax-loading').html("No more records!");
                                                return;
                                            }
                                            $('.ajax-loading').hide(); //hide loading animation once data is received
                                            $("#results").append(data); //append data into #results element
                                        })
                                        .fail(function (jqXHR, ajaxOptions, thrownError) {
                                            alert('No response from server');
                                        });
                                }
                            </script>

                            <div class="col-md-4">


                                <label for="time_section"> Selected Orders<span style="color:red;">*</span>:
                                </label>

                                <div id="delivered_orders">

                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <button type="button" class="btn btn-warning btn-sm" id="loadmore">Load More</button>
                        </div>

                        <div class="row">
                            <button style="margin-left: 10px" type="submit" class="btn btn-primary"><i
                                        class="zmdi zmdi-plus-circle-o"></i>
                                Edit  Order
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

<script src="{{url('components/select2/dist/js/select2.js')}}"></script>

<script src="{{url('/public/')}}/prasley/parsley.js"></script>


<script src="{{url('public/admin/plugins/moment/moment.js')}}"></script>
<script src="{{url('public/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
{{--<script src="{{url('public/admin/pages/jquery.form-pickers.init.js')}}"></script>--}}


<script type="text/javascript">
    // Date Picker
    jQuery('#datepicker').datepicker();
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
    });
</script>

<script>
    $(document).ready(function () {

        var checkedValue = $('.checkbox:checked').val();

        console.log(checkedValue);


    });

    $(document).on('change', '.checkbox', function () {
        if (this.checked) {
            var mytr = $(this).closest('tr');

            td_id = $(this).closest("tr").find("td:eq(1)").text();
            td_name = $(this).closest("tr").find("td:eq(2)").text();
            var td_value = $(this).closest("tr").find("td:eq(3)").text();
            mytr.hide();
            var orders = '<tr class="table2">' + '<td>' + '<a class="doaction btn btn-danger"  id="' + td_id + ' "  >' + '<i class="fa fa-times-circle" aria-hidden="true" >' + '</i>' + '</a>' + '</td>' + '<td>' + td_name + '</td>' + '</tr>';
            $(orders).appendTo($('#delivered_orders'));
        }

        $("body").on("click", "a.doaction ", function () {

            var order_id = $(this).attr("id");

            $("tr").filter("#" + order_id).show();
            $(".checkbox").filter("#" + order_id).prop('checked', false);

            var order = $(this).closest('tr');
            order.hide();

        });


    });
</script>

<script type="text/javascript">
    $("#dropdown").select2();
</script>

<script type="text/javascript">
    $(".dropdown2").select2();
</script>


<script type="text/javascript">
    $(".dropdown3").select2();
</script>

<script>
    $(document).ready(function () {
        $("#demo").hide();

        $("#inlineRadio2").click(function () {
            $("#demo").show();
        });
    });
</script>


<!-- JAVASCRIPT AREA -->
</body>
</html>