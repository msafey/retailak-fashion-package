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
                        Sales Invoice
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Purchase Invoices
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
	                  <div class="card-title">

                            <!-- Add Company Button -->
                            <div class="row">   
                                <label for="">Filter By Purchase Order Id</label>

                                <input type="number" name="purchase_order_id"  value=""  id="purchase_order_id">
                                <button id="purchase_order_id_search" ><i class="fa fa-search"></i></button>

                            </div>



                                        <!-- Add Company Button End-->


                <!-- Add Company Button -->
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

    <div class="modal fade bs-example-modal-sm" id="deleteModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Confirmation
                </div>
                <div class="modal-body">
                    Are you Sure That You Want To Delete Purchase Invoice?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem" title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Delete Invoice </a>

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
                    Are you Sure That You Want To Cancel Invoice ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger changeItem" href="javascript:void(0)" id="cancelItem" title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Cancel Invoice </a>

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
                    Are you Sure That You Want To Submit Invoice ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger changeItem " href="javascript:void(0)" id="submitItem"
                       title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Submit Invoice </a>

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
                                    <th>Purchase Order Id</th>
                                    <th>Sales Invoice Id</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>


                            </table>
<input type="text" hidden="" id="purchase_order" name="purchase_order" value="@if(isset($_GET['purchase_order_id'])){{$_GET['purchase_order_id']}} @endif">

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

    <?php $listUrl = url('admin/purchaseInvoicesList');?>

    <?php $changestatus = url('/admin/purchase-invoices/cancel'); ?>
    // <?php $submit = url('/admin/products');?>


     
 <?php $paymentUrl = url('/admin/payments/create');?>
var purchase_order_id = document.getElementById('purchase_order_id').value;
 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:'{{$listUrl}}',
            type:"GET",
            data: function(d){
                d.purchase_order_id=document.getElementById('purchase_order_id').value;
            }
        },
        columns: [
        {data:'purchase_order_id',name:'purchase_order_id'},
        {data:'id',name:'id'},
        {data:'grand_total_amount',name:'grand_total_amount'},
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
    }       ,
        {data:'created_at',name:'created_at'},
 {
                    data: 'id', render: function (data, data2, type, row) {

                    var submit = "<button data-toggle='modal' data-target='#submitModal' onclick='changeStatus(" + data + ',' + 2 + ")' type='button' class='btn btn-primary' title='Submit'>Submit</button>";
                     var payment =   "<a href='{{$paymentUrl}}?invoice=" + data + "' class='btn btn-w" + "arning'>Payment</a>&nbsp;"
                    var cancel = " <button data-toggle='modal' data-target='#cancelModal' onclick='changeStatus(" + data + ',' + 0 + ")' type='button' class='btn btn-cancel' title='Cancel'>Cancel</button>"
                    var remove = "<button data-toggle='modal' data-target='#deleteModal' onclick='delete_record(" + data + ")' type='button' class='btn btn-danger' title='Delete'>Delete</button>";

                    if (type.status == 2) {
                        return cancel+'&nbsp;&nbsp;&nbsp;'+payment;
                    }
                    return submit +'&nbsp;&nbsp;&nbsp;' +remove;

                }
                }]
        });
 function wait(ms){
    var start = new Date().getTime();
    var end = start;
    while(end < start + ms) {
      end = new Date().getTime();
   }
 }

 $(document).on('click', '#purchase_order_id_search', function(e){
  //7 seconds in milliseconds
         table.ajax.reload();
    });

 $(document).on('input', '#purchase_order_id', function(e){
  //7 seconds in milliseconds
  if($('#purchase_order_id').val() == ''){
         table.ajax.reload();
  }
    });

    function delete_record(id) {
        $('#delItem').one('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{url('admin/purchase-invoices/delete')}}/" + id,
                type: "GET",

                success: function (data) {
                    if (data == 'not allowed') {
                        alert('Cancel Payment First !');
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
                url: "{{url('admin/purchase-invoices/status')}}/" + id + '/' + status,
                type: "GET",
                success: function (data) {
                    if (data == 'not allowed') {
                        alert('Cancel Payment First !');
                        $('.statusModal').modal('hide');
                    }
                    if(data == 'PR not submitted'){
                        alert('Submit Purchase Receipt First !');
                        $('.statusModal').modal('hide');
                    }
                    if (data == 'true') {
                    $('.statusModal').modal('hide');
                    $('#items_datatable').DataTable().draw(false);
                }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error changing data');
                }
            });

        });
    }
    
    $(document).ready(function () {
        var purchase_order = $('#purchase_order').val() 
        if(purchase_order !=''){
            $('#purchase_order_id').val(parseInt(purchase_order));
            table.ajax.reload();
        } 
        $('#reloadTableButton').click(function(){
	        table.ajax.reload();
        });
    });
    
   


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>