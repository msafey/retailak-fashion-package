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

                <!-- Add Company Button -->
                      </div>
                      <div class="modal fade bd-example-modal-lg" id="myModal1" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

    </div>
                        <div class="modal fade bd-example-modal-lg" id="myModal2" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

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
                                            <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                                            <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem" title="Hapus"><i
                                                        class="glyphicon glyphicon-trash"></i> Delete Item</a>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        <div class="card-text">

                            <div class="row">
                                <div class="col-sm-12">
                                    <h4>{{$day}} Report</h4>
                                </div>
                            </div>
                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Barcode</th>
                                    <th>Qty</th>
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
  table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        dom: 'lBfrtip',
        lengthMenu: [[50, 100, 500 , 1000 , 10000 , 100000, -1], [50 , 100 , 500 , 1000 , 10000 , 100000 , "All"]],
        pageLength: 50,
        buttons: [
            'copyHtml5' ,
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
            order: [ [0, 'desc'] ],
            ajax: '{!! url('admin/salesReportsList').'/'.$day !!}',
            columns: [
                {data: 'date', name: 'date'},
                {data: 'product_name', name: 'produce_name'},
                {data: 'category_name', name: 'category_name'},
                {data: 'barcode', name: 'barcode'},
                {data: 'qty', name: 'qty'},
            ]
            });
            $(document).ready(function () {
            $('#reloadTableButton').click(function(){
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
