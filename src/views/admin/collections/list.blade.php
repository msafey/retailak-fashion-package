<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')

    <link rel="alternate" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap.min.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">
        <style type="text/css" media="screen">
             td{
                padding: 6px;

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

    <!-- Start right Content here -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <!-- Bread Crumb And Title Section -->
            @include('layouts.admin.breadcrumb')
            <!--End Bread Crumb And Title Section -->

                <div class="row">
                    <div class="col-sm-12 ">
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
                            <div class="alert alert-success">{{Session::get('success')}}</div>
                        @endif

                        {{-- rr --}}



                        <div class="card card-block"  id="itemsBlock" >

                        <div class="row">
                            <div class="col-sm-12"><a class="btn btn-success" href="{{ url('admin/collection/add') }}">Add New Collection <i class="zmdi zmdi-plus"></i></a> </div>
                        </div>


                            <table id="mytable" class="display" cellspacing="0" border="0" width="100%">
                                <thead><tr style=""><th>Name</th><th>Actions</th></tr></thead>
                                <tbody id="sortable" class="sortable">
                                    @if($collections->count() > 0)
                                    @foreach($collections as $collection)
                                        <tr id="{{ $collection->id }}"><td>{{ $collection->name }}</td><td><a class="btn btn-sm btn-primary " href="{{ url('admin/collection/manage/') }}/{{ $collection->id }}"><i class="zmdi zmdi-settings"></i> Manage</a> <button class="btn btn-sm btn-danger colDelete"  type="button"><i class="zmdi zmdi-delete"></i> Delete</button></td></tr>
                                    @endforeach
                                    @else
                                    <tr colspan="2"><td> No Records</td></tr>
                                    @endif
                                </tbody>
                            </table>

                        </div>




                    </div>

                </div> <!-- container -->
            </div> <!-- content -->
        </div>
        <!-- End content-page -->
        <!-- Footer 8Area -->
    @include('layouts.admin.footer')
    <!-- End Footer Area-->
    </div>
    <!-- END wrapper -->
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" type="text/javascript" charset="utf-8" async defer></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap.min.js" type="text/javascript" charset="utf-8" async defer></script>
    <script>
        var resizefunc = [];
        $(document).ready(function(){




            // var datatable = $('#mytable').DataTable({"paging":   false ,destroy: true,"sorting": false, "searching": false});
            // var datatableBrand = $('#sortByBrand').DataTable({"paging":   false ,destroy: true,"sorting": false,"searching": false});






            $("#sortable").sortable({
                stop: function ()
                {
                    $.map($(this).find('tr'), function (el){
                        var rowID = el.id; var rowIndex = $(el).index();

                        $.ajax({
                            url: '{{URL::to("/admin/collection/reorder")}}',
                            type: 'GET', dataType: 'json',
                            data: {rowID: rowID, rowIndex: rowIndex},
                            success: function (data){
                                //$.each(data , function(item){ $('#sortable tr').find(item.id).text(item.sort_no) ;
                                //datatable.ajax.reload();
                                //var datatable = $('#mytable').DataTable({"paging":   false ,destroy: true });
                                //});
                            },
                            error: function (data) { alert('Error Updating data'); }
                        });
                    });
                }
            });



        });
        $(document).on('click','.colDelete',function(){
            var txt;
var r = confirm("Are You Sure that you want to delete this item ? ");
if (r == true) {
      var CollectionId = $(this).closest('tr').attr('id');
             $.ajax({
                            url: '{{URL::to("/admin/collection/delete/")}}'+'/'+CollectionId,
                            type: 'GET', dataType: 'json',
                            success: function (data){
                                $("#"+CollectionId).remove();
                            },
                            error: function (data) { alert('Error Deleting data'); }
                        });
} else {

}

        });
    </script>

    <!-- JAVASCRIPT AREA -->
@include('layouts.admin.javascript')
<!-- JAVASCRIPT AREA -->
</body>

</html>
