<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head') 
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

</script>
<!-- <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
 -->



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
                        Purchase Receipts
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                         Purchase Receipts
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
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
                    Are you Sure That You Want To Delete Purchase Receipt?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem" title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Delete Receipt </a>

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
                    Are you Sure That You Want To Cancel Receipt ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger changeItem" href="javascript:void(0)" id="cancelItem" title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Cancel Receipt </a>

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
                    Are you Sure That You Want To Submit Receipt ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>
                    <a class="btn btn-sm btn-danger changeItem " href="javascript:void(0)" id="submitItem"
                       title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Submit Receipt </a>

                </div>
            </div>

        </div>
    </div>



                <div class="card card-block">
                    
                  <div class="row">


                        <div class="col-lg-6">
                            <div class="col-sm-12">
                              <label style="margin-bottom: 0;"  class="form-group" for="from">Company
                              </label>
                            </div>
                            <div class="col-sm-12" >
                              <div class='input-group date' id='' style="display: inline;">
                                <input type='text' name="company" disabled="disabled" value="@if(isset($company_selected)){{$company_selected->name_en}} @endif" class="form-control"/>
                              </div>
                            </div>
                        </div>


                        
                        <div class="col-lg-6">
                          <div class="col-sm-12">
                            <label style="margin-bottom: 0;"  class="form-group" for="from">Required By Date:
                            </label>
                          </div>
                          <div class="col-sm-12" >
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' name="required_by_date" disabled="disabled" value="@if(isset($required_by_date)) {{$required_by_date}} @endif" class="form-control"/>
                                <span class="input-group-addon">
                                    <span class="zmdi zmdi-calendar"></span>
                                </span>
                            </div>
                          </div>
                        </div>
                  </div>

                      <div class="row">
                           <div class="col-lg-12">
                              <div class="col-sm-12">

                            <table id="myTable"  class=" table order-list" style="width: 850px;margin-left: 80px;">
                            <thead>
                                <tr>
                                    <td class="col-sm-6">Items</td>
                                    <td class="col-sm-6">Accepted Quantity</td>
                                </tr>
                            </thead>
                            <tbody id="tblrownew0">
                            @foreach($item_details as $order_item)
                                <tr id="field1">
                                    <td class="col-sm-6">
                                      <div class='input-group date' id='' style="display: inline;">
                                          <input type="text" disabled="disabled" class="form-control" name="" value="{{$order_item->product_name}}">
                                      </div>                                    
                                    </td>

                                    <td class="col-sm-6">
                                        <input type="number" disabled="disabled"  min="1"   name="" value="{{$order_item->qty}}" id="qty"  class="form-control qty"/>
                                    </td>
                                 </tr>
  
                            @endforeach
                            @if(count($parent_array)> 0)
                              @foreach($parent_array as $key => $array)
                                <tr>
                                  <td ><label>Variations Of ( <span style='color: red;'><b>'#{{$key}}'</b></span> )</label></td>
                                </tr>
                                @foreach($array as $data)
                                  <tr id="field1">
                                      <td class="col-sm-6">
                                        <div class='input-group date' id='' style="display: inline;">
                                            <input type="text" disabled="disabled" class="form-control" name="" value="{{$data->product_name}}">
                                        </div>                                    
                                      </td>

                                      <td class="col-sm-6">
                                          <input type="number" disabled="disabled"  min="1"   name="" value="{{$data->qty}}" id="qty"  class="form-control qty"/>
                                      </td>
                                   </tr>
                                @endforeach
                              @endforeach
                            @endif
                                <hr>
                            </tbody>
                            <tfoot>
                                <tr>
                                  <td></td>
                                  <td></td>
                                  
                                </tr>
                                <tr>
                                </tr>
                            </tfoot>
                            </table>
                            <hr>

                            </div>
                            </div>
                      </div>
                      
                      
                      <div class="row">
                          <div class="col-lg-6">
                              <div class="col-lg-12">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">Taxes & Charges
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px">
                                  <div class='input-group date' style="display: inline;" id=''>
                                       <input type='text' name="selected_taxs" disabled="disabled" value="@if(isset($selected_tax->type)){{$selected_tax->title}} @endif" class="form-control"/>
                                  </div>
                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="col-lg-12" style="">
                                  <label style="margin-bottom: 0;" class="form-group" for="from">Shipping Rule
                                  </label>
                              </div>
                              <div class="col-lg-12" style="margin-top: 0px">
                                              <div class='input-group date' style="display: inline;" id=''>
                                                    <input type='text' name="selected_taxs" disabled="disabled" value="@if(isset($selected_shipping_rule)) {{$selected_shipping_rule->shipping_rule_label}} @endif" class="form-control"/>

                                              </div>
                                          </div>
                          </div> 
                        </div>
                        <hr>  
                        <div class="row"> 
                            <div class="col-sm-6" style="margin-left: 290px;"> 
                               <?php $changestatus = url('/admin/purchase-receipts/cancel'); ?>

                                
                            <?php $editurl = url('/admin/purchase-orders/');?>
                            <?php $invoiceUrl = url('/admin/purchase-receipts/');?>
                            <?php $download = url('/admin/purchase-receipts/download-file');?>

                            @if($purchase_receipt->status == 2 )
                              <button data-toggle='modal' data-target='#cancelModal' onclick='changeStatus({{$purchase_receipt->id}},0)' type='button' class='btn btn-cancel' title='Cancel'>Cancel</button>
                            @else
                              <button data-toggle='modal' data-target='#submitModal' onclick='changeStatus({{$purchase_receipt->id}},2)' type='button' class='btn btn-primary' title='Submit'>Submit</button>
                            @endif
                              <a href='{{$invoiceUrl}}/{{$purchase_receipt->id}}/invoice' target="_blank" class='btn btn-secondary'>Invoice</a>
                              <a href='{{$editurl}}/{{$purchase_order_id}}/details' target="_blank" class='btn btn-success'>Purchase Order Details</a>
                              

                              <button data-toggle='modal' data-target='#deleteModal' onclick='delete_record({{$purchase_receipt->id}})' type='button' class='btn btn-danger' title='Delete'>Delete</button>
                            <a href='{{$download}}/{{$purchase_receipt->id}}' class='btn btn-warning'>Download</a>
                            </div>
                        </div>
            </div>
        </div>


    </div>
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
<script src="{{url('/public/')}}/prasley/parsley.js"></script>
<script src="{{url('/public/admin/plugins/moment/')}}/moment.js"></script>
<script src="{{url('/public/admin/')}}/js/bootstrap-datetimepicker.js"></script>


    <script src="{{url('components/components/select2/dist/js/select2.js')}}"></script>


<script type="text/javascript">

                            <?php $purchase_receipts = url('/admin/purchase-orders/'.$purchase_order_id.'/purchase-receipts');?>

  function delete_record(id) {
      $('#delItem').one('click', function (e) {
          e.preventDefault();
          $.ajax({
              url: "{{url('admin/purchase-receipts/delete')}}/" + id,
              type: "GET",

              success: function (data) {
                  if (data == 'not allowed') {
                      alert("cancel purchase invoice of this receipt first !");
                      $('#deleteModal').modal('hide');

                  }
                  $('#deleteModal').modal('hide');
window.location.href = '<?php echo $purchase_receipts; ?>';
                  // $('#items_datatable').DataTable().draw(false)
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
              url: "{{url('admin/purchase-receipts/status')}}/" + id + '/' + status,
              type: "GET",
              success: function (data) {
                  // console.log(data);
                  if(data == 'already_cancelled'){
                      // alert('This PR is already Cancelled');
                  }
                  if(data == 'already_submitted'){
                      // alert('This PR is already Submitted');
                  }
                  if(data=='not allowed'){
                      alert('Cancel Payment First !');
                  $('.statusModal').modal('hide');
                  }
                  if (!data) {
                      alert('Error changing data');
                  }
                  $('.statusModal').modal('hide');
                  location.reload();
                  // $('#items_datatable').DataTable().draw(false);
              },
              error: function (jqXHR, textStatus, errorThrown) {
                  alert('Error changing data');
              }
          });

      });
  }
</script>
<!-- JAVASCRIPT AREA -->
</body>
</html>