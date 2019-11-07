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
                        Companies
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Companies
                @endslot
                You are not allowed to access this resource!
                @endcomponent  
            <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
	                  <div class="card-title">

                <!-- Add Company Button -->
                <a href="{{url('/admin/companies/create')}}" class="btn btn-rounded btn-primary"><i
                            class="zmdi zmdi-plus-circle-o"></i> Add New Company</a>

                <!-- Add Company Button End-->

            </div>
                       
                        <div class="card-text">
                           

                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Title</th>
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
     
 <?php $editurl = url('/admin/companies/');?>
 <?php $changestatus = url('/admin/companies/status'); ?>

 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('companiesList') !!}',
        columns: [

        {
            data: 'name_en', render: function (name1, name2, type, row) {
            return name1 + ' <br> ' + type.name
        }},
            // {data: 'parent_item_group', render : function(data){
	           //  var res = data.split("###");
	           //  return res[res.length == 3 ? 1 :  0];
            // }},
           { data: 'id', render: function (data, data2, type, row) {
                
                   
                    if (type.status == 1) {
                        return  "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Company' ></i></a>&nbsp; <a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>De-Activate &nbsp;"
                    }
                    else
                    {
                        return  "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit  Company' ></i></a>&nbsp; <a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>Activate &nbsp;"

                    }
              
                    }
            }]
        });


    
    $(document).ready(function () {
        
        
        $('#reloadTableButton').click(function(){
	        table.ajax.reload();
        });
    });
    
   


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>