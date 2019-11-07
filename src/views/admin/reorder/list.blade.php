

<!DOCTYPE html>
<html>
    <head>
        @include('layouts.admin.head')
        <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>


        <!-- Plugins css-->
        <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
        <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>


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
                                    <h4 class="page-title"> Auto Reorder </h4>
                                    <ol class="breadcrumb p-0">
                                        <li>
                                            <a href="{{url('/')}}">Dashboard</a>
                                        </li>
                                        <li class="active">
                                            Auto Reorder 
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


                                        {{------------------   Auto Reorder -------------}}

                                        <div class="row"> 
                                            <div class="col-sm-12">
                                                <div class="col-sm-3"><input type="checkbox"  class="pChk">Products</div>
                                                <div class="col-sm-3">  <input type="checkbox"  class="pChk2">Bundles</div>
                                                <form action="{{url('admin/reorder')}}" method="get">
                                                    <div class="col-sm-3"> 
                                                        <input type="number" name="value">Minimum Value 
                                                    </div>

                                                    <div class="col-sm-3">  
                                                        <button style="margin-left: 25px" type="submit" class="btn btn-primary"><i
                                                                class="zmdi zmdi-plus-circle-o"></i>
                                                            Submit
                                                        </button>
                                                    </div>
                                                </form>





                                            </div>
                                            <div class="col-sm-12" id="ProjectListButton" style="display:none">
                                                <div class="card-box table-responsive">
                                                    <h4 class="m-t-0 header-title"><b>Auto Reorder Products</b></h4>
                                                    <table id="datatable" class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>name</th>
                                                                <th>stock_qty</th>
                                                            </tr>
                                                        </thead>


                                                        <tbody>
                                                            @foreach($AllProducts as $product)
                                                            <tr>
                                                                <td>
                                                                    {{$product->name}}
                                                                </td>
                                                                <td>
                                                                    {{$product->stock_qty}}
                                                                </td>

                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-sm-12" id="ProjectListButton2" style="display:none">
                                                <div class="card-box table-responsive">
                                                    <h4 class="m-t-0 header-title"><b>Auto Reorder Bundles</b></h4>


                                                    <table id="datatable2" class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>name</th>
                                                                <th>stock_qty</th>
                                                            </tr>
                                                        </thead>


                                                        <tbody>
                                                            @foreach($allBundles as $bundle)
                                                            <tr>
                                                                <td>
                                                                    {{$bundle->code}}
                                                                </td>
                                                                <td>
                                                                    {{$bundle->org_qty}}
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div> <!-- end row -->

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

        <script src="{{url('public/admin/js/jquery.core.js')}}"></script>
        <script src="{{url('public/admin/js/jquery.app.js')}}"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#datatable').DataTable({
                    "order": [[1, "desc"]]
                });
                $('#datatable2').DataTable({
                    "order": [[1, "desc"]]
                });
                $('.pChk').click(function () {
                    if ($('.pChk:checked').length > 0) {
                        $("#ProjectListButton").show();
                    } else {
                        $("#ProjectListButton").hide();
                    }
                });
                $('.pChk2').click(function () {
                    if ($('.pChk2:checked').length > 0) {
                        $("#ProjectListButton2").show();
                    } else {
                        $("#ProjectListButton2").hide();
                    }
                });
            });

        </script>


        <!-- Laravel Javascript Validation -->

        <!-- JAVASCRIPT AREA -->
    </body>
</html>