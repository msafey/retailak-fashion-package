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
                        Payment Methods
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Payment Methods
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
	                  <div class="card-title">

                <!-- Add Company Button -->
                <a href="{{url('/admin/payment-methods/create')}}" class="btn btn-rounded btn-primary"><i
                            class="zmdi zmdi-plus-circle-o"></i> Add New Payment Method</a>

            </div>



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
                       
                        <div class="card-text">
                           

                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Name</th>
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
     
 <?php $editurl = url('/admin/payment-methods/');?>
 <?php $deleteurl = url('/admin/payment-methods/delete'); ?>

 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('paymentList') !!}',
        columns: [

        {
            data: 'name',name:'name'
        },
          {
                data: 'id', render: function (data) 
                {
                    return "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-primary'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Payment Method' ></i></a>&nbsp;<button data-toggle='modal' data-target='#myModal' onclick='openModal(" + data + ")' type='button' class='btn btn-danger' title='Delete Payment Method'><i class='fa fa-trash'></i></button>";

            		}
            }]
        });


    
    $(document).ready(function () {
        
        
        $('#reloadTableButton').click(function(){
	        table.ajax.reload();
        });
    });

    $(document).ready(function () {
        $("[data-toggle='tooltip']").tooltip();
    });

   
    function openModal(id) {
        $('#delItem').on('click',function (event) {
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
                    if (data == 'true') {
                        $('#myModal').modal('hide');
                    $('#items_datatable').DataTable().draw(false)
                    } else {
                        alert('Error deleting data');
                    }
                }

            });

            return false;

        }








//     function delete_record(id) {
// // console.log(id);
//         // ajax delete data to database
//         $.ajax({
//             url: "{{$deleteurl}}/" + id,
//             type: "GET",

//             success: function (data) {
//                 //if success reload ajax table
//                 $('#myModal').modal('hide');
//             //         reload_t
//                 if(data =='true'){
//                       // $('#myModal').modal('hide');
//                             setTimeout(function () {// wait for 5 secs(2)
//                                 location.reload(); // then reload the page.(3)
//                         }, 100);
//                     // $('#items_datatable').DataTable().draw(false)

//                 }
//                },       
//             error: function (jqXHR, textStatus, errorThrown) {
//             }
//         });
        // $('#myModal').modal('toggle');

    //            setTimeout(function () {
    //                table.ajax.reload();
    //            }, 300);


            // }
    
   


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>