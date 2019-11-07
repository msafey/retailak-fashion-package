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
<div id="wrapper">-
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
                        Purchase Receipts
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Purchase Receipts
                @endslot
                You are not allowed to access this resource!
        @endcomponent
            <!--End Bread Crumb And Title Section -->
                <div class="row">
                

                    <div class="row">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="card card-block">
	                  <div class="card-title">

                <!-- Add Company Button -->


                <div class="modal fade bs-example-modal-sm" id="deleteModal" tabindex="-1" role="dialog"
                     aria-labelledby="mySmallModalLabel">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    Confirmation
                                </div>
                                <div class="modal-body">
                                    Are you Sure That You Want To Delete Purchase Receipt?
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem" title="Hapus"><i
                                                class="glyphicon glyphicon-trash"></i> Delete Receipt </a>

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
                                    Are you Sure That You Want To Cancel Receipt ?
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                                    <a class="btn btn-sm btn-danger changeItem" href="javascript:void(0)" id="cancelItem" title="Hapus"><i
                                                class="glyphicon glyphicon-trash"></i> Cancel Receipt </a>

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
                                    Are you Sure That You Want To Submit Receipt ?
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                                    <a class="btn btn-sm btn-danger changeItem " href="javascript:void(0)" id="submitItem"
                                       title="Hapus"><i
                                                class="glyphicon glyphicon-trash"></i> Submit Receipt </a>

                                </div>
                            </div>

                        </div>
                </div>

                <!-- Add Company Button End-->

            </div>
                       
                        <div class="card-text">
                           

                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <!-- <th>Total Amount</th> -->
                                    <th>Status</th>
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

    <?php $listUrl = url('admin/pendingPurchaseReceiptsList');?>

    <?php $changestatus = url('/admin/purchase-receipts/cancel'); ?>
    // <?php $submit = url('/admin/products');?>

     
 <?php $editurl = url('/admin/purchase-orders/');?>
 // <?php $changestatus = url('/admin/purchase-orders/status'); ?>
 <?php $invoiceUrl = url('/admin/purchase-receipts/');?>
 <?php $download = url('/admin/purchase-receipts/download-file');?>

 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{$listUrl}}',
        columns: [
        {data:'id',name:'id'},
        // {data:'grand_total_amount',name:'grand_total_amount'},
            {data: 'status', render: function (status, type, row) {
                if (status == 2) {
                    return "Submitted"
                }
                else if(status == 1){
                    return "Added"
                }else{
                    return 'Cancelled'
                }
                }
            },{
                    data: 'id', render: function (data, data2, type, row) {

                    var details = "<a href='{{$invoiceUrl}}" + '/' + data + '/details' + "' class='btn btn-success'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";

                    var submit = "<button data-toggle='modal' data-target='#submitModal' onclick='changeStatus(" + data + ',' + 2 + ")' type='button' class='btn btn-primary' title='Submit'>Submit</button>&nbsp";
                     var invoice =   "<a href='{{$invoiceUrl}}" + '/' + data + '/invoice' + "' class='btn btn-w" + "arning'>Invoice</a>&nbsp;"
                    var cancel = " <button data-toggle='modal' data-target='#cancelModal' onclick='changeStatus(" + data + ',' + 0 + ")' type='button' class='btn btn-cancel' title='Cancel'>Cancel</button>&nbsp "
                    var remove = "<button data-toggle='modal' data-target='#deleteModal' onclick='delete_record(" + data + ")' type='button' class='btn btn-danger' title='Delete'>Delete</button>&nbsp";
                    var download = "<a href='{{$download}}" + '/' + data  + "' class='btn btn-warning'>Download</a>&nbsp; "
                    if (type.status == 2) {
                        return  details + cancel + invoice + download;
                    }
                    return details + submit + remove + download;

                }
                }]
        });


    function delete_record(id) {
        $('#delItem').one('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{url('admin/purchase-receipts/delete')}}/" + id,
                type: "GET",

                success: function (data) {
                    if (data == 'not allowed') {
                        alert("cancel purchase invoice of this receipt first !");
                        $('#deleteModal').modal('hide');

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
                url: "{{url('admin/purchase-receipts/status')}}/" + id + '/' + status,
                type: "GET",
                success: function (data) {
                    // console.log(data);
                    if(data == 'already_cancelled'){
                        // alert('This PR is already Cancelled');
                    }
                    if(data == 'already_submitted'){
                        // alert('This PR is already Submitted');
                    }
                    if(data=='not allowed'){
                        alert('Cancel Payment First !');
                    $('.statusModal').modal('hide');
                    }
                    if (!data) {
                        alert('Error changing data');
                    }
                    $('.statusModal').modal('hide');
                    $('#items_datatable').DataTable().draw(false);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error changing data');
                }
            });

        });
    }
    
    $(document).ready(function () {
        
        
        $('#reloadTableButton').click(function(){
	        table.ajax.reload();
        });
    });
    
   


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>