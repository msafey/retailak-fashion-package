<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')
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
                Home
                @endslot

                @slot('slot1')
                Admin
                @endslot

                @slot('current')
                Home
                @endslot



                You are not allowed to access this resource!
                @endcomponent


                <div class="row">
                    <div class="col-md-12 col-sm-6 col-xs-12 col-lg-6 col-xl-6">
                        <div class="card-box">

                            <h4 class="header-title m-t-0">Products Stock</h4>


                            <div class="row text-xs-center m-t-30">
                                @foreach($warehouseItems as $warehouseItem)
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup" class="text-success">
                                        {{$warehouseItem->products_count_count}}</h3>
                                    <p class="text-muted">{{$warehouseItem->name}}</p>
                                    <a href="{{url('admin/product/reordering?warehouse_id='.$warehouseItem->id.'&quantity_filter=3')}}"
                                       class="btn btn-sm btn-custom waves-effect waves-light pull-xs-right">View</a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-6 col-xs-12 col-lg-6 col-xl-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0">Total Sales</h4>
                            <div class="row text-xs-center" style="border-bottom: 1px solid #f7f7f9;">
                                <h5 class="text-info text-uppercase m-b-15 m-t-10">Orders</h5>
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup" class="text-warning">{{$totalOrders}}</h3>
                                    <p class="text-muted">All</p>
                                </div>
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup"
                                        class="text-success">{{$deliveredOrders}}</h3>
                                    <p class="text-muted">Delivered</p>
                                </div>
                            </div>
                            <div class="row text-xs-center">
                                <h5 class="text-info text-uppercase m-b-15 m-t-10">Items</h5>
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup" class="text-danger">{{$totalMoney}}</h3>
                                    <p class="text-muted">Total Sales Price</p>
                                </div>
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup" class="text-info">{{$totalSoldProducts}}</h3>
                                    <p class="text-muted">Total Items Sold</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-12 col-sm-6 col-xs-12 col-lg-6 col-xl-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0">Total Month Sales</h4>
                            <div class="row text-xs-center" style="border-bottom: 1px solid #f7f7f9;">
                                <h5 class="text-info text-uppercase m-b-15 m-t-10">Orders</h5>
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup" class="text-warning">{{$totalMonthOrders}}</h3>
                                    <p class="text-muted">All</p>
                                </div>
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup"
                                        class="text-success">{{$deliveredMonthOrders}}</h3>
                                    <p class="text-muted">Delivered</p>
                                </div>
                            </div>
                            <div class="row text-xs-center">
                                <h5 class="text-info text-uppercase m-b-15 m-t-10">Items</h5>
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup" class="text-danger">{{$totalMonthMoney}}</h3>
                                    <p class="text-muted">Total Sales Price</p>
                                </div>
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup" class="text-info">{{$totalMonthItems}}</h3>
                                    <p class="text-muted">Total Items Sold</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-6 col-xs-12 col-lg-6 col-xl-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0">Total Day Sales</h4>
                            <div class="row text-xs-center" style="border-bottom: 1px solid #f7f7f9;">
                                <h5 class="text-info text-uppercase m-b-15 m-t-10">Orders</h5>
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup" class="text-warning">{{$totalDayOrders}}</h3>
                                    <p class="text-muted">All</p>
                                </div>
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup"
                                        class="text-success">{{$deliveredDayOrders}}</h3>
                                    <p class="text-muted">Delivered</p>
                                </div>
                            </div>
                            <div class="row text-xs-center">
                                <h5 class="text-info text-uppercase m-b-15 m-t-10">Items</h5>
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup" class="text-danger">{{$totalDayMoney}}</h3>
                                    <p class="text-muted">Total Sales Price</p>
                                </div>
                                <div class="col-xs-6">
                                    <h3 data-plugin="counterup" class="text-info">{{$totalDayItems}}</h3>
                                    <p class="text-muted">Total Items Sold</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <!--End Bread Crumb And Title Section -->
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
<!-- JAVASCRIPT AREA -->
</body>
</html>