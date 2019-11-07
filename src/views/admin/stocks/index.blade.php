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
                        Stocks
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Stocks
                @endslot
                You are not allowed to access this resource!
                @endcomponent              <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
	                  <div class="card-title">

                <!-- Add Company Button -->
                  <a href="{{url('/admin/stocks/create?product='.$product)}}" class="btn btn-rounded btn-primary"><i
                            class="zmdi zmdi-plus-circle-o"></i> Add New Stock</a>


                <a href="{{url('/admin/move_stock?product='.$product)}}" class="btn btn-rounded btn-primary"><i

                          class="zmdi zmdi-plus-circle-o"></i>Move Stocks</a>


                <!-- Add Company Button End-->

            </div>
                       
                        <div class="card-text">
                           

                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Stock Qty</th>
                                    <th>Purchase Order Reference Id</th>
                                    <th>Status</th>
                                    <th>Warehouse Moved From</th>
                                    <th>Destination Warehouse</th>
                                    <!-- <th>Actions</th> -->
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

    <?php $listUrl = url('admin/stock-itemsList').'/'.$product;?>


     
 // <?php $editurl = url('/admin/warehouses/');?>
 // <?php $changestatus = url('/admin/warehouses/status'); ?>

 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{$listUrl}}',
        columns: [
        {data:'stock_qty',name:'stock_qty'},
        {data:'purchase_order_reference_id',name:'purchase_order_reference_id'},
        {data:'status',name:'status'},
        {data:'moved_from_warehouse',name:'moved_from_warehouse'},
        {data:'destination_warehouse',name:'destination_warehouse'},
           // { data: 'id', render: function (data, data2, type, row) {
                
                   
           //          if (type.status == 1) {
           //              return  "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Product' ></i></a>&nbsp;"
           //              " <a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>De-Activate &nbsp;"
           //          }
           //          else
           //          {
           //              return  "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit  Product' ></i></a>&nbsp; <a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>Activate &nbsp;"

           //          }
              
           //          }
            // }
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