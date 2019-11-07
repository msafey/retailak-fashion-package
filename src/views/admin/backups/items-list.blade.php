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
        a {
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
                            <h4 class="page-title"> Items </h4>
                            <ol class="breadcrumb p-0">
                                <li>
                                    <a href="{{url('/')}}">Dashboard</a>
                                </li>
                                <li class="active">
                                    Items
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


                                {{------------------   Items  -----------------------}}

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card-box table-responsive">
                                            <h4 class="m-t-0 header-title"><b>Items</b></h4>
                                            <p class="text-muted font-13 m-b-30">

                                            </p>

                                            <table id="datatable-buttons" class="table table-striped table-bordered"
                                                   cellspacing="0" width="100%">
                                                <thead>
                                                <tr>
                                                    <th>last_purchase_rate</th>
                                                    <th>barcode</th>
                                                    <th>naming_series</th>
                                                    <th>default_supplier</th>
                                                    <th>selling_cost_center</th>
                                                    <th>website_image</th>
                                                    <th>net_weight</th>
                                                    <th>expense_account</th>
                                                    <th>max_discount</th>
                                                    <th>warranty_period</th>
                                                    <th>hub_category_to_publish</th>
                                                    <th>serial_no_series</th>
                                                    <th>owner</th>
                                                    <th>has_variants</th>
                                                    <th>create_new_batch</th>
                                                    <th>country_of_origin</th>
                                                    <th>modified_by</th>
                                                    <th>default_warehouse</th>
                                                    <th>income_account</th>
                                                    <th>item_name</th>
                                                    <th>_comments</th>
                                                    <th>image</th>
                                                    <th>inspection_required_before_delivery</th>
                                                    <th>end_of_life</th>
                                                    <th>website_warehouse</th>
                                                    <th>is_sales_item</th>
                                                    <th>is_sub_contracted_item</th>
                                                    <th>synced_with_hub</th>
                                                    <th>stock_uom</th>
                                                    <th>show_variant_in_website</th>
                                                    <th>weightage</th>
                                                    <th>default_material_request_type</th>
                                                    <th>hub_sync_id</th>
                                                    <th>docstatus</th>
                                                    <th>default_bom</th>
                                                    <th>tolerance</th>
                                                    <th>thumbnail</th>
                                                    <th>_liked_by</th>
                                                    <th>has_batch_no</th>
                                                    <th>customer_code</th>
                                                    <th>creation</th>
                                                    <th>description</th>
                                                    <th>parent</th>
                                                    <th>safety_stock</th>
                                                    <th>sales_uom</th>
                                                    <th>brand</th>
                                                    <th>_assign</th>
                                                    <th>slideshow</th>
                                                    <th>item_code</th>
                                                    <th>purchase_uom</th>
                                                    <th>opening_stock</th>
                                                    <th>is_stock_item</th>
                                                    <th>buying_cost_center</th>
                                                    <th>show_in_website</th>
                                                    <th>publish_in_hub</th>
                                                    <th>_user_tags</th>
                                                    <th>asset_category</th>
                                                    <th>manufacturer</th>
                                                    <th>is_purchase_item</th>
                                                    <th>disabled</th>
                                                    <th>weight_uom</th>
                                                    <th>min_order_qty</th>
                                                    <th>valuation_method</th>
                                                    <th>name</th>
                                                    <th>idx</th>
                                                    <th>total_projected_qty</th>
                                                    <th>route</th>
                                                    <th>item_group</th>
                                                    <th>hub_warehouse</th>
                                                    <th>modified</th>
                                                    <th>lead_time_days</th>
                                                    <th>is_item_from_hub</th>
                                                    <th>parenttype</th>
                                                    <th>variant_of</th>
                                                    <th>web_long_description</th>
                                                    <th>manufacturer_part_no</th>
                                                    <th>has_serial_no</th>
                                                    <th>variant_based_on</th>
                                                    <th>valuation_rate</th>
                                                    <th>standard_rate</th>
                                                    <th>delivered_by_supplier</th>
                                                    <th>customs_tariff_number</th>
                                                    <th>is_fixed_asset</th>
                                                    <th>inspection_required_before_purchase</th>
                                                    <th>parentfield</th>
                                                </tr>
                                                </thead>


                                                <tbody>
                                                @foreach($items->data as  $item)
                                                    <tr>
                                                        @foreach($item as  $key =>$value)
                                                            <td>{{$value}}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
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

<script src="{{url('public/admin/js/jquery.core.js')}}"></script>
<script src="{{url('public/admin/js/jquery.app.js')}}"></script>

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


<!-- Laravel Javascript Validation -->

<!-- JAVASCRIPT AREA -->
</body>
</html>