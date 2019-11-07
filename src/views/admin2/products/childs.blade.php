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


<div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog"
                 aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            Confirmation
                        </div>
                        <div class="modal-body">
                            Are you Sure That You Want To Change This Item  Status?
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                            <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem" title="Hapus"><i
                                        class="glyphicon glyphicon-trash"></i> Change Status </a>

                        </div>
                    </div>

                </div>
            </div>
<!-- Left Sidebar End -->
   

    <!-- Start right Content here -->
    <div class="content-page">
        <!-- Start content -->

     

        <div class="content">
            <div class="container">
                <!-- Bread Crumb And Title Section -->
@component('layouts.admin.breadcrumb')
                @slot('title')
                        Products
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Products
                @endslot
                You are not allowed to access this resource!
                @endcomponent            <!--End Bread Crumb And Title Section -->
                <div class="row">

                  @if(session()->has('message'))
                      <div class="alert alert-success">
                          {{ session()->get('message') }}
                      </div>
                  @endif

                  
                  <div class="row">
                      @if (count($errors) > 0)
                          <div class="alert alert-danger">
                              <ul>
                                  @foreach ($errors->all() as $error)
                                      <li>{{ $error }}</li>
                                  @endforeach
                              </ul>
                          </div>
                      @endif
                  </div>


                    <div class="card card-block">
                    <div class="card-title">

                <!-- Add Company Button -->
                <a href="{{url('/admin/products/'.$parent->id.'/standard-rate')}}" class="btn btn-rounded btn-primary"><i
                            class="zmdi zmdi-plus-circle-o"></i> Manage Parent Price</a>
                        <div class="card-text">                           
                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Selling Price</th>
                                    <th>Image</th>
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
     
 <?php $editurl = url('/admin/products/');?>
 <?php $changestatus = url('/admin/product/status'); ?>
 <?php $order = url('/admin/products');?>
 <?php $manageUrl = url('/admin/products/'); ?>
 <?php $details = url('/admin/products/');?>


 <?php $listUrl = url('admin/ChildsList').'/'.$parent->id;?>

 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{$listUrl}}',
        columns: [
        {
            data: 'name_en', render: function (name1, name2, type, row) {    
            if(name1.length > 40){
               name1 = name1.substr(0,38)+'...</span>';
            }        
            if(type.name.length >40){
              type.name = type.name.substr( 0, 38 )+'...</span>';
            }      
            return name1 + '   <b>EN</b>' + ' <br> ' + type.name + '   <b>AR</b>'

        }},


            {data: 'selling_price', name: 'selling_price'},
            {  data: 'image', searchable: false, render: function (image, type, row) {
                        return "<img class='img-data-tables' height='100px;' width='150px' style='object-fit: contain' src='{{url('/public/imgs/products')}}" + '/' + image + "'>"
              }
            },{
                    data: 'id', render: function (data, data2, type, row) {
                     var details = "<a href='{{$details}}" + '/' + data  + '/standard-rate'+"' class='btn btn-primary'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";        
                     return details;  
              }
            }]
        });



 $(function () {

     table.ajax.reload();

 });
 $(document).ready(function () {
     $("[data-toggle='tooltip']").tooltip();
 });



</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>