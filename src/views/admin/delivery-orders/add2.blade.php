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
            width: 87px;

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
                {!! Form::open(['url' => '/admin/runsheet', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">

                        {{--Orders--}}
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="seasons_crew">Choose Courier:<span style="color:red;">*</span>
                                </label>

                                <select name="delivery_man" class="select2 form-control select2-multiple"
                                        id="dropdown" id="multiple"
                                        data-placeholder="Choose Orders ...">

                                    @foreach($delivery_man as $delivery)
                                        <option value="{{$delivery->id}}">{{$delivery->name}}</option>
                                    @endforeach
                                </select>
                                </select>
                            </div>
                        </div>

                        {{--Choose Time--}}
                        <div class="row" >

                            <label style="margin-left: 12px" class="form-group" for="gender">Time Section
                            </label> <br>
                            <div class="col-sm-4">
                                <div class="radio radio-info radio-inline">
                                    <input data-parsley-group="block1" type="radio" id="inlineRadio1" value="1"
                                           name="time" >
                                    <label for="inlineRadio1"> Now </label>
                                </div>
                                <div class="radio radio-inline">
                                    <input hidden data-parsley-group="block2" type="radio" id="inlineRadio2"
                                           name="time" value="2">
                                    <label for="inlineRadio2"> Choose Time </label>
                                </div>
                            </div>
                        </div>

                        {{--Hidden Time Section--}}
                        <div id="demo" class="row" style="margin-left: 10px;">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="time_section">Time Section:<span style="color:red;">*</span>
                                    </label>

                                    <select name="time_section" class="select2 form-control select2-multiple"
                                            id="dropdown3" id="multiple"
                                            data-placeholder="Choose Time ...">
                                            <option value="" disabled></option>
                                        @foreach($timesection as $time)
                                            <option value="{{$time->id}}">From {{$time->from}} To {{$time->to}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Date</label>
                                    <div class="input-group">
                                        <input  type="text" autocomplete="off" name="date" class="form-control"
                                               placeholder="mm/dd/yyyy"
                                               id="datepicker-autoclose">
                                        <span class="input-group-addon bg-custom b-0"><i
                                                    class="icon-calender"></i></span>
                                    </div><!-- input-group -->
                                </div>
                            </div>
                        </div>
                        <hr>    
                        <div class="row">   
        <label for="" style="margin-left: 10px;"><b><u>Filter Orders By:</u></b></label>
                        </div>


                        <div class="row">
                            <div class="form-group col-sm-4"><label for="crewdateofbirth">From Date <span style="color:red;"></span>:</label>
                            <input  class="form-control" type="text" name="date_from" autocomplete="off" placeholder="dd/mm/yyyy" id="crewdateofbirth" />
                        </div>
                            <div class="form-group col-sm-4">
                                <label for="crewdateofjoining">To Date<span style="color:red;"></span>:</label>
                            <input   class="form-control" type="text" autocomplete="off" name="date_to" placeholder="dd/mm/yyyy" id="crewdateofjoining" />
                        </div>
                        </div>

                      




                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="seasons_crew">Order Id<span style="color:red;">*</span>:
                                </label>
                                    <input type="number" name="order_id" id="order_id" class="form-control order_filter">

                                      
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="seasons_crew">Choose District<span style="color:red;">*</span>:
                                </label>
                                <select  name="district_id"  id="district" class="form-control  " style="">
                                 <option value="-1" disabled data-foo="Select Email" selected>Select District</option>
                                        @foreach($districts as $district)
                                            <option value="{{$district->id}}">{{$district->district_en}}</option>
                                        @endforeach
                                 
                                 </select>
                            </div>
                        </div>


                      

<hr>    

                        <div class="row">
                           
                        </div>




                     


                        <div class="row">


                        </div>

                        <div class="row">

                            <div class="col-md-8">
                                <input type="hidden" id="delivery_time_left" value="@if(isset($_GET['delivery_time_left'])){{$_GET['delivery_time_left']}}@else 0 @endif" name="delivery_time_left">


                                <label for="time_section"> Orders<span style="color:red;">*</span>:
                                </label>
                                <div id="test">
                                    <div id="results">
                                        
                                        <!-- results appear here -->
                                    </div>
                                    
                                </div>

                               
                                <div class="ajax-loading"><img style=" margin-left: 50px; width: 50px; height:50px;"
                                                               src="{{ url('public/imgs/loading.gif') }}"/></div>
                            </div>
                            <script>
                                var delivery_time_left =$('#delivery_time_left').val();
                                // console.log(delivery_time_left);
                                var page = 1; //track user scroll as page number, right now page number is 1
                                load_more(page); //initial content load
                                $( document ).ready(function (){
                                    // $('#results').append('<tr class="table"><td><b>Select</b></td><td><b>Id</b></td><td><b>Order Name</b></td><td><b>Address</b></td><td><b>Payment Method</b></td><td></td><td><b>Customer Name</b></td><td><b>Show</b></td></tr>');
                                     $('#loadmore').click(function() 
                                		{   
                                            //if user scrolled from top to bottom of the page
                                            // console.log('3aaaaaaa');
                                            page++; //page number increment
                                            load_more(page); //load content
                                          }
                                         );
                                    
                                });
                                function ajax_call(){
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
                                                // console.log(data);
                                                if (data.length == 0) {
                                                    // console.log(data.length);

                                                    //notify user if nothing to load
                                                    $('.ajax-loading').html("No more records!");
                                                    return;
                                                }
                                                $('.ajax-loading').hide(); //hide loading animation once data is received
                                                $("#results").html('<tr class="table"><td><b>Select</b></td><td><b>Id</b></td><td><b>Number Of Items</b></td><td><b>Order Date</b></td><td><b>Address</b></td><td><b>Payment Method</b></td><td><b>Customer Name</b></td><td><b>Show</b></td></tr>'+data); //append data into #results element
                                            })
                                            .fail(function (jqXHR, ajaxOptions, thrownError) {
                                                alert('No response from server');
                                            });
                                }
                                function load_more(page) {
                                    if(delivery_time_left !=0){
                                       $.ajax(
                                           {
                                               url: '?page=' + page,
                                               type: "get",
                                               data:{
                                                   'delivery_time_left':delivery_time_left
                                               },
                                               datatype: "html",
                                               beforeSend: function () {
                                                   $('.ajax-loading').show();
                                               }
                                           })
                                           .done(function (data) {
                                               if (data.length == 0) {
                                                   // console.log(data.length);

                                                   //notify user if nothing to load
                                                   $('.ajax-loading').html("No more records!");
                                                   return;
                                               }
                                               $('.ajax-loading').hide(); //hide loading animation once data is received
                                               $("#results").append('<tr class="table"><td><b>Select</b></td><td><b>Id</b></td><td><b>Number Of Items</b></td><td><b>Order Date</b></td><td><b>Address</b></td><td><b>Payment Method</b></td><td><b>Customer Name</b></td><td><b>Show</b></td></tr>'+data); //append data into #results element
                                           })
                                           .fail(function (jqXHR, ajaxOptions, thrownError) {
                                               alert('No response from server');
                                           }); 
                                    }else{
                                     ajax_call(); 
                                    }
                                  
                                }
                            </script>

                            <div class="col-md-4">


                                <label for="time_section"> Selected Orders:<span style="color:red;">*</span>
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
                                Add Order
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
    $("#crewgender").select2();
    $('#crewdateofbirth').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    $('#crewdateofjoining').datepicker({
        autoclose: true,
        todayHighlight: true
    });
</script>


<script type="text/javascript">
    // Date Picker

    jQuery('#datepicker').datepicker();
    jQuery('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
    });
</script>


<!-- Laravel Javascript Validation -->
<script>



    //     $('#order_id input').blur(function()
    // {
    //     // console.log($(this).val());
    //     // if( $(this).val() == '' ) {
    //       // $(this).parents('p').addClass('warning');
    // }
    // });

function ajax_district_call(district_id){
     $.ajax(
            {
                url: '?page=' + page,
                type: "get",
                data:{
                    'district_id':district_id
                },
                datatype: "html",
                beforeSend: function () {
                    $('.ajax-loading').show();
                }
            })
            .done(function (data) {
                if (data.length == 0) {
                    removetr();
                    // console.log(data.length);
                    //notify user if nothing to load
                    $('.ajax-loading').html("No records!");
                    return;
                }
                $('.ajax-loading').hide(); 

                 removetr();

                $("#results").append(data); //append data into #results element

                //hide loading animation once data is received
                // $("#results").html('<tr class="table"><td><b>Select</b></td><td><b>Id</b></td><td><b>Order Name</b></td><td><b>Address</b></td><td><b>Payment Method</b></td><td></td><td><b>Customer Name</b></td><td><b>Show</b></td></tr>'+data); //append data into #results element
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            }); 
}

    $('#district').on('change',function(){
        var district_id = $(this).val();
        ajax_district_call(district_id);       

    });
         

    $(document).on('input', '.order_filter', function(e){
        var order_id = $('#order_id').val();    
        var page = 1; //track user scroll as page number, right now page number is 1
        $.ajax({
                url: '?page=' + page,
                type: "get",
                data:{'order_id':order_id},
                datatype: "html",
                beforeSend: function () {
                    $('.ajax-loading').show();
                }
            })
            .done(function (data) {
                if (data.length == 0) {
                    removetr();
                    if($('#order_id').val() == ''){
                        // console.log($('#order_id').val());

                        if($('#district').val() !== null){
                            ajax_district_call($('#district').val());
                        }else{
                            ajax_call();
                        }
                    }
                    //notify user if nothing to load
                    $('.ajax-loading').html("No records!");
                    return;
                }
                $('.ajax-loading').hide();
                 // $("tr.table").remove();
                 removetr();
                 //hide loading animation once data is received
                $("#results").append(data); //append data into #results element
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
           
    
    });
     function removetr(){
                $('#results').remove();
                $('#test').append('<div id="results"></div>');
                $('#results').append('<tr class="table"><td><b>Select</b></td><td><b>Id</b></td><td><b>Number Of Items</b></td><td><b>Order Date</b></td><td><b>Address</b></td><td><b>Payment Method</b></td><td><b>Customer Name</b></td><td><b>Show</b></td></tr>');
            }


    $(document).on('change', '.checkbox', function () {
        if (this.checked) {
            var mytr = $(this).closest('tr');

            td_id = $(this).closest("tr").find("td:eq(1)").text();
            td_name = $(this).closest("tr").find("td:eq(2)").text();
            var td_value = $(this).closest("tr").find("td:eq(3)").text();
            mytr.hide();
            var orders = '<tr class="table2">' + '<td>' + '<a class="doaction btn btn-danger"  id="' + td_id + ' "  >' + '<i class="fa fa-times-circle" aria-hidden="true" >' + '</i>' + '</a>' + '</td>' + '<td>' + td_id + '</td>' + '</tr>';
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
        $('#inlineRadio2').attr('checked','true');
        $("#demo").show();

        $("#inlineRadio2").click(function () {
            $("#demo").show();
        });
        $("#inlineRadio1").click(function () {
            $("#demo").hide();
        });

    });
</script>


<!-- JAVASCRIPT AREA -->
</body>
</html>