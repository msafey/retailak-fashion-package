<!DOCTYPE html>
<html>

<head>
    @include('layouts.admin.head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <style media="screen" type="text/css">
        td {
            font-size: 11px;
            padding: 2px;
        }

        .page-item {
            display: inline;
            font-size: 12px;
            padding: 1px;
        }
    </style>
    </link>
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
            @include('layouts.admin.breadcrumb')
            <!--End Bread Crumb And Title Section -->

                <div class="col-md-12">
                    <div class="row">
                        <p> <b>Tracking Order : </b> {{$shipment->order_id}}</p>
                        <p> <b>By : </b> Aramex</p>
                        <p> <b>Shipping Number :</b> {{$shipment->shipment_id}}</p>
                        <p> <b>Pdf Url :</b> <a href="{{$shipment->label_url}}" download='proposed_file_name' target="_blank"> Download  file </a> </p>
                        <p> <b>Tracking Status : </b> {{$shipment->status}}</p>

                    </div>
                </div>
                <div class="result">

                </div>
                <!-- container -->
            </div>
            <!-- content -->
        </div>
        <!-- End content-page -->
        <!-- Footer Area -->
    @include('layouts.admin.footer')
    <!-- End Footer Area-->
    </div>
    <!-- END wrapper -->
    <script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}">
    </script>
    <script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}">
    </script>
    <script>
        function track() {

            const trackingUrl = '{{url('admin/aramex/shipment/track')}}';

            $('#track').click(function () {
                $.ajax({
                    url: trackingUrl,
                    type: "GET",
                    success: function (data) {
                        $(".result").html(data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error tracking shipment');
                    }
                });
            })
        }
    </script>

    <!-- JAVASCRIPT AREA -->
@include('layouts.admin.javascript')
<!-- JAVASCRIPT AREA -->
</body>

</html>