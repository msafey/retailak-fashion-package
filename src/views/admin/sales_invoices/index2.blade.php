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
                        Sales Invoice
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Purchase Invoices
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
                <div class="modal fade bd-example-modal-lg" id="myModal3" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

                </div>

<div class="modal fade bd-example-modal-lg" id="myModal4" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

</div>

<div class="modal fade bd-example-modal-lg" id="myModal1" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

    </div>




                <div class="row">

                    <div class="card card-block">
	                  <div class="card-title">

                            <!-- Add Company Button -->
                       <!--      <div class="row">   
                                <label for="">Filter By Sales Order Id</label>

                                <input type="number" name="sales_order_id"  value=""  id="sales_order_id">
                                <button id="sales_order_id_search" ><i class="fa fa-search"></i></button>

                            </div> -->
                            
                            <div class="row">
                                    <div class="col-sm-1" style="margin-top: 5px;">
                                        <label for="">Filter By :</label>
                                    </div>
                                    <div class="col-sm-3">
                                        <select name="search_filter" class="form-control" id="search_filter">
                                            <option value="1">Sales Orders</option>
                                            <option value="3">Sales Invoices</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="number" placeholder="ID" name="search_id" class="form-control"  id="search_id">
                                    </div> 

                                    <div class="col-sm-2" style="margin-top: 8px"><input type="checkbox" name="pending" value="" checked  id="pending" >Show Pending SI</div>
                                    <div class="col-sm-3" style="margin-top: 8px"> <input type="checkbox" name="completed" value="1" id="completed" >Show Completed SI</div>
                            </div>



                                        <!-- Add Company Button End-->


                <!-- Add Company Button -->
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

    <div class="modal fade bs-example-modal-sm" id="deleteModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Confirmation
                </div>
                <div class="modal-body">
                    Are you Sure That You Want To Delete Sales Invoice?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem" title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Delete Invoice </a>

                </div>
            </div>

        </div>
    </div>
    <div class="modal fade bs-example-modal-sm statusModal" id="cancelModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Confirmation
                </div>
                <div class="modal-body">
                    Are you Sure That You Want To Cancel Invoice ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger changeItem" href="javascript:void(0)" id="cancelItem" title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Cancel Invoice </a>

                </div>
            </div>

        </div>
    </div>
    <div class="modal fade bs-example-modal-sm statusModal" id="submitModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Confirmation
                </div>
                <div class="modal-body">
                    Are you Sure That You Want To Submit Invoice ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger changeItem " href="javascript:void(0)" id="submitItem"
                       title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Submit Invoice </a>

                </div>
            </div>

        </div>
    </div>

                <!-- Add Company Button End-->

            </div>
                       
                        <div class="card-text">
                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Sales Order Id</th>
                                    <th>Sales Invoice Id</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>


                            </table>
<input type="text" hidden="" id="sales_order" name="sales_order" value="@if(isset($_GET['sales_order_id'])){{$_GET['sales_order_id']}} @endif">

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

    <?php $listUrl = url('admin/salesInvoicesAccountingList');?>

    // <?php $changestatus = url('/admin/sales-invoices/cancel'); ?>
    // <?php $submit = url('/admin/products');?>


     
 // <?php $paymentUrl = url('/admin/payments/create');?>
// var sales_order_id = document.getElementById('sales_order_id').value;
 var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url:'{{$listUrl}}',
            type:"GET",
            data: function(d){
                d.search_id=document.getElementById('search_id').value,
                d.search_filter=document.getElementById('search_filter').value,
                d.pending=document.getElementById('pending').value,
                d.completed=document.getElementById('completed').value;
            }
        },
        columns: [
        {data:'sales_order_id',name:'sales_order_id'},
        {data:'id',name:'id'},
        {data:'grand_total_amount',name:'grand_total_amount'},
        {data: 'status', render: function (status, type, row) {
            if (status == 2) {
                return "Submitted"
            }
            else if(status == 1){
                return "Added"
            }else{
                return 'Cancelled'
            }
        }
              },{data: 'id', render: function (data, data2, type, row) {

                    // var submit = "<button data-toggle='modal' data-target='#submitModal' onclick='changeStatus(" + data + ',' + 2 + ")' type='button' class='btn btn-primary' title='Submit'>Submit</button>";
                     // var payment =   "<a href='{{$paymentUrl}}?invoice=" + data + "' class='btn btn-w" + "arning'>Payment</a>&nbsp;"
                    // var cancel = " <button data-toggle='modal' data-target='#cancelModal' onclick='changeStatus(" + data + ',' + 0 + ")' type='button' class='btn btn-cancel' title='Cancel'>Cancel</button>"
                    // var remove = "<button data-toggle='modal' data-target='#deleteModal' onclick='delete_record(" + data + ")' type='button' class='btn btn-danger' title='Delete'>Delete</button>";
                    var payment = '<a  data-toggle="modal" id="payment' + data +'" data-target="#myModal3" title="Payment" class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i></a>';

                    var invoice = '<a  data-toggle="modal" id="invoice' + data +'" data-target="#myModal3" title="Invoice" class="btn btn-warning btn-sm sales_invoice"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                        return  invoice + '&nbsp;&nbsp;&nbsp;' +payment;

                }
                }]
        });
 

 function wait(ms){
    var start = new Date().getTime();
    var end = start;
    while(end < start + ms) {
      end = new Date().getTime();
   }
 }

 // $(document).on('click', '#sales_order_id_search', function(e){
 //  //7 seconds in milliseconds
 //         table.ajax.reload();
 //    });

 // $(document).on('input', '#sales_order_id', function(e){
 //  //7 seconds in milliseconds
 //  if($('#sales_order_id').val() == ''){
 //         table.ajax.reload();
 //  }
 //    });

 var ckbox = $('#pending');
 var ckbox1=$('#completed');

 $('#pending').change(function () {


       if (ckbox.is(':checked')) {
           $('#pending').val(1);
       } else {
           $('#pending').val(0);
       }
    table.ajax.reload();

   });


 $('#completed').change(function () {
       if (ckbox1.is(':checked')) {
           $('#completed').val(1);
           } else {
           $('#completed').val(0);
       }
    table.ajax.reload();

   });



    function delete_record(id) {
        $('#delItem').one('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{url('admin/sales-invoices/delete')}}/" + id,
                type: "GET",

                success: function (data) {
                    if (data == 'not allowed') {
                        alert('Cancel Payment First !');
                        $('#deleteModal').modal('hide');
                    }
                    $('#deleteModal').modal('hide');
                    $('#items_datatable').DataTable().draw(false)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });

        });
    }

    function changeStatus(id, status) {
        $('.changeItem').one('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{url('admin/sales-invoices/status')}}/" + id + '/' + status,
                type: "GET",

                success: function (data) {
                    if (data == 'not allowed') {
                        alert('Cancel Payment First !');
                        $('.statusModal').modal('hide');
                    }
                    if (data == 'true') {
                    $('.statusModal').modal('hide');
                    $('#items_datatable').DataTable().draw(false);
                }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error changing data');
                }
            });

        });
    }
    
    $(document).ready(function () {
        var purchase_order = $('#sales_order').val() 
        if(purchase_order !=''){
            $('#sales_order_id').val(parseInt(purchase_order));
            table.ajax.reload();

        }        
        $('#reloadTableButton').click(function(){
	        table.ajax.reload();
        });
    });
    
    $(document).on('click','.payment' , function (event){
        var order_delivery_id = -1;
        var order_id = this.id.match(/\d+/); // 123456          
         $.ajax({
           method: 'GET',
           url: '{!! route('payment') !!}',
           data: {'order_id' : order_id,'order_delivery_id':order_delivery_id},
           success: function(response){
            if(response == 'no sales invoice submitted' || response=='no sales invoice'){
                alert('Create Sales Invoice First');
                hideModal();
                location.reload();
                // event.preventDefault();
            }

            if($('.modal_exist').length <= 0){
                $('#myModal1').append(response.data);
                $('#myModal1').modal('show');
                var payment_exist=$('#payment_exist').val();
                if(!payment_exist){
                        $('#cancel').attr('hidden',true);
                        $('#save').attr('hidden',false);
                      }else{
                        $('#save').attr('hidden',true);
                        $('#cancel').attr('hidden',false);

                      }
            }
           },
           error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
         }
         });
        // console.log($('.order_status').is(":disabled"));
        // if($("#"+order_id).is(":disabled") == false){
        //     event.preventDefault();
        //     alert('Deliver Order First');
        // }
    });


    $(document).on('click','.sales_invoice',function (event){
        var order_delivery_id = 0 ;
        // console.log(order_delivery_id);
        var sales_order_id = this.id.match(/\d+/); // 123456   
        // console.log(sales_order_id);
        // console.log(sales_order_id);
         $.ajax({
           method: 'GET',
           url: '{!! route('sales_invoice') !!}',
           data: {'order_id' : sales_order_id,'order_delivery_id':order_delivery_id},
           success: function(response){
            if(response=='Deliver Order First'){
                alert('Deliver Order First');
                $('#myModal3').modal('hide');
                event.preventDefault();
            }

            if($('.invoice_modal_exist').length <= 0){
                $('#myModal3').append(response.data);
            }
            var sales_invoice_exist=$('#sales_invoice_exist').val();
            if(!sales_invoice_exist){
                    $('#invoice_cancel').attr('hidden',true);
                    $('#invoice_save').attr('hidden',false);
                  }else{
                    $('#invoice_save').attr('hidden',true);
                    $('#invoice_cancel').attr('hidden',false);

                  }
         
           },
           error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
         }
         });
    });
function hideModal() {
  $("#myModal1").removeClass("in");
  $(".modal-backdrop").remove();
  $("#myModal1").hide();
}


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>