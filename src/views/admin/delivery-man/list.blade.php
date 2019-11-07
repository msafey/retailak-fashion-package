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


<div class="modal fade bs-example-modal-sm" id="statusModal" tabindex="-1" role="dialog"
     aria-labelledby="mySmallModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                    Confirmation
            </div>
            <div class="modal-body">
                    Are you Sure That You Want To Change Status Of This Courier?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">No Cancel</button>
                <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="statusItem" title="Hapus"><i
                            class="glyphicon glyphicon-trash"></i>Change Status</a>

            </div>
        </div>

    </div>
</div>



    <div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Confirmation
                </div>
                <div class="modal-body">
                    Are you Sure That You Want To Change Status Of This Courier?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="changeStatusItem" title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i>Change Status</a>

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
                        Couriers
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Couriers
                @endslot
                You are not allowed to access this resource!
                @endcomponent             <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
                        <div class="card-title">

                            <!-- Add menu Button -->
                            <!-- <button disabled="disabled" class="btn btn-rounded btn-primary">Add New Delivery Man</button> -->
                            <a href="{{url('/admin/delivery/man/create')}}" class="btn btn-rounded btn-primary"><i
                                        class="zmdi zmdi-plus-circle-o"></i> Add New Courier </a>

                            <a href="{{url('/admin/delivery/cars')}}" class="btn btn-rounded btn-primary"><i

                                        class="zmdi zmdi-plus-circle-o"></i> Manage Courier Cars </a>
            
                            <!-- Add menu Button End-->

                                    <div class="row">   
                                        <div class="col-sm-6">  
                            <label for="" style="margin-right: 50px;">Filters:</label><input type="checkbox" name="inactive" value="" id="inactive" >Show Inactive Couriers
                                        </div>
                            
                                    <!-- Add Company Button End-->

                                </div>


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
                                    <th>Name</th>
                                    <th>Phone</th>
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
        <?php $deleteurl = url('/admin/delivery/man/delete'); ?>
        <?php $changestatus = url('/admin/delivery/man/status'); ?>
        <?php $editurl = url('/admin/delivery/man/');?>
        var inactive=document.getElementById("inactive").value;
        var ckbox1=$('#inactive');

    var table = $('#items_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
            url:'{!! route('deliverymanlist') !!}',
            type:"GET",
            data: function(d){
                d.inactive=document.getElementById('inactive').value;
            }
            },
            columns: [

                {data: 'name', name: 'delivery__men.name'},
                {data: 'mobile', name: 'delivery__men.mobile'},
                {
                    data: 'status', render: function (status, type, row) {
                    if (status == 1) {
                        return "Active"
                    }
                    else {
                        return "Not Active"
                    }
                }
                },

                {
                    data: 'id', render: function (data, data2, type, row) {

                    if (type.status == 1) {
                        return "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-warning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Deliver-Man' ></i></a>&nbsp;<button data-toggle='modal' data-target='#statusModal' onclick='updateStatus(" + data + ", " + 0 + ")' type='button' class='btn btn-danger' title='De-Activate Courier'>De-Activate</button>&nbsp;" ;
                        // return "<button class='btn btn-warning' disabled>Edit</button>&nbsp;" +  "<a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>De-Activate &nbsp;"
                    }
                    else
                    {
                        return "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Deliver-Man' ></i></a>&nbsp;<button data-toggle='modal' data-target='#statusModal' onclick='updateStatus(" + data + ", " + 1 + ")'  type='button' class='btn btn-danger' title='Activate Courier'>Activate</button>&nbsp;"
                         // return "<button class='btn btn-warning' disabled>Edit</button>&nbsp;" +"<a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>Activate &nbsp;"

                    }

                }
                }


            ]
        });


    $('input').change(function () {
          if (ckbox1.is(':checked')) {
              $('#inactive').val(1);
              } else {
              $('#inactive').val(0);
          }
       table.ajax.reload();

      });

    $(function () {

        table.ajax.reload();

    });
    $(document).ready(function () {
        $("[data-toggle='tooltip']").tooltip();
    });

    // function openModal(id) {
    //     $('#changeStatusItem').click(function () {
    //         change_status(id);
    //     });
    // }


    function updateStatus(id, status) {
        $('#statusItem').one('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{$changestatus}}/" + id + '/'+status,
                type: "GET",

                success: function (data) {
                    if (data == 'success') {
                        $('#statusModal').modal('hide');
                        $('#items_datatable').DataTable().draw(false)
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error Updating data');
                }
            });
        });
    }



    function change_status(id) {

        // ajax delete data to database
        $.ajax({
            url: "{{$changestatus}}/" + id,
            type: "GET",

            success: function (data) {
                    console.log(data);
                $('#myModal').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error Change Status');
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