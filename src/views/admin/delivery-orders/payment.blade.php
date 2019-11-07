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
                {!! Form::open(['url' => 'admin/runsheet/'.$orders->id.'/payment', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-1">
                                    <label style="margin-bottom: 0;"  class="form-group" for="from">Mode Of Payment: </label>    
                                </div>
                                <div class="col-sm-3">
                                        <select required name="payment_mode_id" @if(isset($payment_exist) && $payment_exist->status != 0) disabled="disabled" @endif class="form-control" >
                                            <option value="-1" disabled selected>Select Mode Of Payment</option>
                                       <?php foreach ($payment_methods as $method) { ?>
                                            <option value="{{$method->id}}" @if(isset($payment_exist) && $payment_exist->payment_mode_id == $method->id)selected @endif >{{$method->key}}</option>
                                        <?php } ?>
                                        </select>
                                </div>
                                
                                   <div class="col-sm-3"></div>
                                        <div class="col-sm-1">
                                            <label style="margin-bottom: 0;"  class="form-group" for="from">Posting Date: </label>    
                                        </div>
                                        <div class="col-sm-3">
                                              <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' name="posting_date" value="@if(isset($payment_exist) && $payment_exist->status != 0) {{$payment_exist->date}} @else {{old('date_created',Carbon\Carbon::today()->format('Y-m-d'))}}@endif" class="form-control"/>
                                    <span class="input-group-addon">
                                        <span class="zmdi zmdi-calendar"></span>
                                    </span>
                                </div>
                                        </div>
                                        


                            </div>
                        </div>


                      
                        <div class="row">
                            <label>Orders Data </label>
                            <div class="col-sm-12">
                                <div class="col-sm-12">
                                    <table class="table" style="width:100%">

                                        <tr>
                                            <th>Order Name</th>
                                            <th>Order Qty</th>
                                            <th>Product Name</th>
                                            <th>Price Per Unit</th>
                                            <th>Total Price</th>
                                        </tr>

                                        <tr>

                                            <td>
                                                <table class="remove">

                                                    @foreach (json_decode($orders->productlist) as $product)

                                                        <tr class="removeborder">
                                                            <td>{{$product->item_name}}</td>
                                                        </tr>

                                                    @endforeach
                                                </table>
                                            </td>

                                            <td>
                                                <table class="remove">

                                                    @foreach (json_decode($orders->productlist) as $product)

                                                        <tr class="removeborder">
                                                            <td>{{$product->qty}}</td>
                                                        </tr>

                                                    @endforeach
                                                </table>
                                            </td>

                                            <td>
                                                <table class="remove">

                                                    @foreach ($products as $product)

                                                        <tr class="removeborder">
                                                            <td>{{$product['name']}}</td>
                                                        </tr>

                                                    @endforeach
                                                </table>
                                            </td>
                                           <!--  <td>
                                                <table class="remove">

                                                    <tr class="removeborder">
                                                        <td>{{str_replace('SO-', '', $orders->salesorder_id)}}</td>
                                                    </tr>

                                                </table>
                                            </td> -->

                                            <!-- {{str_replace('SO-', '', $orders->salesorder_id)}} -->

                                            <td>
                                                <table class="remove">


                                                    @foreach ($products as $product)
                                                        <tr class="removeborder">
                                                            <td>{{$product['standard_rate']}}</td>
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

                                           <!--  <td>
                                                <table class="remove">


                                                    <tr class="removeborder">
                                                        <td>{{$final_total_price}}</td>
                                                    </tr>

                                                </table>
                                            </td> -->


                                        </tr>
                                    </table>
                                </div>


                            </div>
                        </div>
                        <hr>
                               <div class="row">
                                   <div class="col-sm-12">
                                       <div class="col-sm-1">
                                           <label style="margin-bottom: 0;"  class="form-group" for="from">Paid Amount: </label>    
                                       </div>
                                       <div class="col-sm-3">
                                              <input type="number" id="paid_amount" style="display: inline;" step="0.01" min="{{$total_amount_after_discount}}"  @if(isset($payment_exist) && $payment_exist->status != 0)value="{{$payment_exist->paid_amount}}" disabled="disabled" @else value="{{$total_amount_after_discount}}" @endif name="paid_amount" class="form-control paid_amount">
                                       </div>
                                       
                                               <div class="col-sm-1">
                                                   <label style="margin-bottom: 0;"  class="form-group" for="from">Shipping Rate: </label>    
                                               </div>
                                               <div class="col-sm-3">
                                           <input type='text' disabled="disabled" value="{{$shipping_rate}}" class="form-control"/>
                                           
                                               </div>

                                                <div class="col-sm-1">
                                                   <label style="margin-bottom: 0;"  class="form-group" for="from">Free Shipping</label>    
                                               </div>
                                               <div class="col-sm-2">
                                           <input type='text' disabled="disabled" value="@if(isset($promocode) && $promocode->shipping_rate ==1 ) Have Free Shipping @else No Free Shipping @endif" class="form-control"/>
                                           
                                               </div>
                                               
                                               
   

                                   </div>
                               </div>


                            <hr>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="col-sm-3">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Grand Total
                                        </label>
                                    </div>
                                    <div class="col-sm-6" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id=''>
                                            <input type='text' hidden="hidden" @if(isset($payment_exist) && $payment_exist->status != 0)disabled="disabled" @endif value="{{$final_total_price}}"   name="final_total_amount" class="form-control">

                                            <input type='text' value="{{$final_total_price}}" disabled="disabled"  name="name_en" class="form-control">
                                        </div>
                                    </div>



                                </div>
                                @if(isset($total_amount_after_discount))
                                <div class="col-lg-6">
                                    <div class="col-sm-3">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Grand Total After Discount
                                        </label>
                                    </div>
                                    <div class="col-sm-6" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id=''>
                                            <input type='text' hidden="hidden" @if(isset($payment_exist) && $payment_exist->status != 0)disabled="disabled" @endif value="{{$total_amount_after_discount}}"   name="final_total_amount_after_discount" class="form-control total_amount_after_discount">

                                            <input type='text' value="{{$total_amount_after_discount}}" disabled="disabled"  name="standard_rate" class="form-control total_amount_after_discount">
                                        </div>
                                    </div>



                                </div>
                                @endif
@if(isset($payment_exist) && $payment_exist->status != 2)
                            <div class="col-lg-6">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Unallocated Amount
                                    </label>
                                </div>

                                <div class="col-sm-6" style="margin-top: 0px"->
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' hidden="hidden"  id="unallocated55"   name="unallocated_amount" class="form-control">
                                        <input type='number' value="0"  step="0.01" disabled="disabled" id="unallocated" name="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            @endif

                            </div>

                        @if(isset($payment_exist) && $payment_exist->status != 0)

                <input type="text" hidden="hidden" name="payment_exist" value="{{$payment_exist->id}}">
                <input type="text" hidden="hidden" name="delivery_order_id" value="{{$delivery_order_id}}">

                        @endif
 
 <input type="text" hidden="hidden" name="delivery_order_id" value="{{$delivery_order_id}}">

                    <div class="row">
                        @if(isset($payment_exist) && $payment_exist->status != 0)
                        <div class="col-sm-32"><button type="submit" style="margin-left: 12px" class="btn btn-warning">Cancel</button></div>
                        @else
                        <div class="col-sm-32"><button  type="submit"  id="save" style="margin-left: 12px" class="btn btn-primary">Save</button></div>
                        @endif

                    </div>
                </div>
                {!! Form::close() !!}

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



        $(document).on('input', '.paid_amount', function(e){
            var attr_id = $(this).attr('id');      
            var paid_value = $(this).val();
            var total_amount = $('.total_amount_after_discount').val();
            var unallocated_amount = paid_value - total_amount;
            if(unallocated_amount < 0){
               $('#unallocated').val(0); 
               $('#unallocated55').val(0); 
            }else{
             $('#unallocated').val(Math.round(unallocated_amount));   
             $('#unallocated55').val(Math.round(unallocated_amount));   
            }

            // console.log($('#unallocated-').val());
        });


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
