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
                                       CMS Users
                               @endslot

                               @slot('slot1')
                                       Home
                               @endslot

                               @slot('current')
                                       CMS Users
                               @endslot
                               You are not allowed to access this resource!
                               @endcomponent              <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
                        <div class="card-title">
                            <!-- Add menu Button -->
                            <a href="{{url('/admin/cmsusers/create')}}" class="btn btn-rounded btn-primary"><i
                                        class="zmdi zmdi-plus-circle-o"></i> Add New CMS User </a>
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
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Role</th>

                                    <th>Actions</th>
                                </tr>
                                </thead>


                            </table>

<input value ="{{$user_id}}" type="hidden" id="user" name="auth_user">

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
        <?php $deleteurl = url('/admin/cmsusers/delete'); ?>
        <?php $editurl = url('/admin/cmsusers');?>
        <?php $manageurl = url('admin/cmsusers/changepassword');?>
        var auth_user = document.getElementById('user').value;
        var current_user_id=0;
    var table = $('#items_datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('adminsList') !!}',
            columns: [

                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'role', name: 'role'},
                
                {
                        data: 'id', searchable: false, render: function (data, data2, type, row) {
                        if(data == auth_user){
                                current_user_id=data;
                            }
                            var edit = "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-primary'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit' ></i></a>&nbsp;" + "<button data-toggle='modal' data-target='#myModal' id='delete"+data+"'  onclick='openModal(" + data + ")' type='button' class='btn btn-danger'  title='Delete'><i class='fa fa-trash'></i></button>&nbsp";        
                          var manage = "<a href='{{$manageurl}}" + '/' + data  + "' class='btn btn-primary'><i class='fa fa-cogs ' data-toggle='tooltip' data-placement='top' title='Change Password' ></i></a>&nbsp;";
                            return edit + manage;

                        }
                 }
                


            ]
        });



        setTimeout(function(){
       if(current_user_id == auth_user){
             $('#delete'+ current_user_id).attr('disabled', 'disabled');
            }else{
            }}, 500);
           



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
                url: '{{ $deleteurl }}' + '/' + id,
                type: "GET",

                success: function (data) {
                    if (data == 'success') {
                        $('#myModal').modal('hide');
                           setTimeout(function () {// wait for 5 secs(2)
                                location.reload(); // then reload the page.(3)
                        }, 200);
                    } else {
                        alert("Can't Delete Your Own Account!");
                        $('#myModal').modal('hide');
                    }
                }
            });

            return false;

        }



</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>