<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>

    <!-- FONT
–––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link href="https://fonts.googleapis.com/css?family=Cabin|Roboto+Condensed:700" rel="stylesheet">

    <!-- CSS
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <link rel="stylesheet" href="{{url('public/print/assets/css/normalize.css')}}">
{{--    <link rel="stylesheet" href="{{url('public/print/assets/css/skeleton.css')}}">--}}


    <!-- Plugins css -->
    <link href="{{url('public/admin/plugins/timepicker/bootstrap-timepicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/mjolnic-bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>


    <style>
        #invoice-POS {
            /*box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);*/
            padding: 2mm;
            margin: 0 auto;
            width: 75mm;
            background: #FFF;
        }

        ::selection {
            background: #f31544;
            color: #FFF;
        }

        h1 {
            font-size: 1.5em;
            color: #222;
        }

        h4 {
            font-size: 0.5em;
        }

        h3 {
            font-size: 1.2em;
            font-weight: 300;
            line-height: 2em;
        }

        p {
            font-size: 0.5em;
            color: #666;
            line-height: 1.2em;
            margin-bottom: 1em;
        }

        #top, #mid, #bot { /* Targets all id with 'col-' */
            border-bottom: 1px solid #EEE;
        }

        #top {
            min-height: 100px;
            margin-bottom: 5px;

        }

        #mid {
            min-height: 80px;
        }

        #bot {
            min-height: 50px;
        }

        #top .logo {
        / / float: left;
            height: 60px;
            width: 105px;
            /*background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;*/
            background-size: 60px 60px;
        }

        #top .logo img {

            height: 50px;
        }

        .clientlogo {
            float: left;
            height: 60px;
            width: 60px;
            background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
            background-size: 60px 60px;
            border-radius: 50px;
        }

        .info {
            display: block;
        / / float: left;
            margin-left: 0;
        }

        .title {
            float: right;
        }

        .title p {
            text-align: right;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }



        th:first-child, td:last-child {
            padding-right: 25px;

        }
        th, td {
            padding: 0px 0px;
            text-align: left;
            border-bottom: 1px solid #E1E1E1;
        }
        .tabletitle {
        / / padding: 5 px;
            font-size: .5em;
            background: #EEE;
        }

        .service {
            border-bottom: 1px solid #EEE;
        }

        .tableitem {
            /*padding: 2px;*/
        }

        .item {
            /*width: 24mm;*/
        }

        .itemtext {
            font-size: .5em;
            margin: 0px;

        }

        #legalcopy {
            margin-top: 5mm;
        }

        .font {
            font-weight: 800;
            font-size: 14px;
        }

        .remove .removeborder td, th {
            border: none;
            height: 75px;

        }

        .remove .removeborder tr {
        }

        .table td, th {
            border: 2px solid #dddddd;
            text-align: left;
            padding: 8px;
            /*vertical-align: middle;*/
            text-align: center;

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
                {!! Form::open(['url' => '/admin/runsheet', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}


                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">

                        <div class="row">
                            <label>Orders Data </label>
                            <div class="col-sm-12">
                                <div class="col-sm-12">

                                    <table class="table" style="width:100%">

                                        <tr>
                                            <th>Product Name</th>
                                            <th>Order Qty</th>
                                            <th>Sales Order</th>
                                            <th>Price Per Unit</th>
                                            <th>Total Price</th>
                                            <th>Shipping Rate</th>
                                            <th>Final Total Price</th>
                                        </tr>

                                        <tr>

                                           

                                            <td>
                                                <table class="remove">

                                                    @foreach ($products as $product)

                                                        <tr class="removeborder">
                                                            <td>{{$product['name']}}</td>
                                                        </tr>

                                                    @endforeach
                                                </table>
                                            </td>
                                            <td>
                                                <table class="remove">

                                                    @foreach ($item_list as $product)

                                                        <tr class="removeborder">
                                                            <td>{{$product->qty}}</td>
                                                        </tr>

                                                    @endforeach
                                                </table>
                                            </td>
                                            <td>
                                                <table class="remove">

                                                    <tr class="removeborder">
                                                        <td>{{str_replace('SO-', '', $orders->id)}}</td>
                                                    </tr>

                                                </table>
                                            </td>

                                            {{str_replace('SO-', '', $orders->id)}}

                                            <td>
                                                <table class="remove">


                                                    @foreach ($products as $product)
                                                        <tr class="removeborder">
                                                            <td>{{$product['rate']}}</td>
                                                        </tr>

                                                    @endforeach
                                                </table>
                                            </td>


                                            <td>
                                                <table class="remove">


                                                    @foreach ($products as $product)
                                                        <tr class="removeborder">
                                                            <td>{{$product['total_price']}}</td>
                                                        </tr>

                                                    @endforeach
                                                </table>
                                            </td>


                                            <td>
                                                <table class="remove">
                                                        <tr class="removeborder">
                                                            <td>{{$shipping_rate}}</td>
                                                        </tr>
                                                </table>
                                            </td>
                                            <td>
                                                <table class="remove">


                                                    <tr class="removeborder">
                                                        <td>{{$final_total_price}}</td>
                                                    </tr>

                                                </table>
                                            </td>


                                        </tr>


                                    </table>
                                </div>


                            </div>
                        </div>
<hr>

                        <hr>

                                <section class="container" aria-describedby="demos">
                                    <label>Print Recipt</label>
                                    <div class="row">
                                        <div class="one-half column">
                                            <a  id="basic" href="#nada" class="btn btn-primary">Print</a>
                                        </div>
                                    </div>
                                </section>

                        {{--print recipt--}}


                        <hr>


                        {{--User--}}
                        <div class="row">
                            <label>User Data</label>

                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label for="time_section">Name : </label> @if(isset($user_data->name)){{$user_data->name}} @endif
                                    </div>
                                </div>

                                @if(isset($user_data->name))
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Phone : </label> @if(!empty($address_user->address_phone)){{$address_user->address_phone}}@else {{$user_data->phone}} @endif

                                    </div>
                                </div>
                                @endif


                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Address: </label><br>
                                         {{$address_user->street}}
                                            <!-- {{$address_user->region}}{{$address_user->city}} -->
                                        <!-- {{$address_user->country}} -->
                                        <br>
                                        <!-- - <label>Floor</label> {{$address_user->floor_no}}, -->
                                        <!-- <label>Building</label> {{$address_user->building_no}}, -->
                                        <!-- <label>Apartment</label> {{$address_user->apartment_no}} -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>
                        {{--Payment Method--}}
                        <div class="row">
                            <label>User Payment Method</label>

                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label for="time_section">Payment Method : </label> {{$orders->payment_method}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{--Choose Time--}}
                        <div class="row">
                            <label>Time To Deliver</label>

                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        @if(isset($order_time->from))
                                            <label for="time_section">Time : </label> From: {{$order_time->from}}@endif
                                        To: @if(isset($order_time->to)){{$order_time->to}} @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Order Date : </label> {{$orders->date}}

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>


            <div style="display: none" class="row">
                <div class="demo twelve columns">
                    <center id="top">
                        <div class="logo">
                            <img src="{{url('public/admin/images/logo_print_gomla.png')}}">
                        </div>
                    </center><!--End InvoiceTop-->

                    <div class="info">
                        <h4>Contact Info</h4>
                        <p>
                            @if(isset($user_data->name)) 
                             name : {{$user_data->name}} </br>
                            @endif

                            Address : 
                            @if(isset($address_user->title)) 
                            {{$address_user->title}}
                            @endif
                            @if(isset($address_user->building_no)) 

                            -{{$address_user->building_no}}
                            @endif
                            @if(isset($address_user->street)) 

                             {{$address_user->street}},
                             @endif
                             @if(isset($address_user->regoin)) 
                            {{$address_user->regoin}},
                            @endif
                            @if(isset($address_user->city)) 

                                {{$address_user->city}}
                            @endif

                            @if(isset($address_user->apartment_no))
                            ,{{$address_user->apartment_no}}
                            @endif

                            @if(isset($address_user->floor_no))

                            ,{{$address_user->floor_no}}</br>
                            @endif

                            @if(isset($user_data->phone))
                            Phone : @if(!empty($address_user->address_phone)){{$address_user->address_phone}}@else {{$user_data->phone}} @endif</br>
                            @endif

                            Payment Method : {{$orders->payment_method}}</br>
                            Invoice Number : {{str_replace('SO-', '', $orders->salesorder_id)}}</br>
                        </p>
                    </div>


                    <div id="table">
                        <table>
                            <tr class="tabletitle">
                                <td class="item"><h4>Item</h4></td>
                                <td  style="    width: 3px;" class="Hours"><h4>Qty</h4></td>
                                <td class="Rate"><h4>Sub Total</h4></td>
                            </tr>

                            @for($i= 0; $i< count($products); $i++ )
                                <tr class="service">

                                    <td class="tableitem"><p class="itemtext">{{$products[$i]['name']}}</p>
                                    </td>
                                    <td style="    width: 3px;" class="tableitem"><p
                                                class="itemtext">{{$item_list[$i]->qty}}</p>
                                    </td>
                                    <td class="tableitem"><p
                                                class="itemtext"> {{$products[$i]['rate']}}</p>
                                    </td>
                                </tr>
                            @endfor

                            <tr class="tabletitle">
                                <td></td>
                                <td class="Rate"><h4>Delivery</h4></td>
                                <td class="payment"><h4>{{$orders->shipping_rate}}</h4></td>
                            </tr>

                            <tr class="tabletitle">
                                <td></td>
                                <td class="Rate"><h4>Total</h4></td>
                                <td class="payment"><h4>{{$final_total_price}}</h4></td>
                            </tr>

                        </table>
                    </div><!--End Table-->


                </div>
            </div>



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

    <!-- jQuery  -->
    <script src="{{url('public/admin/js/jquery.min.js')}}"></script>
    <script src="{{url('public/admin/js/tether.min.js')}}"></script><!-- Tether for Bootstrap -->
    <script src="{{url('public/admin/js/bootstrap.min.js')}}"></script>
    <script src="{{url('public/admin/js/detect.js')}}"></script>
    <script src="{{url('public/admin/js/fastclick.js')}}"></script>
    <script src="{{url('public/admin/js/jquery.blockUI.js')}}"></script>
    <script src="{{url('public/admin/js/waves.js')}}"></script>
    <script src="{{url('public/admin/js/jquery.nicescroll.js')}}"></script>
    <script src="{{url('public/admin/js/jquery.scrollTo.min.js')}}"></script>
    <script src="{{url('public/admin/js/jquery.slimscroll.js')}}"></script>
    <script src="{{url('public/admin/plugins/switchery/switchery.min.js')}}"></script>

    <!-- Required datatable js -->
    <script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{url('public/admin/plugins/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatables/jszip.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatables/pdfmake.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatables/vfs_fonts.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatables/buttons.html5.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatables/buttons.print.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatables/buttons.colVis.min.js')}}"></script>
    <!-- Responsive examples -->
    <script src="{{url('public/admin/plugins/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>

    <script src="{{url('public/admin/js/jquery.core.js')}}"></script>
    <script src="{{url('public/admin/js/jquery.app.js')}}"></script>
    <script src="{{url('public/print/printThis.js')}}"></script>


    <script>
        $('#basic').on("click", function () {
            $('.demo').printThis({
                importCSS: false,
                base: "https://jasonday.github.io/printThis/"
            });
        });

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').DataTable();

            //Buttons examples
            var table = $('#datatable-buttons').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'colvis']
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        });

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
