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

    <!-- Start right Content here -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <!-- Bread Crumb And Title Section -->
@component('layouts.admin.breadcrumb')
                @slot('title')
                        RunSheets
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        RunSheets
                @endslot
                You are not allowed to access this resource!
                @endcomponent                    <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
                        <div class="card-title">
                            <div class="row">
                                <a href="{{url('/admin/runsheet/create')}}" class="btn btn-rounded btn-primary"><i
                                            class="zmdi zmdi-plus-circle-o"></i> Add New RunSheet </a>

                                <a href="{{url('/admin/runsheet/debreifing/actions')}}" class="btn btn-rounded btn-warning"><i
                                            class=""></i> Debriefing </a>            
                            </div>
                            <br>
                            <div class="row">
                                <label for="" style="margin-left: 10px;"><b><u>Filters :</u></b></label>

                            </div>
                            <div class="row">

                               <div class="col-sm-4">
                                    <label for=""> Select Courier</label>
                                   <select id="courier_id" name="courier_id" class="form-control" >
                                       <option disabled selected value="-1">Select Courier</option>
                                       <?php foreach ($couriers as $courier) { ?>
                                           <option value="{{$courier->id}}">{{$courier->name}}</option>
                                       <?php } ?>
                                   </select>
                               </div>

                               <div class="col-sm-4">
                                <label for=""> Select Branch</label>
                               <select id="branch_id" name="branch_id" class="form-control" >
                                   <option disabled selected value="-1">Select Branch</option>
                                   <?php foreach ($warehouses as $branch) { ?>
                                       <option value="{{$branch->id}}">{{$branch->name}}</option>
                                   <?php } ?>
                               </select>

                              
                            </div>
                            <div class="row"> 
                                <div class="col-sm-4"> 
                                   <input id="not_completed" name="not_completed" type="checkbox" style="float: left; margin-top: 5px;>">
                                   <div style="margin-left: 25px;">
                                        Show Not Completed Runsheets
                                   </div>
                               </div>  
                            </div>
                        </div>
                            <!-- Add menu Button -->
                           
                            <!-- Add menu Button End-->

                        </div>
                       
                        <div class="card-text">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if( Session::has('success') )
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                                    </button>{{Session::get('success')}}</div>
                        @endif
                        <!-- Table Start-->


                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Courier Name</th>
                                    <th> Orders</th>
                                    <th> Pending Orders</th>
                                    <th> Date</th>

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
    var courier_id = 0;
    var not_completed=0;
    var branch_id = 0;



        <?php $deleteurl = url('/admin/runsheet/delete'); ?>
        <?php $editurl = url('/admin/runsheet/');?>
        <?php $details = url('/admin/runsheet/details/');?>

    var table = $('#items_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url:'{!! route('delivery_orders_list') !!}',
                type:"GET",
                 data: function(d){
                d.courier_id=document.getElementById('courier_id').value;
                d.not_completed=document.getElementById('not_completed').value;
                d.branch_id=document.getElementById('branch_id').value;
                // console.log(d.item_group);
                // d.inactive=document.getElementById('inactive').value;
            }
                },          
            columns: [
                {data:'id',name:'delivery__orders.id'},
                {data: 'name', name: 'delivery__orders.name'},
                {data: 'count_of_orders', name: 'count_of_orders'},
                {data: 'count_of_pending', name: 'count_of_pending'},
                {data: 'date', name: 'delivery__orders.date'},


                {
                    data: 'id', render: function (data, data2, type, row) {

                    if (type.orders_id == "a:0:{}") {
                        return "<a href='{{$details}}" + '/' + data + "' target='_blank' class='btn btn-p" + "rimary'><i class='fa fa-list' data-toggle='tooltip' data-placement='top' title='Manage Deliver-Order' ></i></a>  <a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Deliver-Order' ></i></a> &nbsp;<button data-toggle='modal' data-target='#myModal' onclick='openModal(" + data + ")' type='button' class='btn btn-danger' title='Delete Delivery-Order'><i class='fa fa-trash'></i></button>"
                    } else {
                        return "<a href='{{$details}}" + '/' + data + "' target='_blank' class='btn btn-p" + "rimary'><i class='fa fa-list' data-toggle='tooltip' data-placement='top' title='Manage Deliver-Order' ></i></a>  <a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Deliver-Order' ></i></a>"
                    }
                }
                }


            ]
        });


   
    $('select').change(function () {
        courier_id= document.getElementById("courier_id").value;
        branch_id= document.getElementById("branch_id").value;
       
       table.ajax.reload();

      });

    var ckbox1=$('#not_completed');
  $('input').change(function () {
        if (ckbox1.is(':checked')) {
            $('#not_completed').val(1);
            } else {
            $('#not_completed').val(0);
        }
     table.ajax.reload();

    });

    function openModal(id) {
        $('#delItem').click(function () {
            delete_record(id);
        });
    }

    function delete_record(id) {

        // ajax delete data to database
        $.ajax({
            url: "{{$deleteurl}}/" + id,
            type: "GET",

            success: function (data) {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });

        $('#myModal').modal('toggle');

        setTimeout(function () {
            table.ajax.reload();
        }, 300);


    }
</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>