<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>
    <!-- DataTables -->
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
                        Sales Reports
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        Items
                    @endslot
                    You are not allowed to access this resource!
            @endcomponent
            <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
                        <div class="card-title">
                            <div class="row card card-block" style="margin-left:8px;margin-top: 10px; ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="col-lg-12">
                                            <a id="csv" href="{{route('export.csv.sales')}}"
                                               class="form-control btn btn-success"><i
                                                    class="fa fa-download"></i>CSV</a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="col-lg-12">
                                            <a id="csv" href="{{route('export.pdf.sales')}}"
                                               class="form-control btn btn-custom"><i
                                                    class="fa fa-download"></i>PDF</a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal fade bd-example-modal-lg" id="myModal1" data-backdrop="static"
                             data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                             aria-hidden="true">

                        </div>

                        <div class="modal fade bd-example-modal-lg" id="myModal2" data-backdrop="static"
                             data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                             aria-hidden="true">

                        </div>
                        <div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog"
                             aria-labelledby="mySmallModalLabel">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        Confirmation
                                    </div>
                                    <div class="modal-body">
                                        Are you Sure That You Want To Delete This Item ?
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel
                                        </button>
                                        <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem"
                                           title="Hapus"><i
                                                    class="glyphicon glyphicon-trash"></i> Delete Item</a>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-text">


                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Barcode</th>
                                    <th>Price</th>
                                    <th>Cost</th>
                                    <th>Qty</th>

                                </tr>
                                </thead>


                            </table>


                            <!-- Table End-->
                        </div>


                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Daily Report</h4>
                                @foreach($salesReportsDates as $salesReportsDate)
                                    <a href="{{url('admin/daily-report/').'/'.$salesReportsDate->date}}" target="_blank"
                                       class="btn btn-primary">{{$salesReportsDate->date}}</a>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div> <!-- container -->
            </div> <!-- content -->
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

<!-- JAVASCRIPT AREA -->
@include('layouts.admin.javascript')
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

<script>
        <?php $deliverdturl = url('/admin/delivery/orders/status');?>
    var table = $('#items_datatable').DataTable({
            lengthMenu: [ 10, 25, 50, 75, 100, 500],
            processing: true,
            serverSide: false,
            pageLength: 50,
            order: [[0, 'desc']],
            ajax: {
                url: '{!! route('salesReportsList') !!}',
                type: "GET",
            },
            columns: [

                {data: 'date', name: 'date'},
                {data: 'product_name', name: 'produce_name'},
                {data: 'category_name', name: 'category_name'},
                {data: 'barcode', name: 'barcode'},
                {data: 'price', name: 'price'},
                {data: 'cost', name: 'cost'},
                {data: 'qty', name: 'qty'},
            ]
        });


    $(document).ready(function () {


        $('#reloadTableButton').click(function () {
            table.ajax.reload();
        });
    });

    $(document).ready(function () {
        $("[data-toggle='tooltip']").tooltip();
    });


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
