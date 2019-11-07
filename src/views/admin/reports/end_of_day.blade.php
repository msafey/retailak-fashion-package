<!DOCTYPE html>

@include('layouts.admin.head')
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
                        End Of Day Reports
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        Reports
                    @endslot
                    You are not allowed to access this resource!
            @endcomponent
            <!--End Bread Crumb And Title Section -->
                <div class="row">


                    <div class="card">
                        <div class="card-title">

                            <div class="col-sm-12 card card-block">

                                <form method="GET" action="{{url('admin/reports/end-of-day')}}">

                                    <div class='col-sm-5'>

                                        <label for="meta_tags">Start Date :</label>

                                        <input type='text' name="start_date" required class="form-control"
                                               id='datetimepicker1'/>
                                    </div>

                                    <div class='col-sm-5'>

                                        <label for="meta_tags">End Date :</label>

                                        <input type='text' name="end_date" required class="form-control"
                                               id='datetimepicker2'/>
                                    </div>


                                    <div class='col-sm-2'>


                                        <button style="margin-top: 30px" class="btn btn-sm btn-primary" type="submit">
                                            Filter
                                        </button>
                                    </div>

                                </form>

                            </div>

                            <div class="col-sm-12 card card-block">

                                <div class="col-md-3 m-r-2">
                                    <b>Total Orders :</b> {{$totalOrders}}
                                </div>

                                <div class="col-md-3  m-r-3">
                                    <b>Total Delivery Orders :</b> {{$totalDeliveredOrders}}
                                </div>

                                <div class="col-md-3 ">
                                    <b>Total Cancel Orders :</b> {{$totalCanceledOrders}}
                                </div>
                            </div>

                            <div class="col-sm-12 card card-block">

                                <div class="col-md-3 m-r-2">
                                    <b>Total Money :</b> {{$totalMoney->total_money}}
                                </div>


                                @foreach($totalMoneyPaymentMethods as $total_moneyMethod)
                                    <div class="col-md-3 m-r-2">
                                        <b>Total Money ( {{$total_moneyMethod->payment_method}})
                                            :</b> {{$total_moneyMethod->total_money}}
                                    </div>

                                @endforeach

                            </div>


                            <div class="card-text">


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
</div>
<!-- END wrapper -->
<script>
    var resizefunc = [];
</script>

<!-- JAVASCRIPT AREA -->
@include('layouts.admin.javascript')


<script src="{{url('public/admin/js/moment.min.js')}}"></script>
<script src="{{url('public/admin/js/bootstrap-datetimepicker.js')}}"></script>

<script type="text/javascript">
    $(function () {
        $('#datetimepicker1').datetimepicker();
        $('#datetimepicker2').datetimepicker();
    });
</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
