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
    <style>
        .header-title {
            font-size: 1.1rem;
            text-transform: none;
        }
        .max-width-table{
            max-width: 360px !important;
            overflow: hidden;
            text-overflow: ellipsis
        }
    </style>
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
    <div class="col-sm-12 card card-block">
        <div class="row">


            {{------------------   Push Notifications -------------}}

            @if(isset($push_notifications))
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <h4 class="m-t-0 header-title">
                                <!-- Add crew Button -->
                                <a href="{{url('/admin/notifications/add')}}" class="btn btn-rounded btn-primary">
                                    <i class="zmdi zmdi-plus-circle-o"></i> Send New Notification
                                </a>
                                <a href="{{url('/admin/notifications/users/add')}}" class="btn btn-rounded btn-primary">
                                    <i class="zmdi zmdi-plus-circle-o"></i> Send Notification To Specific User
                                </a>
                                <!-- Add crew Button End-->

                            </h4>
                            <p class="text-muted font-13 m-b-30">
                            </p>

                            <table id="datatable" class="table table-striped table-bordered"
                                   cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Ttile</th>
                                    <th>Message</th>
                                    <th>Device Os</th>
                                    <th>Time</th>
                                    <th>Number Success</th>
                                    <th>Number Failure</th>
                                </tr>
                                </thead>


                                <tbody>
                                @foreach($push_notifications as $push_notification)
                                    <tr>
                                        <td>{{$push_notification->push_title}}</td>
                                        <td>
                                            <div class="max-width-table">
                                                {{$push_notification->push_message}}
                                            </div>
                                        </td>

                                        <td>{{$push_notification->push_os}}</td>
                                        <td>{{$push_notification->push_time}}</td>
                                        <td>{{$push_notification->push_success}}</td>
                                        <td>{{$push_notification->push_failure}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            @endif
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

    var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('deliverymanlist') !!}',
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
                    return "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Deliver-Man' ></i></a>&nbsp; <a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>De-Activate &nbsp;"
                }
                else
                {
                    return "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Deliver-Man' ></i></a>&nbsp; <a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>Activate &nbsp;"

                }

            }
            }


        ]
    });


    $(function () {

        table.ajax.reload();

    });
    $(document).ready(function () {
        $("[data-toggle='tooltip']").tooltip();
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
