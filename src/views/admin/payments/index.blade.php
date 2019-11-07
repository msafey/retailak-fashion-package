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

    <div class="modal fade bs-example-modal-sm" id="deleteModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Confirmation
                </div>
                <div class="modal-body">
                    Are you Sure That You Want To Delete Payment?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem" title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Delete Payment </a>

                </div>
            </div>

        </div>
    </div>
    <div class="modal fade bs-example-modal-sm statusModal" id="cancelModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Confirmation
                </div>
                <div class="modal-body">
                    Are you Sure That You Want To Cancel Payment ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger changeItem" href="javascript:void(0)" id="cancelItem" title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Cancel Payment </a>

                </div>
            </div>

        </div>
    </div>
    <div class="modal fade bs-example-modal-sm statusModal" id="submitModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Confirmation
                </div>
                <div class="modal-body">
                    Are you Sure That You Want To Submit Payment ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger changeItem " href="javascript:void(0)" id="submitItem"
                       title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Submit Payment </a>

                </div>
            </div>

        </div>
    </div>


    <!-- Start right Content here -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <!-- Bread Crumb And Title Section -->
@component('layouts.admin.breadcrumb')
                @slot('title')
                        Payments
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Payments
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
                        <div class="card-title">


                        </div>

                        <div class="card-text">


                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Purchase Order ID</th>
                                    <th>Payment Mode</th>
                                    <th>posting Date</th>
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

        <?php $changestatus = url('/admin/payments/cancel'); ?>
        <?php $submit = url('/admin/products');?>
        <?php $manageUrl = url('/admin/products/'); ?>
        <?php $details = url('/admin/product-details/');?>

    var table = $('#items_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route('payments_List') !!}'
            },
            columns: [

                {
                    data: 'purchase_order_id', name: 'purchase_order_id'
                },
                {
                    data: 'payment_mode_id', name: 'payment_mode_id'
                },
                {data: 'posting_date', name: 'posting_date'},
                {
                    data: 'id', render: function (data, data2, type, row) {

                    var submit = "<button data-toggle='modal' data-target='#submitModal' onclick='changeStatus(" + data + ',' + 2 + ")' type='button' class='btn btn-primary' title='Submit'>Submit</button>";
                    var cancel = " <button data-toggle='modal' data-target='#cancelModal' onclick='changeStatus(" + data + ',' + 0 + ")' type='button' class='btn btn-cancel' title='Cancel'>Cancel</button>"
                    var remove = "<button data-toggle='modal' data-target='#deleteModal' onclick='delete_record(" + data + ")' type='button' class='btn btn-danger' title='Delete'>Delete</button>";

                    if (type.status == 2) {
                        return cancel
                    }else{
                            return submit + remove;
                    }

                }
                }]
        });


    function delete_record(id) {
        $('#delItem').one('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{url('admin/payments/delete')}}/" + id,
                type: "GET",

                success: function (data) {
                    if (!data) {
                        alert('Cancel Payment First !');
                    }
                    $('#deleteModal').modal('hide');
                    $('#items_datatable').DataTable().draw(false)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        });
    }
    function changeStatus(id, status) {
        $('.changeItem').one('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{url('admin/payments/status')}}/" + id + '/' + status,
                type: "GET",

                success: function (data) {
                    if (!data) {
                        alert('Error changing data');
                    }
                    $('.statusModal').modal('hide');
                    // table.ajax.reload();
                        // location.reload();
                    $('#items_datatable').DataTable().draw(false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error changing data');
                }
            });

        });
    }


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>