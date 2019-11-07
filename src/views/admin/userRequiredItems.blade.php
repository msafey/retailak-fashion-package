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
<!-- @include('layouts.admin.scriptname_desc') -->

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
                                       Products Notifications
                               @endslot

                               @slot('slot1')
                                       Home
                               @endslot

                               @slot('current')
                                       Products Notifications
                               @endslot
                               You are not allowed to access this resource!
               @endcomponent   
                           <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block" style="width: 100%;">
                        <div class="card-title">



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
                                    <tr >
                                        <th>User</th>
                                        <th>Product ID</th>
                                        <!-- <th>Itemcode</th> -->
                                        <th>Product Name</th>
                                        <!-- <th>Product Name</th> -->
                                        <th>warehouse</th>
                                        <th>NotifyMe</th>
                                        <th>Category</th>
                                        <!-- <th>Category (EN)</th> -->
                                        <th>Required Time</th>
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
<script src="{{url('public/admin/plugins/datatables/dataTables.fixedHeader.min.js')}}"></script>
<!-- https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js -->

<script>


    $(document).ready(function(){
         $('#items_datatable thead tr').clone(true).appendTo( '#items_datatable thead' );
    $('#items_datatable thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100px !important" placeholder="Filter By '+title+'" />' );

        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );

    });

    var table = $('#items_datatable').DataTable({
            processing: true,
            serverSide: true,
            orderCellsTop: true,
            fixedHeader: true,

            ajax: '{!! route('userproductnotifylist') !!}',
            columns: [

                {data: 'user', name: 'user'},
                {data: 'product_id', name: 'product_id'},
                // {data: 'item_code', name: 'product_id'},
                {data: 'name', name: 'name'},
                // {data: 'name_en', name: 'name_en'},
                {data: 'warehouse', name: 'warehouse'},
                {data: 'notification_sent',render: function (status, type, row) {
                    if (status == 1) {
                        return "Active"
                    }
                    else {
                        return "Recieved"
                    }
                }},
                {data: 'category', name: 'category'},
                // {data: 'category_en', name: 'category_en'},
                {data: 'required_time', name: 'required_time'},

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


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>