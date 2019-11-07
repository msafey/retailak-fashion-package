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
                        Categories
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Categories
                @endslot
                You are not allowed to access this resource!
                @endcomponent  
            <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
	                  <div class="card-title">

                <!-- Add Company Button -->
                <div class="row">   
                <a href="{{url('/admin/categories/create')}}" class="btn btn-rounded btn-primary"><i
                            class="zmdi zmdi-plus-circle-o"></i> Add New Category</a>

                <a href="{{url('/admin/category/order')}}" class="btn btn-rounded btn-primary"><i
                                        class="zmdi zmdi-plus-circle-o"></i> Sort Category</a>

                </div>
                <div class="row">   
                    <div class="col-sm-6">  
        <label for="" style="margin-right: 50px;">Filters:</label><input type="checkbox" checked name="active" value="1"  id="active">Show Active Categories &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="inactive" value="" id="inactive" >Show Inactive Categories
                    </div>
       
                <!-- Add Company Button End-->

            </div>
                       
                        <div class="card-text">
                           

                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <!-- <th>Sorting Number</th> -->
                                    <th>Name</th>
                                    <th>Parent Category</th>
                                    <th>Thumb</th>
                                    <th>Status</th>
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
     
 <?php $editurl = url('/admin/categories/');?>
 <?php $changestatus = url('/admin/categories/status'); ?>

 <?php $manageUrl = url('/admin/categories/'); ?>
 var active= document.getElementById("active").value;  
 var inactive=document.getElementById("inactive").value;
 var ckbox = $('#active');
 var ckbox1=$('#inactive');

 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
        url:'{!! route('categoriesList') !!}',
        type:"GET",
        data: function(d){
            d.active=document.getElementById('active').value,
            d.inactive=document.getElementById('inactive').value;

        }
        },
        columns: [
        // {data:'sorting_no',name:'sorting_no'},
        {
            data: 'name_en', render: function (name1, name2, type, row) {
            return name1 +'  <b>EN</b>' + ' <br> ' + type.name+'  <b>AR</b>'
        }},
            // {data: 'parent_item_group', render : function(data){
	        //  var res = data.split("###");
	        //  return res[res.length == 3 ? 1 :  0];
            // }},
            {data: 'parent_item_group', name: 'parent_item_group'},
            {
                        data: 'image', searchable: false, render: function (image, type, row) {
                        return "<img class='img-data-tables' height='100px;' width='150px' style='object-fit: contain' src='{{url('/public/imgs/categories')}}" + '/' + image + "'>"
                    }
                    }, 

                    {
                        data: 'status', render: function (status, type, row) {
                        if (status == 1) {
                            return "Active"
                        }
                        else {
                            return "InActive"
                        }
                    }
                    },

                    {
                        data: 'id', render: function (data, data2, type, row) {
                            var edit = "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Deliver-Order' ></i></a> ";

                        if (type.status == 1) {
                            return edit + "&nbsp;<a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>De-Activate &nbsp;" 
                            // return "<button class='btn btn-warning' disabled>Edit</button>&nbsp;" +  "<a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>De-Activate &nbsp;"
                        }
                        else
                        {
                            return edit + "&nbsp;<a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>Activate &nbsp;"
                             // return "<button class='btn btn-warning' disabled>Edit</button>&nbsp;" +"<a href='{{$changestatus}}" + '/' + data + "' class='btn btn-danger'>Activate &nbsp;"

                        }

                    }
                    }
         ]
        });
 $('input').change(function () {


       if (ckbox.is(':checked')) {
           $('#active').val(1);
       } else {
           $('#active').val(0);
       }
    table.ajax.reload();

   });


 $('input').change(function () {
       if (ckbox1.is(':checked')) {
           $('#inactive').val(1);
           } else {
           $('#inactive').val(0);
       }
    table.ajax.reload();

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