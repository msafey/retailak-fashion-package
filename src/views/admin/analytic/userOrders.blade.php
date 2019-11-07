<!DOCTYPE html>
<html>
    <head>
        @include('layouts.admin.head')
        <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>


        <!-- Plugins css-->
        <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
        <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{url('public/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">


        <!-- DataTables -->
        <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
              type="text/css"/>
        <!-- Responsive datatable examples -->
        <link href="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet"
              type="text/css"/>

        <!-- App Favicon -->
        <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">

        <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
        <!-- Switchery css -->
        <link href="{{url('public/admin/plugins/switchery/switchery.min.css')}}" rel="stylesheet"/>

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style>
            a{
                color: black;
            }
            .header-title {
                font-size: 1.1rem;
                text-transform: none;
            }</style>
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
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="page-title-box">
                                    <h4 class="page-title"> Users Orders </h4>
                                    <ol class="breadcrumb p-0">
                                        <li>
                                            <a href="{{url('/')}}">Dashboard</a>
                                        </li>
                                        <li class="active">
                                            Users Orders
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!--End Bread Crumb And Title Section -->
                        <div class="row">

                            <div class="card card-block">
                                <div class="card-text">
                                    <div class="row">


                                         {{-- Users --}}
                                         <form method="get">
                                            
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <h4>Filter by Orders date</h4>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <label>From</label>
                                                            <div class="input-group">
                                                                <input  type="text" name="from" value="{{old('from')}}"class="form-control"
                                                                                    placeholder="mm/dd/yyyy"
                                                                                    id="datepicker-autoclose">
                                                                <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                                                            </div><!-- input-group -->
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>To</label>
                                                            <div class="input-group">
                                                                <input  type="text" name="to" value="{{old('to')}}" class="form-control"
                                                                        placeholder="mm/dd/yyyy"
                                                                        id="datepicker-autoclose2">
                                                                <span class="input-group-addon bg-custom b-0"><i class="icon-calender"></i></span>
                                                            </div><!-- input-group -->
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <h4>Filter by Orders quantity</h4>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                            <div class="col-sm-2">
                                                                <div class="input-group">
                                                                    <label for="nofrom" >From</label>
                                                                    <input  type="number" name="nofrom" value="{{old('nofrom')}}" class="form-control">
                                                                </div><!-- input-group -->
                                                                  
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <div class="input-group">
                                                                    <label for="noto" >To</label>
                                                                    <input  type="number" name="noto" value="{{old('noto')}}" class="form-control">
                                                                </div><!-- input-group -->
                                                            </div>
                                                    </div>
                                                </div>
                                               
                                            </div>



                                            <div class="row">
                                                    <button id="genpass" style="margin-left: 10px" type="submit" class="btn btn-primary"><i
                                                            class="zmdi zmdi-plus-circle-o"></i>
                                                        Filter
                                                    </button>
                                            </div>
                                        </form>
                                        </div>
                                      
                                        

                                         <div class="row">
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                <table id="datatable-buttons9" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                
                                                                <th>user name</th>
                                                                <th>user id</th>
                                                                <th>Phone</th>
                                                                <th>orders</th>
                                                                <th>Total Amount</th>
                                                                <th>Territory</th>
                                                               
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($ordersByUsers as $userOrders)
                                                                <?php
                                                                if(array_key_exists($userOrders->user_id,$territories))
                                                                    $mostTerritory = array_search(max($territories[$userOrders->user_id]), $territories[$userOrders->user_id]);
                                                                ?>

                                                            <tr>
                                                                
                                                                <td>
                                                                    @if($mostTerritory)
                                                                        <a href="{{url('admin/user/details/'.$userOrders->usrid.'?territory='.$mostTerritory)}}">
                                                                            {{$userOrders->username}}
                                                                        </a>
                                                                    @else
                                                                        <a href="{{url('admin/user/details/'.$userOrders->usrid)}}">
                                                                            {{$userOrders->username}}
                                                                        </a>
                                                                    @endif
                                                                </td>
                                                                <td>{{$userOrders->user_id}}</td>
                                                                <td>{{$userOrders->phone}}</td>
                                                                <td>{{$userOrders->orders_count}}</td>
                                                                <td>@if(isset($userOrderSum[$userOrders->user_id]))
                                                                        {{$userOrderSum[$userOrders->user_id]}}
                                                                    @endif</td>
                                                                <td>@if($mostTerritory)
                                                                {{$mostTerritory}}
                                                                @endif</td>
                                                                
                                                            </tr>
                                                            @endforeach
                                                        </tbody>


                                    
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                </div>


                            </div>
                        </div>


                    </div>


                </div>


            </div>
        </div> <!-- container -->

        <!-- End content-page -->
        <!-- Footer Area -->@include('layouts.admin.footer')

        <!-- End Footer Area-->
        <!-- END wrapper -->
        <script>
var resizefunc = [];
        </script>

        <!-- JAVASCRIPT AREA -->
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
        <script src="{{url('public/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>

        <script src="{{url('public/admin/js/jquery.core.js')}}"></script>
        <script src="{{url('public/admin/js/jquery.app.js')}}"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                var table =  $('#datatable-buttons9').DataTable({
                    lengthChange: false,
                    buttons: ['excel']
                });
                table.buttons().container()
                    .appendTo('#datatable-buttons9_wrapper .col-md-6:eq(0)');
                //Buttons examples
             /*   var table = $('#datatable-buttons').DataTable({
                    lengthChange: false,
                    buttons: ['excel']
                });*/

//                table.buttons().container()
//                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            });


        </script>
        <script type="text/javascript">
            $(document).ready(function () {
               /* $('#datatable').DataTable();
                $('#datatable-buttons9').DataTable();
                
                //Buttons examples
                var table = $('#datatable-buttons2').DataTable({
                    lengthChange: false,
                    buttons: ['excel']
                            //buttons: ['[pdf']
                });*/


                jQuery('#datepicker').datepicker();
                jQuery('#datepicker-autoclose').datepicker({
                    autoclose: true,
                    todayHighlight: true
                });
                jQuery('#datepicker-autoclose2').datepicker({
                    autoclose: true,
                    todayHighlight: true
                });
                jQuery('#datepicker-autoclose3').datepicker({
                    autoclose: true,
                    todayHighlight: true
                });
                jQuery('#datepicker-autoclose4').datepicker({
                    autoclose: true,
                    todayHighlight: true
                });
            });


        </script>
        <script>
            $(document).ready(function () {
                $('#genpass').click(function (e) {

                    $('#from2').val($('#from').val());
                    $('#to2').val($('#to').val());
                    // e.preventDefault();
                });
            });
        </script>


        <!-- Laravel Javascript Validation -->

        <!-- JAVASCRIPT AREA -->
    </body>
</html>