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
                                    <h4 class="page-title"> Users </h4>
                                    <ol class="breadcrumb p-0">
                                        <li>
                                            <a href="{{url('/')}}">Dashboard</a>
                                        </li>
                                        <li class="active">
                                            Users
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

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="ep fm afz ang ate">
                                                            <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                                <div class="ahm">
                                                                    <span class="bqq">Total Active Products</span>
                                                                    <h2 class="bqp">
                                                                        {{$totalActiveProducts}}
                                                                    </h2>
                                                                    <hr class="bqz afe">
                                                                </div>
                                                                <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                                </canvas>
                                                            </div>
                                                        </div>
                                                    </div> <div class="col-sm-4">
                                                        <div class="ep fm afz ang ate">
                                                            <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                                <div class="ahm">
                                                                    <span class="bqq">Total Active Bunddles</span>
                                                                    <h2 class="bqp">
                                                                        {{$totalActiveBunddles}}
                                                                    </h2>
                                                                    <hr class="bqz afe">
                                                                </div>
                                                                <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                                </canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="ep fm afz ang ate">
                                                            <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                                <div class="ahm">
                                                                    <span class="bqq">Total Active Users</span>
                                                                    <h2 class="bqp">
                                                                        {{$totalActiveUsers}}
                                                                    </h2>
                                                                    <hr class="bqz afe">
                                                                </div>
                                                                <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                                </canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="ep fm afz ang ate">
                                                            <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                                <div class="ahm">
                                                                    <span class="bqq">Total Delivery Men</span>
                                                                    <h2 class="bqp">
                                                                        {{$deliveryMen}}
                                                                    </h2>
                                                                    <hr class="bqz afe">
                                                                </div>
                                                                <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                                </canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="ep fm afz ang ate">
                                                            <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                                <div class="ahm">
                                                                    <span class="bqq">Total Active Time Sections</span>
                                                                    <h2 class="bqp">
                                                                        {{$timeSections}}
                                                                    </h2>
                                                                    <hr class="bqz afe">
                                                                </div>
                                                                <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                                </canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="ep fm afz ang ate">
                                                            <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                                <div class="ahm">
                                                                    <span class="bqq">Total Active Promocodes</span>
                                                                    <h2 class="bqp">
                                                                        {{$promocodes}}
                                                                    </h2>
                                                                    <hr class="bqz afe">
                                                                </div>
                                                                <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                                </canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <form method="get">
                                                    <div class="col-sm-4">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input name="active" type="checkbox" value="1" class="form-check-input">
                                                                Show Avilables Bunddles
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="row">
                                                            <label>From</label>
                                                            <div class="input-group">
                                                                <input  type="text" name="from" class="form-control"
                                                                        placeholder="mm/dd/yyyy"
                                                                        id="datepicker-autoclose">
                                                                <span class="input-group-addon bg-custom b-0"><i
                                                                        class="icon-calender"></i></span>
                                                            </div><!-- input-group -->
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="row">

                                                            <label>To</label>
                                                            <div class="input-group">
                                                                <input  type="text" name="to" class="form-control"
                                                                        placeholder="mm/dd/yyyy"
                                                                        id="datepicker-autoclose2">
                                                                <span class="input-group-addon bg-custom b-0"><i
                                                                        class="icon-calender"></i></span>
                                                            </div><!-- input-group -->
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <button id="genpass" style="margin-left: 10px" type="submit" class="btn btn-primary"><i
                                                                class="zmdi zmdi-plus-circle-o"></i>
                                                            report
                                                        </button>
                                                    </div>
                                                </form>
                                                <div class="col-sm-6">
                                                    <div class="ep fm afz ang ate">
                                                        <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                            <div class="ahm">
                                                                <span class="bqq">total Orders</span>
                                                                <h2 class="bqp">
                                                                    {{$Orderscount}}
                                                                </h2>
                                                                <hr class="bqz afe">
                                                            </div>
                                                            <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                            </canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="ep fm afz ang ate">
                                                        <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                            <div class="ahm">
                                                                <span class="bqq">total money </span>
                                                                <h2 class="bqp">
                                                                    {{$totalPrices}}
                                                                </h2>
                                                                <hr class="bqz afe">
                                                            </div>
                                                            <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                            </canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                                <form action="{{url('admin/payments/excel')}}" method="get">
                                                    <div class="invisible">
                                                        <div class="input-group">
                                                            <input id="from2"   type="text" name="from" class="form-control"


                                                                   @if(isset($from)) value="{{$from}}" @endif >
                                                                   <span class="input-group-addon bg-custom b-0"><i
                                                                    class="icon-calender"></i></span>
                                                        </div>
                                                        <div class="input-group">
                                                            <input  type="text" name="to"  class="form-control"
                                                                    @if(isset($to))  value="{{$to}}" @endif
                                                                    id="to2">
                                                                    <span class="input-group-addon bg-custom b-0"><i
                                                                    class="icon-calender"></i></span>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Download Product And Bundle</button>
                                                </form>
                                                <h1>Products</h1><hr class="bqz afe">

                                                <h3>Best seller</h3>


                                                <div class="col-sm-6">
                                                    <div class="ep fm afz ang ate">
                                                        <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                            <div class="ahm">
                                                                <span class="bqq">name </span>
                                                                @foreach($bestSellingProductsnames as $results)
                                                                <h4 class="bqp">
                                                                    {{ $results }} 
                                                                </h4>
                                                                @endforeach

                                                                <hr class="bqz afe">
                                                            </div>
                                                            <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                            </canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="ep fm afz ang ate">
                                                        <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                            <div class="ahm">
                                                                <span class="bqq">QTY </span>

                                                                @foreach($Best_seller_product_qty as $results)
                                                                <h4 class="bqp">
                                                                    {{ $results }} 
                                                                </h4>
                                                                @endforeach
                                                                <hr class="bqz afe">
                                                            </div>
                                                            <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                            </canvas>
                                                        </div>
                                                    </div>
                                                </div>

                                                <h3>Lowest Selling</h3>
                                                <div class="col-sm-6">
                                                    <div class="ep fm afz ang ate">
                                                        <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                            <div class="ahm">
                                                                <span class="bqq">code </span>
                                                                @foreach($lowestSellingProductsname as $result)
                                                                <h4 class="bqp">

                                                                    {{ $result }} 
                                                                </h4>
                                                                @endforeach
                                                                <hr class="bqz afe">
                                                            </div>
                                                            <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                            </canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="ep fm afz ang ate">
                                                        <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                            <div class="ahm">
                                                                <span class="bqq">QTY </span>
                                                                @foreach($Lowest_Selling_Product_qty as $results)
                                                                <h4 class="bqp">

                                                                    {{ $results }} 
                                                                </h4>
                                                                @endforeach

                                                                <hr class="bqz afe">
                                                            </div>
                                                            <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                            </canvas>
                                                        </div>
                                                    </div>
                                                </div>



                                                <h3>Best seller</h3>

                                                <div class="col-sm-6">
                                                    <div class="ep fm afz ang ate">
                                                        <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                            <div class="ahm">
                                                                <span class="bqq">code </span>
                                                                @foreach($BestSellingProducts as $results)

                                                                <h4 class="bqp">

                                                                    {{ $results }} 
                                                                </h4>
                                                                @endforeach
                                                                <hr class="bqz afe">
                                                            </div>
                                                            <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                            </canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="ep fm afz ang ate">
                                                        <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                            <div class="ahm">
                                                                <span class="bqq">QTY </span>
                                                                @foreach($best_Selling_bunddle_qty as $results)
                                                                <h4 class="bqp">

                                                                    {{ $results }} 
                                                                </h4>
                                                                @endforeach

                                                                <hr class="bqz afe">
                                                            </div>
                                                            <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                            </canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h3>Lowest Selling</h3>
                                                <div class="col-sm-6">
                                                    <div class="ep fm afz ang ate">
                                                        <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                            <div class="ahm">
                                                                <span class="bqq">Name </span>
                                                                @foreach($lowestSellingBunddlesname as $results)
                                                                <h4 class="bqp">
                                                                    {{ $results }} 
                                                                </h4>
                                                                @endforeach
                                                                <hr class="bqz afe">
                                                            </div>
                                                            <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                            </canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="ep fm afz ang ate">
                                                        <div class="bsm bqv"><iframe class="chartjs-hidden-iframe" tabindex="-1" style="display: block; overflow: hidden; border: 0px; margin: 0px; top: 0px; left: 0px; bottom: 0px; right: 0px; height: 100%; width: 100%; position: absolute; pointer-events: none; z-index: -1;"></iframe>
                                                            <div class="ahm">
                                                                <span class="bqq">QTY </span>

                                                                @foreach($Lowest_Selling_bunddle_qty as $results)
                                                                <h4 class="bqp">

                                                                    {{ $results }} 
                                                                </h4>
                                                                @endforeach
                                                                <hr class="bqz afe">
                                                            </div>
                                                            <canvas id="sparkline1" width="176" height="43" class="bsn js-chart-drawn" data-chart="spark-line" data-dataset="[[28,68,41,43,96,45,100]]" data-labels="['a','b','c','d','e','f','g']" style="width: 176px; height: 43px; display: block;">
                                                            </canvas>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">

                                                <div class="col-sm-12">
                                                    <div class="card-box table-responsive">

                                                        <a href="{{url('/admin/refresh')}}"><button type="button" class="btn btn-primary">Refresh</button></a>
                                                        <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>id</th>
                                                                    <th>name</th>
                                                                    <th>stock_qty</th>
                                                                    <th>org_qty</th>
                                                                    <th>sold</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                <?php
                                                                $i = 1;
                                                                ?>
                                                                @foreach($sales as $sale)


                                                                <tr>
                                                                    <td>
                                                                        {{$i}}
                                                                    </td>
                                                                    <td>
                                                                        {{$sale->item_name}}
                                                                    </td>
                                                                    <td>
                                                                        {{$sale->qty}}
                                                                    </td>
                                                                    <td>
                                                                        {{$sale->org_qty}}
                                                                    </td>
                                                                    <td>
                                                                        {{$sale->total}}
                                                                    </td>


                                                                </tr>
                                                                <?php $i++ ?> 
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                        </div> <!-- end row -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="card-box table-responsive">


                                                    <table id="datatable-buttons2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>id</th>
                                                                <th>name</th>
                                                                <th>org qty</th>
                                                                <th>available</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <?php
                                                            $i = 1;
                                                            ?>
                                                            @foreach($tablePromo as $product)
                                                            <tr>
                                                                <td>
                                                                    {{$i}}
                                                                </td>
                                                                <td>
                                                                    {{$product['code']}}
                                                                </td>
                                                                <td>
                                                                    {{$product['org_qty']}}
                                                                </td>

                                                                <td>
                                                                    {{$product['userscount']}}
                                                                </td>

                                                            </tr>
                                                            <?php $i++ ?> 
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
        <script src="{{url('public/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>

        <script src="{{url('public/admin/js/jquery.core.js')}}"></script>
        <script src="{{url('public/admin/js/jquery.app.js')}}"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#datatable').DataTable();

                //Buttons examples
                var table = $('#datatable-buttons').DataTable({
                    lengthChange: false,
                    buttons: ['excel']
                });

                table.buttons().container()
                        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
            });


        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#datatable').DataTable();
                $('#datatable-buttons9').DataTable();
                
                //Buttons examples
                var table = $('#datatable-buttons2').DataTable({
                    lengthChange: false,
                    buttons: ['excel']
                            //buttons: ['[pdf']
                });

                table.buttons().container()
                        .appendTo('#datatable-buttons2_wrapper .col-md-6:eq(0)');
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