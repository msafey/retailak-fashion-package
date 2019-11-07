<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')

    <link rel="alternate" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap.min.css">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">

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
                    <div class="col-sm-10 ">
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

                        <a href="{{ url('admin/slide/add')}}" class="btn btn-primary" style="margin-bottom: 10px; margin-left:2px;"><i class="fa fa-plus"></i>Add New Slide</a>

                        <div class="card card-block"  >
                             <table class="table table-striped">
                                <thead>

                                    <td>Display Name</td>
                                    <td>Actions</td>
                                </thead>
                                <tbody>
                                    @if($slides->count() > 0)
                                        @foreach($slides as $slide)
                                        <tr id="Slide{{ $slide->id}}">  <td> Slide {{ $slide->id }}</td>  <td>

                                            <a class="btn btn-warning" href="{{ url('admin/slide/edit')}}/{{$slide->id}}"> <i class="fa fa-edit"> </i> </a>
                                            <button data-toggle='modal' data-target='#myModal' onclick='openModal("{{$slide->id}}")' type='button' class='btn btn-danger' title='Delete Item'><i class='fa fa-trash'></i></button>
                                             </td> </tr>
                                        @endforeach
                                    @else
                                        <tr><td style="text-align: center" colspan="4" > No Data Available </td></tr>
                                    @endif

                                </tbody>
                            </table>
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
         <?php $deleteurl = url('admin/slide/delete'); ?>
    function openModal(id) {
            $('#delItem').click(function (event) {
                event.preventDefault();
                delete_record(id);
            });
        }
        function delete_record(id) {
            // ajax delete data to database
            $.ajax({
                url: '{{ $deleteurl }}' + '/' + id,
                type: "GET",
                success: function (data) {
                   $('#myModal').modal('hide');
                        if (data !== 'success') {
                            $('#Slide'+data).hide();
                        }
                }
            });
            return false;
        }

    </script>

    <!-- JAVASCRIPT AREA -->
@include('layouts.admin.javascript')
<!-- JAVASCRIPT AREA -->
</body>

</html>
