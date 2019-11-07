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
                        Warehouses
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Warehouses
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
                <div class="row">
                    <div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog"
                         aria-labelledby="mySmallModalLabel">
                        <div class="modal-dialog modal-sm" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    Confirmation
                                </div>
                                <div class="modal-body">
                                    Are you Sure That You Want To Make This Warehouse  as Default Warehouse ?
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="changeDefault" title="Hapus"><i
                                                class="glyphicon glyphicon-trash"></i> Yes </a>

                                    </div>
                            </div>

                        </div>
                    </div>
                    <div class="card card-block">
	                  <div class="card-title">

                <!-- Add Company Button -->
                <a href="{{url('/admin/warehouses/create')}}" class="btn btn-rounded btn-primary"><i
                            class="zmdi zmdi-plus-circle-o"></i> Add New Warehouse</a>

                <!-- Add Company Button End-->

            </div>
                       
                        <div class="card-text">
                           

                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Warehouse Code</th>
                                    <th>District</th>
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
     
 <?php $editurl = url('/admin/warehouses/');?>
 <?php $changestatus = url('/admin/warehouses/status'); ?>
 <?php $details = url('/admin/warehouses');?>

 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('warehousesList') !!}',
        columns: [

        {
            data: 'name_en', render: function (name1, name2, type, row) {
            return name1 + ' <br> ' + type.name
        }},
        {data:'warehouse_code',name:'warehouse_code'},

        {data:'district_id',name:'district_id'},
            // {data: 'parent_item_group', render : function(data){
	           //  var res = data.split("###");
	           //  return res[res.length == 3 ? 1 :  0];
            // }},
           { data: 'id', render: function (data, data2, type, row) {
                   var details = "<a href='{{$details}}" + '/' + data  + '/details'+ "' class='btn btn-primary'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";

                    if (type.status == 1) {
                        var edit = "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Product' ></i></a>&nbsp; <a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>De-Activate</a> &nbsp;"
                    }
                    else
                    {
                          var edit  ="<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit  Product' ></i></a>&nbsp; <a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>Activate</a> &nbsp;"

                    }
                    if(type.default_warehouse == 1){
                         var default_warehouse = "<button  style='margin-left:5px;' type='button' class='btn btn-success' title='Artist Of The Month'>Default Warehouse</button>";
                    }else{
                        var default_warehouse = "<button data-toggle='modal' data-target='#myModal' onclick='openModal("+ data + ",)' type='button' class='btn btn-default' title='change to default warehouse'>Change To Default Warehouse</button>";

                    }
              
                    return details + edit + default_warehouse;
                    }
            }]
        });


    
    $(document).ready(function () {
        
        
        $('#reloadTableButton').click(function(){
	        table.ajax.reload();
        });
    });
    
   function openModal(id) {
       $('#changeDefault').one('click', function (e) {
           e.preventDefault();
           changestatus(id);
       });
   }
   function openModalStat(id) {
       $('#changeDefault').one('click', function (e) {
           e.preventDefault();
           changestatus(id);
       });
   }
   <?php $changestatus = url('/admin/warehouses/default-warehouse'); ?>

   function changestatus(id) {
       // ajax delete data to database
       $.ajax({
           url: "{{$changestatus}}/" + id,
           type: "GET",
           success: function (data) {
               $('#myModal').modal('hide');
              
               if (data == 'true') {
                   table.ajax.reload();
               }
           },
           error: function (jqXHR, textStatus, errorThrown) {
               alert('Error deleting data');
           }
       });
   }


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>