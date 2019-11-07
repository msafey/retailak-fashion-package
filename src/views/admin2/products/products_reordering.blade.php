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
                 
                    <div class="card card-block">

	                  <div class="card-title" style="margin-bottom: 100px;">

                <!-- Add Company Button -->
                <div class="col-sm-3">
                    <select id="warehouse" name="warehouse" class="form-control" >
<!--                         <option value="-1">All Warehouses</option>
 -->                        <?php foreach ($warehouses as $warehouse) { ?>
                            <option value="{{$warehouse->id}}" @if(isset($warehouse_id) && $warehouse_id == $warehouse->id)selected @endif>{{$warehouse->name}}</option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-sm-3">
                    <select id="quantity_filter" name="quantity_filter" class="form-control" >
                            <option value="1">(0)</option>
                            <option value="2">(0:10)</option>
                            <option value="3"@if(isset($quantity_filter) && $quantity_filter !=0 )selected @endif>(10:50)</option>
                            <option value="4">(50:100)</option>
                            <option value="5">(100:1000)</option>
                    </select>
                </div>
               

                

            </div>
                       
                        <div class="card-text">
                           

                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Product Code</th>
                                    <th>Projected Qty</th>
                                    <!-- <th>Thumb</th> -->
                                    <!-- <th>Actions</th> -->
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
 <?php $details = url('/admin/product-details/');?>

 var warehouse = 0;
 var quantity_filter = 0;

 // var ckbox = $('#item_group');

 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
          ajax:{
              url:'{!! route('productReorderingList') !!}',
              type:"GET",
              data: function(d){
                  d.warehouse=document.getElementById('warehouse').value;
                  // console.log(d.item_group);
                  d.quantity_filter=document.getElementById('quantity_filter').value;
              }
              },
        columns: [

        // {
        //     data: 'name_en', render: function (name1, name2, type, row) {
        //     return name1 + ' <br> ' + type.name
        // }},
            // {data: 'parent_item_group', render : function(data){
	           //  var res = data.split("###");
	           //  return res[res.length == 3 ? 1 :  0];
            // }},

            {data: 'product_name', name: 'product_name'},
            {data: 'product_code', name: 'product_code'},
            {data: 'projected_qty', name: 'projected_qty'},

    // {
    //                     data: 'image', searchable: false, render: function (image, type, row) {
    //                     return "<img class='img-data-tables' height='100px;' width='150px' src='{{url('/public/imgs/products')}}" + '/' + image + "'>"
    //                 }
    //                 },         
                       // {
             //        data: 'id', render: function (data, data2, type, row) {

             //         var edit = "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Product' ></i></a>&nbsp;";
             //         var deactivate = "<button data-toggle='modal' data-target='#myModal' onclick='openModal(" + data + ")' type='button' class='btn btn-danger' title='De-Activate'>De-Activate</button>";
             //         var activate = "<button data-toggle='modal' data-target='#myModal' onclick='openModal(" + data + ")' type='button' class='btn btn-danger' title='Activate'>Activate</button>"

             //         var details = "<a href='{{$details}}" + '/' + data  + "' class='btn btn-primary'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";
             //        if(type.is_bundle == 0){
             //            // var manage_bundle = "<a href='{{$manageUrl}}" + '/' + data + '/manage_bundle' + "' class='btn btn-primary' id='manage_bundle'><i class='fa fa fa-th-large' data-toggle='tooltip' data-placement='top' title='' ></i></a>&nbsp;";
             //            // var manage = "<a href='{{$manageUrl}}" + '/' + data + '/manage' + "' class='btn btn-primary disabled'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";
             //        }else{
             //            // var manage_bundle = "<a href='{{$manageUrl}}" + '/' + data + '/manage_bundle' + "' class='btn btn-primary disabled' id='manage_bundle'><i class='fa fa fa-th-large' data-toggle='tooltip' data-placement='top' title='' ></i></a>&nbsp;";
             //            // var manage = "<a href='{{$manageUrl}}" + '/' + data + '/manage' + "' class='btn btn-primary '><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";
             //        }
                  
             //        if (type.active == 1) {
             //            return details + edit + deactivate ;
             //        }
             //        else
             //        {
             //            return details + edit + activate;
             //        }              
            	// }
            // }
            ]
        });


 function openModal(id) {
     $('#delItem').one('click',function (e) {
         e.preventDefault();
         delete_record(id);
     });
 }

 function delete_record(id) {

     // ajax delete data to database
     $.ajax({
         url: "{{$changestatus}}/" + id,
         type: "GET",

         success: function (data) {
             //if success reload ajax table
             // console.log(data.success);
             $('#myModal').modal('hide');

             if(data =='success'){
                 $('#items_datatable').DataTable().draw(false)

             }
         //                reload_table();
             
         },
         error: function (jqXHR, textStatus, errorThrown) {
             alert('Error deleting data');
         }
     });

     $('#myModal').modal('toggle');

 //            setTimeout(function () {
 //                table.ajax.reload();
 //            }, 300);


         }
 


    $('select').change(function () {
    //     warehouse= document.getElementById("warehouse").value;
    //     if(item_group == -1 || item_group == 0){
    //         $('#order2').addClass('disabled');  
    //     }else{
    //         $("#order2").attr('href', '{{$order}}'+'/'+item_group+'/order');
    //         $('#order2').removeClass('disabled');      
    //     }
    //       // if (ckbox.is(':selected')) {
    //       //    item_group= document.getElementById("item_group").value;
    //       // } 
       table.ajax.reload();

      });


    $(document).ready(function () {
        
        // if(item_group == 0){
            // $('#order2').addClass('disabled');
        // }
        $('#reloadTableButton').click(function(){
	        table.ajax.reload();
        });
    });
    
   


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>