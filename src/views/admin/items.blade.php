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
                        Items Reports
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Items
                @endslot
                You are not allowed to access this resource!
                @endcomponent  
            <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
	                  <div class="card-title">

                <!-- Add Company Button -->

            </div>


 <div class="modal fade bd-example-modal-lg" id="myModal1" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

    </div>

    <div class="modal fade bd-example-modal-lg" id="myModal2" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

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
                                    <th>Product Id</th>
                                    <th>Product Name</th>
                                    <th>Selling Price</th>
                                    <th>Buying Price</th>
                                    <th>Profit</th>
                                    <th>Price History</th>
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
<?php $deliverdturl = url('/admin/delivery/orders/status');?>
 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        order: [ [0, 'desc'] ],
        ajax: '{!! route('itemList2') !!}',
        columns: [

        {data: 'product_id',name:'product_id'},
        {data: 'product_name',name:'product_name'},
        {data: 'selling_price',name:'selling_price'},
        {data: 'buying_price',name:'buying_price'},
        {data: 'profit',name:'profit'},
        {data: 'product_id',render: function(data,data2,type,row){
            return '<button style=";" id="selling'+data+'"   href="#" data-target="#myModal2" data-toggle="modal" title="Selling Price History" class="btn btn-warning btn-sm selling_history">Selling Price History</button>' +  '<button style="margin-left:20px;" id="buying'+data+'"   href="#" data-target="#myModal1" data-toggle="modal" title="Buying Price History" class="btn btn-warning btn-sm buying_history">Buying Price History</button>';


        }},

        // {
                    // data: 'product_id', render: function (data, data2, type, row) {
                       
                    // if (type.active == 1) {

                        // return type.selling_price + '    '+'<a  id="buying'+data+'"   href="#" data-target="#myModal1" data-toggle="modal" title="Buying Price History" class="btn btn-warning btn-sm buying_history"><i class="fa fa-external-link-square" aria-hidden="true"></i></a>';
                    // }
                    // else
                    // {
                        // return edit + activate;
                    // }

                // }
                // }
        ]
        });
       
$(document).on('click','.buying_history',function(event){
    var product_id = this.id.match(/\d+/); // 123456 
      $.ajax({
        method: 'GET',
        url: '{!! route('getBuyingPriceHistory') !!}',
        data: {'product_id' : product_id},
        success: function(response){
            if($('.email_modal_exist').length <= 0){
                $('#myModal1').append(response.data);
                // $('#myModal1').show();
               
            }
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
      });
    });


$(document).on('click','.selling_history',function(event){
    var product_id = this.id.match(/\d+/); // 123456 
      $.ajax({
        method: 'GET',
        url: '{!! route('getSellingPriceHistory') !!}',
        data: {'product_id' : product_id},
        success: function(response){
            if($('.selling_modal_exist').length <= 0){
                $('#myModal2').append(response.data);
                // $('#myModal1').show();
               
            }
        },
        error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
        console.log(JSON.stringify(jqXHR));
        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
      });
    });

    
    $(document).ready(function () {
        
        
        $('#reloadTableButton').click(function(){
	        table.ajax.reload();
        });
    });

    $(document).ready(function () {
        $("[data-toggle='tooltip']").tooltip();
    });

   
   


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>