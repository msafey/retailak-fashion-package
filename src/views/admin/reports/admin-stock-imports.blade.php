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
                       Stock Imported Reports
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
                        <div class="card-text">
                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Admin ID</th>
                                        <th>Admin Name</th>
                                        <th>File Name</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                            </table>
                            <!-- Table End-->
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
                url: '{!! route('get-admin-Stock-imports') !!}',
                type: "GET",
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'admin_id', name: 'admin_id'},
                {
                    data: 'admin_id', render: function (data, data2, type, row) {
                       return type.admin.name;
                    }
                },
                {data: 'fileName', name: 'fileName', render: function (data) {
                        let url = '{{url('/public/admin/uploaded/stocks/')}}';
                        return `<a href="${url}/${data}">${data}</a>`;
                    }},
                {data: 'created_at', name: 'created_at'},
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
