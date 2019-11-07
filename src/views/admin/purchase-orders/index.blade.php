    <!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
          <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

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
                        Purchase Orders
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Purchase Orders
                @endslot
                You are not allowed to access this resource!
                @endcomponent             <!--End Bread Crumb And Title Section -->
                <div class="row">
                    <div class="modal fade bs-example-modal-sm" id="deleteModal" tabindex="-1" role="dialog"
                         aria-labelledby="mySmallModalLabel">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    Confirmation
                                </div>
                                <div class="modal-body">
                                    Are you Sure That You Want To Delete Purchase Order?
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem" title="Hapus"><i
                                                class="glyphicon glyphicon-trash"></i> Delete Order </a>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card card-block">
	                  <div class="card-title">
<div class="row">
    
                <!-- Add Company Button -->
                <div class="col-sm-3">
                    @if(isset($_GET['product_id']))
                    <?php $product_id = $_GET['product_id']; ?>
                    @else
                    <?php $product_id = 0; ?> 
                    @endif
                  <a href="{{url('/admin/purchase-orders/create?product='.$product_id)}}" class="btn btn-rounded btn-primary"><i  class="zmdi zmdi-plus-circle-o"></i>Purchase New Order</a>


                    
                </div>

                <div class="col-sm-3">
                  <label for="crewdateofjoining">Products: <span style="color:red;"></span></label>

                    <select id="product_id"  class="form-control select2" >
                        <option disabled selected value="0" data-foo="Select Product">Select Product</option>
                        <!-- <option value="-1">All Categories</option> -->

                        <?php foreach ($products as $product) { ?>
                            <option value="{{$product->id}}" @if($product_id == $product->id)selected @endif data-foo="{{$product->name}}">{{$product->name_en}}</option>
                        <?php } ?>
                    </select>
                </div>

                  <div class=" col-sm-3">
                      <label for="crewdateofjoining">Creation Date: <span style="color:red;"></span></label>
                  <input   class="form-control creation_date" type="text" name="creation_date" placeholder="mm/dd/yyyy" id="creation_date" />
              </div>


                </div>


                <!-- Add Company Button End-->

            </div>
                       
                        <div class="card-text">
                           

                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Company</th>
                                    <th>Required By Date</th>
                                    <th>Status</th>
                                    <th>Grand Total Amount</th>
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
<script src="{{url('/public/admin/plugins/moment/')}}/moment.js"></script>

<script src="{{url('public/admin/plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/jszip.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/pdfmake.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.colVis.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="{{url('public/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>

    <script src="{{url('components/components/select2/dist/js/select2.js')}}"></script>

<script>

    <?php $listUrl = url('admin/purchaseOrdersList');?>

 $('#product_id').select2({
      matcher: matchCustom,
            templateResult: formatCustom
    });
  $('#creation_date').datepicker({
        autoclose: true,
        todayHighlight: true
    });


     
 <?php $editurl = url('/admin/purchase-orders/');?>

 <?php $changestatus = url('/admin/purchase-orders/status'); ?>

 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:'{{$listUrl}}',
            type:"GET",
            data: function(d){
            d.product_id=document.getElementById('product_id').value,
            d.creation_date= document.getElementById("creation_date").value
            }
        },
        columns: [
        {data:'id',name:'id'},
        {data:'company',name:'company'},
        {data:'required_by_date',name:'required_by_date'},        
        {data:'status',name:'status'},
        {data:'grand_total_amount',name:'grand_total_amount'},
           { data: 'id', render: function (data, data2, type, row) {
                var receipts = "<a href='{{$editurl}}" + '/' + data+'/purchase-receipts'+ "' class='btn btn-primary' title='Purchase Receipts'>PR("+type.count_purchase_receipts+")</a>&nbsp;";
                var edit =   "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Purchased Order' ></i></a>&nbsp;"
                var remove = "<button data-toggle='modal' data-target='#deleteModal' onclick='delete_record(" + data + ")' type='button' class='btn btn-danger' title='Delete Purchase Order'><i class='fa fa-trash'></i></button>";
                 var details = "<a href='{{$editurl}}" + '/' + data + '/details' + "' class='btn btn-primary'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";

                return details + edit + receipts  + remove;
                   
                    // if (type.status == 1) {
                        // edit  "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Product' ></i></a>&nbsp;"
                         // <a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>De-Activate &nbsp;"
                    // }
                    // else
                    // {
                    //     return  "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit  Product' ></i></a>&nbsp; <a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>Activate &nbsp;"

                    // }
              
                    }
            }]
        });


    
    $(document).ready(function () {
        
        
        $('#reloadTableButton').click(function(){
	        table.ajax.reload();
        });
    });
    
   function delete_record(id) {
       $('#delItem').one('click', function (e) {
           e.preventDefault();
           $.ajax({
               url: "{{url('admin/purchase-orders/delete')}}/" + id,
               type: "GET",

               success: function (data) {
                   if (data == 'cancel purchase receipt') {
                       alert('Cancel Purchased Receipts First');
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
   $('select').change(function () {
       product_id= document.getElementById("product_id").value;
       if(product_id == -1 || product_id == 0){
            return false;
       }
        
      table.ajax.reload();

     });

   $('#creation_date').on('change', function(e){
      var creation_date= document.getElementById("creation_date").value;
       if(creation_date == ""){
            return false;
       }else{
        table.ajax.reload();

       }
     });




  
</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>