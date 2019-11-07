<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
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
                        Reports
                    @endslot

                    @slot('slot1')
                        Reports
                    @endslot

                    @slot('current')
                        Invoice
                    @endslot
                    You are not allowed to access this resource!
            @endcomponent
            <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card-title">
                        <div class="row card card-block" style="margin-left:8px;margin-top: 10px; ">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="col-lg-12">
                                        <a id="csv" href="{{route('export.csv.invoice')}}"
                                           class="form-control btn btn-success"><i
                                                class="fa fa-download"></i>CSV</a>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="col-lg-12">
                                        <a id="csv" href="{{route('export.pdf.invoice')}}"
                                           class="form-control btn btn-custom"><i
                                                class="fa fa-download"></i>PDF</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card card-block">

                        <!-- Table Start-->
                        <table id="table" class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Order No.</th>
                                <th>User Name</th>
                                <th>Actions</th>
                            </tr>
                            </thead>


                        </table>

                        <!-- Table End-->
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

    $(function () {
        $('#table').DataTable({
            lengthMenu: [ 10, 25, 50, 75, 100, 500],
            processing: true,
            serverSide: false,
            pageLength: 50,
            ajax: '{{ url('admin/reports/invoice/index') }}',
            columns: [
                {data: 'id', name: 'id'},
                {data: 'user.name', name: 'user.name'},
                {
                    data: 'id', render: function (data) {
                        var details = "<a href='{{'invoice'}}" + '/' + data + "' class='btn btn-primary'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";
                        return details;
                    }
                }
            ]
        });
    });


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
