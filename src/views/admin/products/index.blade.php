<<<<<<< HEAD
    <!DOCTYPE html>
=======
<!DOCTYPE html>
>>>>>>> 3c3095f3495a180ab23103a66369f5e917cb9450
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


<<<<<<< HEAD
@include('layouts.modals.statusModal')   

    <!-- Start right Content here -->
=======
@include('layouts.modals.statusModal')

<!-- Start right Content here -->
>>>>>>> 3c3095f3495a180ab23103a66369f5e917cb9450
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <!-- Bread Crumb And Title Section -->
<<<<<<< HEAD
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
                <a href="{{url('/admin/products/create')}}" class="btn btn-rounded btn-primary"><i
                            class="zmdi zmdi-plus-circle-o"></i> Add New Product</a>
                <div class="col-sm-3">
                    <select id="item_group" class="form-control" >
                        <option disabled selected value="0">Select Category</option>
                        <option value="-1">All Categories</option>
                        @foreach($categories as $key=> $category)
                        <optgroup label="{{$key}}">
                            @foreach($category as $cat)
                            <option value="{{$cat->id}}">{{$cat->name_en}}-{{$cat->name}}</option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                </div>

                <a href="#" id="order2" class="btn btn-rounded btn-primary"><i class="zmdi zmdi-plus-circle-o"></i>Order Products According To Category</a>
                <a href="{{url('admin/product/reordering')}}" id="order3" class="btn btn-rounded btn-primary"><i class="zmdi zmdi-plus-circle-o"></i>  Products Reorder</a>

<div class="row" style="margin-left:8px;margin-top: 10px; ">
  

                        <div class="col-sm-3"><input type="checkbox" name="active" value="1" checked  id="active" >Show Active Products</div>
                        <div class="col-sm-3">  <input type="checkbox" name="inactive" value="" id="inactive" >Show Inactive Products</div>
</div>
            </div>
                       
                        <div class="card-text">
                           
=======
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
                            <a href="{{url('/admin/products/importStocks')}}" class="btn btn-rounded btn-primary"><i
                                        class="zmdi zmdi-plus-circle-o"></i>Import stock</a>


                            <a href="{{url('/admin/products/create')}}" class="btn btn-rounded btn-primary"><i
                                        class="zmdi zmdi-plus-circle-o"></i> Add New Product</a>
                            <div class="col-sm-3">
                                <select id="item_group" class="form-control">
                                    <option disabled selected value="0">Select Category</option>
                                    <option value="-1">All Categories</option>
                                    @foreach($categories as $key=> $category)
                                        <optgroup label="{{$key}}">
                                            @foreach($category as $cat)
                                                <option value="{{$cat->id}}">{{$cat->name_en}}-{{$cat->name}}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <a href="#" id="order2" class="btn btn-rounded btn-primary"><i
                                        class="zmdi zmdi-plus-circle-o"></i>Order Products According To Category</a>
                            <a href="{{url('admin/product/reordering')}}" id="order3"
                               class="btn btn-rounded btn-primary"><i class="zmdi zmdi-plus-circle-o"></i> Products
                                Reorder</a>

                            <div class="row" style="margin-left:8px;margin-top: 10px; ">


                                <div class="col-sm-3"><input type="checkbox" name="active" value="1" checked
                                                             id="active">Show Active Products
                                </div>
                                <div class="col-sm-3"><input type="checkbox" name="inactive" value="" id="inactive">Show
                                    Inactive Products
                                </div>
                            </div>
                        </div>

                        <div class="card-text">

>>>>>>> 3c3095f3495a180ab23103a66369f5e917cb9450

                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Selling Price</th>
                                    <th>Image</th>
                                    <!-- <th>Barcode</th> -->
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
<<<<<<< HEAD
     
 <?php $editurl = url('/admin/products/');?>
 <?php $changestatus = url('/admin/product/status'); ?>
 <?php $order = url('/admin/products');?>
 <?php $manageUrl = url('/admin/products/'); ?>
 <?php $details = url('/admin/product-details/');?>

 var item_group = 0;

 // var ckbox = $('#item_group');

 var active= document.getElementById("active").value;  
 var inactive=document.getElementById("inactive").value;
 var ckbox = $('#active');
 var ckbox1=$('#inactive');

 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
          ajax:{

              url:'{!! route('productsList') !!}',
              type:"GET",
              data: function(d){
                  d.item_group=document.getElementById('item_group').value;
                  d.active=document.getElementById('active').value,
                  d.inactive=document.getElementById('inactive').value;

              }
              },
        columns: [{
            data: 'name_en', render: function (name1, name2, type, row) { 
            if(name1.length > 40){
                name1 = name1.substr(0,38)+'...</span>';
            }
            if(type.name.length >40){
                type.name = type.name.substr( 0, 38 )+'...</span>';
            }
            return name1 + '   <b>EN</b>' + ' <br> ' + type.name + '   <b>AR</b>'

        }},


            // {data: 'parent_item_group', render : function(data){
             //  var res = data.split("###");
             //  return res[res.length == 3 ? 1 :  0];
            // }},
            {data: 'item_group', name: 'item_group'},
            {data: 'selling_price', name: 'selling_price'},
            {  data: 'image', searchable: false, render: function (image, type, row) {
                        return "<img class='img-data-tables' height='100px;' width='150px' style='object-fit: contain' src='{{url('/public/imgs/products')}}" + '/' + image + "'>"
              }
=======

        <?php $editurl = url('/admin/products/');?>
        <?php $changestatus = url('/admin/product/status'); ?>
        <?php $order = url('/admin/products');?>
        <?php $manageUrl = url('/admin/products/'); ?>
        <?php $details = url('/admin/product-details/');?>

    var item_group = 0;

    // var ckbox = $('#item_group');

    var active = document.getElementById("active").value;
    var inactive = document.getElementById("inactive").value;
    var ckbox = $('#active');
    var ckbox1 = $('#inactive');

    var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {

            url: '{!! route('productsList') !!}',
            type: "GET",
            data: function (d) {
                d.item_group = document.getElementById('item_group').value;
                d.active = document.getElementById('active').value,
                    d.inactive = document.getElementById('inactive').value;

            }
        },
        columns: [{
            data: 'name_en', render: function (name1, name2, type, row) {
                if (name1.length > 40) {
                    name1 = name1.substr(0, 38) + '...</span>';
                }
                if (type.name.length > 40) {
                    type.name = type.name.substr(0, 38) + '...</span>';
                }
                return name1 + '   <b>EN</b>' + ' <br> ' + type.name + '   <b>AR</b>'

            }
        },


            // {data: 'parent_item_group', render : function(data){
            //  var res = data.split("###");
            //  return res[res.length == 3 ? 1 :  0];
            // }},
            {data: 'item_group', name: 'item_group'},
            {data: 'selling_price', name: 'selling_price'},
            {
                data: 'image', searchable: false, render: function (image, type, row) {
                    return "<img class='img-data-tables' height='100px;' width='150px' style='object-fit: contain' src='{{url('/public/imgs/products')}}" + '/' + image + "'>"
                }
>>>>>>> 3c3095f3495a180ab23103a66369f5e917cb9450
            },
            // {  data: 'barcode', searchable: false, render: function (barcode, type, row) {
            //             return "<img class='img-data-tables' height='100px;' width='150px' style='object-fit: contain' src='{{url('/public/imgs/products/barcodes')}}" + '/' + barcode + "'>"
            //   }
            // },
            {
<<<<<<< HEAD
                    data: 'id', render: function (data, data2, type, row) {

                     var edit = "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Product' ></i></a>&nbsp;";
                     var deactivate = "<button data-toggle='modal' data-target='#myModal' onclick='openModal(" + data + ")' type='button' class='btn btn-danger' title='De-Activate'>De-Activate</button>";
                     var activate = "<button data-toggle='modal' data-target='#myModal' onclick='openModal(" + data + ")' type='button' class='btn btn-danger' title='Activate'>Activate</button>"

                     var details = "<a href='{{$details}}" + '/' + data  + "' class='btn btn-primary'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";
                    if(type.is_bundle == 0){
                        // var manage_bundle = "<a href='{{$manageUrl}}" + '/' + data + '/manage_bundle' + "' class='btn btn-primary' id='manage_bundle'><i class='fa fa fa-th-large' data-toggle='tooltip' data-placement='top' title='' ></i></a>&nbsp;";
                        // var manage = "<a href='{{$manageUrl}}" + '/' + data + '/manage' + "' class='btn btn-primary disabled'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";
                    }else{
                        // var manage_bundle = "<a href='{{$manageUrl}}" + '/' + data + '/manage_bundle' + "' class='btn btn-primary disabled' id='manage_bundle'><i class='fa fa fa-th-large' data-toggle='tooltip' data-placement='top' title='' ></i></a>&nbsp;";
                        // var manage = "<a href='{{$manageUrl}}" + '/' + data + '/manage' + "' class='btn btn-primary '><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";
                    }
                  
                    if (type.active == 1) {
                        return details + edit + deactivate ;
                    }
                    else
                    {
                        return details + edit + activate;
                    }              
              }
            }]
        });

 $('input').change(function () {
      if (ckbox.is(':checked')) {
          $('#active').val(1);
      } else {
           $('#active').val(0);
      }
      if (ckbox1.is(':checked')) {
           $('#inactive').val(1);
      } else {
           $('#inactive').val(0);
      }
      if(ckbox1.is(':checked') && ckbox.is(':checked')){
        $('#active').val(-1);
        $('#inactive').val(-1);
      }
    table.ajax.reload();

   });

 $(document).ready(function () {
     $("[data-toggle='tooltip']").tooltip();
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

     // $('#myModal').modal('toggle');

 //            setTimeout(function () {
 //                table.ajax.reload();
 //            }, 300);


         }
 


    $('select').change(function () {
        item_group= document.getElementById("item_group").value;
        if(item_group == -1 || item_group == 0){
            $('#order2').addClass('disabled');  
        }else{
            $("#order2").attr('href', '{{$order}}'+'/'+item_group+'/order');
            $('#order2').removeClass('disabled');      
        }
          // if (ckbox.is(':selected')) {
          //    item_group= document.getElementById("item_group").value;
          // } 
       table.ajax.reload();

      });


    $(document).ready(function () {
        
        if(item_group == 0){
            $('#order2').addClass('disabled');
        }
        $('#reloadTableButton').click(function(){
          table.ajax.reload();
        });
    });
    
   
=======
                data: 'id', render: function (data, data2, type, row) {

                    var edit = "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Product' ></i></a>&nbsp;";
                    var deactivate = "<button data-toggle='modal' data-target='#myModal' onclick='openModal(" + data + ")' type='button' class='btn btn-danger' title='De-Activate'>De-Activate</button>";
                    var activate = "<button data-toggle='modal' data-target='#myModal' onclick='openModal(" + data + ")' type='button' class='btn btn-danger' title='Activate'>Activate</button>"

                    var details = "<a href='{{$details}}" + '/' + data + "' class='btn btn-primary'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";
                    if (type.is_bundle == 0) {
                        // var manage_bundle = "<a href='{{$manageUrl}}" + '/' + data + '/manage_bundle' + "' class='btn btn-primary' id='manage_bundle'><i class='fa fa fa-th-large' data-toggle='tooltip' data-placement='top' title='' ></i></a>&nbsp;";
                        // var manage = "<a href='{{$manageUrl}}" + '/' + data + '/manage' + "' class='btn btn-primary disabled'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";
                    } else {
                        // var manage_bundle = "<a href='{{$manageUrl}}" + '/' + data + '/manage_bundle' + "' class='btn btn-primary disabled' id='manage_bundle'><i class='fa fa fa-th-large' data-toggle='tooltip' data-placement='top' title='' ></i></a>&nbsp;";
                        // var manage = "<a href='{{$manageUrl}}" + '/' + data + '/manage' + "' class='btn btn-primary '><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";
                    }

                    if (type.active == 1) {
                        return details + edit + deactivate;
                    } else {
                        return details + edit + activate;
                    }
                }
            }]
    });

    $('input').change(function () {
        if (ckbox.is(':checked')) {
            $('#active').val(1);
        } else {
            $('#active').val(0);
        }
        if (ckbox1.is(':checked')) {
            $('#inactive').val(1);
        } else {
            $('#inactive').val(0);
        }
        if (ckbox1.is(':checked') && ckbox.is(':checked')) {
            $('#active').val(-1);
            $('#inactive').val(-1);
        }
        table.ajax.reload();

    });

    $(document).ready(function () {
        $("[data-toggle='tooltip']").tooltip();
    });


    function openModal(id) {
        $('#delItem').one('click', function (e) {
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

                if (data == 'success') {
                    $('#items_datatable').DataTable().draw(false)

                }
                //                reload_table();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });

        // $('#myModal').modal('toggle');

        //            setTimeout(function () {
        //                table.ajax.reload();
        //            }, 300);


    }


    $('select').change(function () {
        item_group = document.getElementById("item_group").value;
        if (item_group == -1 || item_group == 0) {
            $('#order2').addClass('disabled');
        } else {
            $("#order2").attr('href', '{{$order}}' + '/' + item_group + '/order');
            $('#order2').removeClass('disabled');
        }
        // if (ckbox.is(':selected')) {
        //    item_group= document.getElementById("item_group").value;
        // }
        table.ajax.reload();

    });


    $(document).ready(function () {

        if (item_group == 0) {
            $('#order2').addClass('disabled');
        }
        $('#reloadTableButton').click(function () {
            table.ajax.reload();
        });
    });
>>>>>>> 3c3095f3495a180ab23103a66369f5e917cb9450


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>