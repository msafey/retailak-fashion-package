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
                        PromoCodes
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        PromoCodes
                    @endslot
                    You are not allowed to access this resource!
            @endcomponent             <!--End Bread Crumb And Title Section -->
                <div class="row">
                    <div class="card card-block">


                        <div class="card-title">
                            <div class="col-sm-3"><input type="checkbox" name="available" value="" id="available">Available
                            </div>
                            <div class="col-sm-3"><input type="checkbox" name="unavailable" value="" id="unavailable">Unavailable
                            </div>

                            <a href="{{url('/admin/promocode/add')}}" class="btn btn-primary btn-sm"
                               id="reloadTableButton"> Add New Code </a>

                        </div>


                    </div>


                    <div class="card-text">


                        <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Count</th>
                                <th>Status</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Created At</th>
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
<?php $unavailable = 0?>

<script>

        <?php $editurl = url('/admin/promocode/');?>
    var available = document.getElementById("available").value;
    var unavailable = document.getElementById("unavailable").value;
    var ckbox = $('#available');
    var ckbox1 = $('#unavailable');

    var table = $('#items_datatable').DataTable({

        processing: true,
        serverSide: true,
        ajax: {
            url: '{!! route("promocodes_list") !!}',
            type: "GET",
            data: function (d) {
                d.available = document.getElementById('available').value,
                    d.unavailable = document.getElementById('unavailable').value;

            }
        },
        columns: [
            {data: 'code', name: 'code'},
            {data: 'userscount', name: 'userscount'},
            {data: 'active', name: 'active'},
            {data: 'sfrom', name: 'sfrom'},
            {data: 'sto', name: 'sto'},
            {data: 'created_at', name: 'created_at'},
            {
                data: 'id', render: function (data,data2, type, row) {

                    return "<a class='btn btn-info' href='{{url('/admin/promocode')}}/" + data + "/edit'> " +
                        "<i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='' id='edit' ></i> </a>"
                }
            }
        ]

    });


    $('input').change(function () {

        if (ckbox.is(':checked')) {
            $('#available').val(1);
        } else {
            $('#available').val(0);
        }
        table.ajax.reload();

    });


    $('input').on('click', function () {
        if (ckbox1.is(':checked')) {
            $('#unavailable').val(1);
        } else {
            $('#unavailable').val(0);
        }
        table.ajax.reload();

    });


    $(document).ready(function () {


        $('#reloadTableButton').click(function () {
            table.ajax.reload();
        });
    });


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
