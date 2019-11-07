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
                    Items Of {{$warehouse->name_en}}-{{$warehouse->name}} Warehouse
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Warehouse Items
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
	                  <div class="card-title">

                <!-- Add Company Button -->

                <!-- Add Company Button End-->

            </div>
                       
                        <div class="card-text">
                           

                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Product Id</th>
                                    <th>Product SKU</th>
                                    <th>Product Name</th>
                                    <th>Stock Quantity</th>
                                    <th>Details</th>
                                </tr>
                                </thead>


                            </table>
<input type="text" hidden="" value="{{$id}}" name="id" id="warehouse_id">

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
     
 <?php $editurl = url('/admin/warehouses/');?>
 <?php $changestatus = url('/admin/warehouses/status'); ?>
 <?php $details = url('/admin/warehouses');?>
 <?php $product_details = url('/admin/product-details/');?>
 <?php $purchase_order = url('admin/purchase-orders');?>
 <?php $sales_order = url('admin/sales-orders');?>

 var warehouse_id=0;

 
 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        order: [ [0, 'desc'] ],
        ajax:{
        url:'{!! route('detailsList') !!}',
        type:"GET",
        data: function(d){
            d.warehouse_id=document.getElementById('warehouse_id').value
        }
        },
        columns: [

       
        {data:'product_id',name:'product_id'},
            {data:'item_code',name:'item_code'},
                   { data: 'product_id', render: function (data, data2, type, row) {
                    var product = "<a href='{{$product_details}}" + '/' + data  + "'>"+type.product_name+"</a>&nbsp;";
                    return product;

        }},
        {data:'projected_qty',name:'projected_qty'},

        {
                           data: 'product_id', render: function (data, data2, type, row) {


                            var product_po = "<a href='{{$purchase_order}}" + '?product_id=' + data  + "' class='btn btn-primary' target='_blank'>PO-("+type.count_of_po+")</a>&nbsp;";

                            var product_so = "<a href='{{$sales_order}}" + '?product_id=' + data  + "' class='btn btn-primary' target='_blank'>SO-("+type.count_of_so+")</a>&nbsp;";
                            return product_po + product_so;
	       }
       }
]
        });


    
    $(document).ready(function () {
        
        
        $('#reloadTableButton').click(function(){
	        table.ajax.reload();
        });
    });
    
   


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>