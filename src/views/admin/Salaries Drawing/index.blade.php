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

<!-- Start right Content here -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                @include('layouts.admin.flashMessage')
                {{--Showing Toast Message--}}
                <div id="SuccessDeleteSalary" class="bg-success text-white"
                     style="position: relative; width: 30%; float: right; display: none;">
                    <div class="Customtoast" style="padding: 10px;">
                        Record Deleted Successfully &nbsp; &nbsp; <i class="fas fa-check-double"></i>
                    </div>
                </div>
                {{--End of Showing Toast Messages--}}
                <div class="row">
                    <a href="Salaries/add" class="col-lg-4 col-md-6 col-sm-12 btn btn-primary"> <i
                                class="fas fa-plus-circle"></i> &nbsp; Add New Drawing / Salary </a>
                </div>
                <div class="row">
                    <input
                            type="date"
                            id="dateFilter"
                            class="date-range-filter"
                            placeholder="Date: yyyy-mm-dd">
                </div>
                <table class="table table-bordered " style="background-color: #fff;" id="Drawings-table">
                    <thead>
                    <tr>
                        <th>Cost</th>
                        <th>Made By</th>
                        <th>Money To Person</th>
                        <th>Drwaing Type</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>


            <!-- Delete Modal -->
            <div class="modal fade" id="DeleteDrawing" tabindex="-1" role="dialog" aria-labelledby="DeleteDrawing"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to delete it</h5>
                            <input type="hidden" value="" id="RemoveDrawing">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger" onclick="DeleteDrawing()">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
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
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script>
    $(document).ready(function () {

        var FilterDate = 'no';

        $('#dateFilter').change(function (e) {
            FilterDate = $('#dateFilter').val();
            console.log(FilterDate);
            table.ajax.reload();
        });

        table = $('#Drawings-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{!! route('allDrawings') !!}',
                type: "GET",
                data: function (d) {
                    d.filter = FilterDate;
                }
            },
            columns: [
                {data: 'cost', name: 'cost'},
                {data: 'admin_name', name: 'admin_name'},
                {data: 'person', name: 'person'},
                {data: 'type', name: 'type'},
                {data: 'date', name: 'date'},
                {
                    data: 'id', render: function (data) {

                         edit = '<a href="Salaries/' + data + '/edit" class="btn btn-info"> <i class="fas fa-marker"></i> </a>';
                         remove = '<a  class="btn btn-danger"  data-toggle="modal" data-target="#DeleteDrawing" onclick="openModal(' + data + ')" > <i class="far fa-trash-alt"></i> </a>'
                        return edit + '&nbsp;' + remove;
                    }
                },
            ]
        });

    });

    function openModal(id) {
        $('#RemoveDrawing').val(id);
    }

    function DeleteDrawing() {
        var id = $('#RemoveDrawing').val();

        // ajax delete data to database
        $.ajax({
            url: '{{ route("deleteDrawing") }}',
            type: "post",
            data: {
                "_token": "{{ csrf_token()  }}",
                "id": id
            },
            success: function (response) {
                if (response == 'true') {
                    $('#items_datatable').DataTable().draw(false);
                    $('#SuccessDeleteSalary').fadeIn(200);

                    $('#Drawings-table').DataTable().ajax.reload(null, false);

                    setTimeout(function () {
                        $('#SuccessDeleteSalary').fadeOut('fast');
                    }, 2000); // <-- time in milliseconds
                } else {
                    alert('Drawing not deleted !!  try again later');
                }
                $('#DeleteDrawing').modal('toggle');
            },
            error: function (err) {
                console.log(err);
            }
        });

    }

</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
