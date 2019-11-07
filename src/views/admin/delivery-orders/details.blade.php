<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')

<link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
      type="text/css"/>
<link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
      type="text/css"/>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style>


    .remove .removeborder td, th {
        border: none;
        height: 75px;

    }

    .table td, th {
        border-collapse: collapse;

        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        /*vertical-align: middle;*/
        text-align: center;

    }

   

    .checkbox td:nth-of-type(1) {
        margin-top: 0px;

    }

    .table td:nth-of-type(1) {
        width: 85px;
        vertical-align: middle;
        text-align: center;
    }
.light h6{
font-weight: 400;
}
    .hr1 {
        width: 65px;
        margin-bottom: -12px;
        padding: 5px;
        width: 50px;
        border-top: 5px solid rgba(0, 0, 0, 0.1);
    }

    .button {
        /*padding-top: 22px;*/
        /*margin-top: 2px;*/
         /*margin-right: 3px; */
    }

    .firstLine td {
        border-bottom: 2px solid black;
    }

    .height_div {
        vertical-align: middle;
        margin-bottom: 20px;
        margin-right: 0px;
        text-align: center;

        /*height: 400px;*/
    }

    .inside_div {
        height: 45px;
    }

    .final_price {

       }

</style>


<!-- <link rel="stylesheet" href="/resources/demos/style.css">
 --><style>
#sortable1{
    margin-left: 10px;
}
#sortable1, #sortable2 , #sortable3,#sortable4{
  border: 1px solid #eee;
  width: 142px;
  min-height: 20px;
  list-style-type: none;
  margin: 0;
  padding: 5px 0 0 0;
  float: left;
  margin-right: 10px;
}
#sortable1 {height:300px; width:18%;}
#sortable1 {overflow:hidden; overflow-y:scroll;}

#sortable2 {height:300px; width:18%;}
#sortable2 {overflow:hidden; overflow-y:scroll;}


#sortable3 {height:300px; width:18%;}
#sortable3 {overflow:hidden; overflow-y:scroll;}


#sortable4 {height:300px; width:18%;}
#sortable4 {overflow:hidden; overflow-y:scroll;}

#sortable1 li, #sortable2 li,#sortable3 li ,#sortable4 li {
  margin: 0 5px 5px 5px;
      padding: 5px 5px;
      font-size: 1.2em;
      height: 55px;
}
#sortable3 .ui-state-highlight{
    background: red !important;
    min-height: 176px;

}


</style>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$( function() {
    $("#sortable1").sortable({
    items: "li:not(.ui-state-disabled)",
    cancel: ".unsortable"
});
$("#sortable2").sortable({
        items: "li:not(.ui-state-disabled)",
        cancel: " .unsortable"
    });
$("#sortable3").sortable({
    items: "li:not(.ui-state-disabled)",
    cancel: ".unsortable"
});

$("#sortable4").sortable({
    items: "li:not(.ui-state-disabled)",
    cancel: ".unsortable"
});
  $( "#sortable1, #sortable2 #sortable3 #sortable4" ).sortable({
    connectWith: ".connectedSortable"
  }).disableSelection();
} );
$(function(){
  $( "#sortable1, #sortable4" ).sortable({
    connectWith: ".connectedSortable2"
  }).disableSelection();
});

  $( function() {
  $( "#sortable2, #sortable3" ).sortable({
    connectWith: ".connectedSortable1"
  }).disableSelection();
} );

</script>


<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Plugins css -->
    <link href="{{url('public/admin/plugins/timepicker/bootstrap-timepicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/mjolnic-bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--//Delivered Modal--}}
    <div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    Confirmation
                </div>
                <div class="modal-body">
                    Are you Sure That You Want To Delivery This Orders ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>

                    <a id="delivered"  class="btn btn-sm btn-danger" href="javascript:void(0)"
                       title="Hapus" data-dismiss="modal" ><i
                                class="glyphicon glyphicon-trash"></i> Delivery Orders</a>

                </div>
            </div>

        </div>
    </div>




    <div class="modal fade bd-example-modal-lg" id="myModal1" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

    </div>




<div class="modal fade bd-example-modal-lg" id="myModal4" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

</div>

<div class="modal fade bd-example-modal-lg" id="myModal3" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

</div>

    

    {{--Canceled Model--}}
    <div class="modal fade bs-example-modal-sm" id="myModal2" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                     <i class="fa fa-exclamation-triangle" aria-hidden="true">Confirmation</i>

                </div>
                <div class="modal-body">

            @foreach ($array_order as $arr_order)
            <div class="form-group">
                <label style="margin-bottom: 0;"  class="form-group" for="from">Id : {{$arr_order->id}} </label>    

                    <input class="checkbox cancel_order" type="checkbox"  id="cancel{{$arr_order->id}}" name="cancelled_orders[]" value="{{$arr_order->id}}"/>
                    </div>
            @endforeach
                    
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>

                    <a id="canceled"  class="btn btn-sm btn-danger"
                       href="javascript:void(0)"
                       title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Canceled Orders</a>

                </div>
            </div>

        </div>
    </div>




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
                        Run Sheets
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Run Sheets
                @endslot
                You are not allowed to access this resource!
                @endcomponent                    <!--End Bread Crumb And Title Section -->
               
                <?php $deliverdturl = url('/admin/runsheet/orders/status');?>

                <?php $changestatus = url('/admin/runsheet/order/status'); ?>
                <?php $urlshow = url('/admin/runsheet'); ?>


                <div class="card card-block">
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

                    <div class="row">
                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                    </div>
                    <div style="" class="card-text">

                      <div class="row"> 
                              
                              <p style="font-size:15px;font-weight:500;text-align:left;float:left;margin-left:100px; ">Run SheetID: {{$order_delivery_id}}</p>

                              <h4 style="font-size:15px;font-weight:500;text-align:right;float:right;margin-right: 600px;">Courier : {{$delivery_man}}</h3>
                              <hr style="clear:both;"/>


                      </div>
                   
                      <div class="row"> 
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                                <div class="card-box tilebox-one">
      
                                      <i class="ion-android-data pull-xs-right text-muted"></i>
                                      <h6 class="text-muted text-uppercase m-b-20">Count Pending Orders</h6>
                                      <h2 class="m-b-20" data-plugin="counterup" id="count_pending">{{$count_of_pending_orders}}</h2>
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                                <div class="card-box tilebox-one">
                                  
                                      <i class="ion-social-foursquare pull-xs-right text-muted"></i>
                                      <h6 class="text-muted text-uppercase m-b-20">Count Of Delivered</h6>
                                      <h2 class="m-b-20" data-plugin="counterup" id="count_delivered">{{$count_of_delivered_orders}}</h2>
                                </div>
                            </div>

                              <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                                <div class="card-box tilebox-one">
                                      <i class="ion-close-circled pull-xs-right text-muted"></i>
                                      <h6 class="text-muted text-uppercase m-b-20">Count Of Cancelled</h6>
                                      <h2 class="m-b-20" data-plugin="counterup" id="count_cancelled">{{$count_of_cancelled_orders}}</h2>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                                <div class="card-box tilebox-one">
                                  
                                      <i class="ion-pound pull-xs-right text-muted"></i>
                                      <h6 class="text-muted text-uppercase m-b-20">Count Of Void</h6>
                                      <h2 class="m-b-20" data-plugin="counterup" id="count_void">{{$count_of_void_orders}}</h2>
                                </div>
                            </div>

                      </div>  
                      <div class="row"> 
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3" style="width: 50%;">
                                  <div class="card-box tilebox-one">
                                      <i class="icon-rocket pull-xs-right text-muted"></i>
                                      <h6 class="text-muted text-uppercase m-b-20">Total Orders</h6>
                                      <h2 class="m-b-20" data-plugin="counterup">{{$total_orders}}</h2>
                                 </div>
                            </div>
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3" style="width: 50%;">
                                <div class="card-box tilebox-one">

                                      <i class="icon-paypal pull-xs-right text-muted"></i>
                                      <h6 class="text-muted text-uppercase m-b-20">Total Money</h6>
                                      <h2 class="m-b-20" data-plugin="counterup">{{$total_money}}</h2>
                                </div>
                            </div>
                         
                    </div>                                          
                
                        
                  </div>
                        <div class="row" style="margin-left:100px;margin-top: 50px;"> 
                            <?php $deliverdturl = url('/admin/runsheet/orders/status');?>
                            <?php $changestatus = url('/admin/runsheet/order/status'); ?>
                            <?php $urlshow = url('/admin/runsheet'); ?>
                      <ul style="width: 41%;margin-right: 100px;margin-bottom: 50px;" id="sortable1" class="connectedSortable">

                        <div class="row note unsortable" id="unsort" >
                          <div class="col-lg-12">
                            <div class="col-lg-5 p-1" style="margin-top: 8px ">
                              <label for="" class="form-group"><b> Pending Orders</b></label>
                            </div>
                            <div class="col-lg-6 p-0" >
                              <input type="number" id="pending_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" name="order_id">
                            </div>
                          </div>
                        </div>


                              @forelse($array_order as $order)
                                @if($order->status == 'Assigned')
                                <!-- <div id="pending">   -->
                                <li class="ui-state-default" id="{{$order->id}}" >

                                <div style="text-align: left;" class="col-lg-7 p-0 light">
                                    <div class="col-lg-5 p-0">
                                      <h6><b> ID: {{$order->id}}</b></h6>
                                      <h6><b>qty: </b> {{$order->qty_of_items}}</h6>
                                    </div>
                                    
                                    <div class="col-lg-7 p-0">
                                      <h6>{{$order->count_of_items}} <b>  Products </b></h6>
                                      <h6>{{$order->total}} <b>LE</b></h6>
                                    </div>
                                    
                                  </div>

                                  <div style="text-align:right;margin-top: 8px;" class="col-lg-5 p-0">
                                    <button style="" title="un-assign order" id="unassign{{$order->id}}" class=" button btn btn-warning btn-sm unassign">Un-Assign</button>

                                      <a class="btn btn-primary btn-sm" title="show order" style="" id="show"  href="{{$urlshow}}/{{$order->id}}"> <i class="fa fa-eye" aria-hidden="true"></i>
                                      </a>
                                      </span>
                                      <!-- <a class="btn btn-danger cancellation" title="cancel order" style="margin-bottom: 10px;" id='cancel{{$order->id}}' href="#"> <i class="fa fa-close" aria-hidden="true"></i> -->
                                      <!-- </a>&nbsp;&nbsp; -->

                                    <a style="display: none;"  data-toggle="modal" id="invoice{{$order->id}}" data-target="#myModal3"  href="{{$urlshow}}/{{$order->id}}/payment?delivery_order_id={{$order_delivery_id}}"  title="Invoice" class="btn btn-warning btn-sm sales_invoice"><i class="fa fa-external-link-square"
                                    aria-hidden="true"></i>
                                    </a>

                                    <a style="display: none;" data-toggle="modal" id="payment{{$order->id}}" data-target="#myModal1"  href="{{$urlshow}}/{{$order->id}}/payment?delivery_order_id={{$order_delivery_id}}" title="Payment"  class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i>
                                    </a>    
                                    </div>

                                    </li>
                                    <!-- </div> -->
                                    @endif


                                @empty
                              @endforelse
                      </ul>

                      <ul id="sortable2" style="width: 41%;" class="connectedSortable">
                                  <div class="row note unsortable" id="unsort" >
                                    <div class="col-lg-12">
                                      <div class="col-lg-5 p-1" style="margin-top: 8px ">
                                        <label for="" class="form-group"><b> Delivered Orders</b></label>
                                      </div>
                                      <div class="col-lg-6 p-0" >
                                        <input type="number" id="delivered_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" name="order_id">
                                      </div>
                                    </div>
                                    </div>

                                    @foreach($array_order as $order)
                                    <?php $array =[] ; ?>
                                    @if($order->status =='Delivered')
                                    <?php $array[] =$order ; ?>
                                    <li class="ui-state-default" id="{{$order->id}}" >
                                      <div style="text-align: left;" class="col-lg-7 p-0 light">
                                        <div class="col-lg-5 p-0">
                                          <h6><b> ID: {{$order->id}}</b></h6>
                                          <h6><b>qty: </b> {{$order->qty_of_items}}</h6>
                                        </div>
                                        
                                        <div class="col-lg-7 p-0">
                                          <h6>{{$order->count_of_items}} <b>  Products </b></h6>
                                          <h6>{{$order->total}} <b>LE</b></h6>
                                        </div>
                                        
                                      </div>
                                      <div style="text-align:right;margin-top: 8px;" class="col-lg-5 p-0">
                                        
                                        <a class="btn btn-primary btn-sm" style="" id="show" title="show order" disabled='disabled' href="{{$urlshow}}/{{$order->id}}"> <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                        <a style=""  data-toggle="modal" id="invoice{{$order->id}}" title="Invoice" data-target="#myModal3"  href="{{$urlshow}}/{{$order->id}}/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-warning btn-sm  sales_invoice"><i class="fa fa-external-link-square"
                                          aria-hidden="true"></i>
                                        </a>
                                        <a style="" data-toggle="modal" id="payment{{$order->id}}" title="Payment" data-target="#myModal1"  href="{{$urlshow}}/{{$order->id}}/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i>
                                        </a>
                                      </div>
                                      
                                    </li>
                                      @endif
                                    @endforeach
                      </ul>

      
                  </div>
                        <div class="row" style="margin-left:100px;"> 
                          <ul id="sortable3" style="width: 41%;margin-right: 100px;margin-bottom: 50px;" class="connectedSortable1 connectedSortable">
                                      <div class="row note unsortable" >
                                        <div class="col-lg-12">
                                          <div class="col-lg-6 p-1" style="margin-top: 8px ">
                                            <label for="" class="form-group"><b> Cancelled Orders</b></label>
                                          </div>
                                          <div class="col-lg-5 p-0" >
                                            <input type="number" id="cancelled_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" name="order_id">
                                          </div>
                                        </div>
                                        
                                      </div>

                                      @foreach($array_order as $order)
                                          @if($order->status =='Cancelled')
                                              <li class="ui-state-default" id="{{$order->id}}" >
                                                  <div style="text-align: left;" class="col-lg-7 p-0 light">
                                                          <div class="col-lg-5 p-0">
                                                            <h6><b> ID: {{$order->id}}</b></h6>
                                                            <h6><b>qty: </b> {{$order->qty_of_items}}</h6>
                                                          </div>
                                                          
                                                          <div class="col-lg-7 p-0">
                                                            <h6>{{$order->count_of_items}} <b>  Products </b></h6>
                                                            <h6>{{$order->total}} <b>LE</b></h6>
                                                          </div>
                                                  </div>
                                                   <div style="text-align:right;margin-top: 8px;" class="col-lg-5 p-0">
                                                         <a id="show" class="btn btn-primary btn-sm " title="show order" style="" href="{{$urlshow}}/{{$order->id}}"> <i class="fa fa-eye" aria-hidden="true"></i>
                                                          </a>
                                                          <a style="display: none;"  data-toggle="modal" id="invoice{{$order->id}}" data-target="#myModal3"  href="{{$urlshow}}/{{$order->id}}/payment?delivery_order_id={{$order_delivery_id}}" title="Invoice"  class="btn btn-warning  sales_invoice"><i class="fa fa-external-link-square"
                                                              aria-hidden="true"></i>
                                                          </a>

                                                          <a style="display: none;" data-toggle="modal" id="payment{{$order->id}}" data-target="#myModal1"  href="{{$urlshow}}/{{$order->id}}/payment?delivery_order_id={{$order_delivery_id}}" title="Payment" class="btn btn-success payment"><i class="fa fa-money" aria-hidden="true"></i>
                                                          </a>   
                                                   </div>
                                                 
                                                            
                                              </li>
                                          @endif
                                      
                                      @endforeach
                          </ul>

                    <ul style="width: 41%;" id="sortable4" class="connectedSortable">
                     

                      <div class="row note unsortable" id="unsort" >
                        <div class="col-lg-12">
                          <div class="col-lg-5 p-1" style="margin-top: 8px ">
                            <label for="" class="form-group"><b> Void Orders</b></label>
                          </div>
                          <div class="col-lg-6 p-0" >
                            <input type="number" id="void_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" name="order_id">
                          </div>
                        </div>
                      </div>


                            @forelse($array_order as $order)
                              @if($order->status == 'Void')
                              <!-- <div id="pending">   -->
                                      <li class="ui-state-default" id="{{$order->id}}" >

                                          <div style="text-align: left;" class="col-lg-7 p-0 light">
                                              <div class="col-lg-5 p-0">
                                                <h6><b> ID: {{$order->id}}</b></h6>
                                                <h6><b>qty: </b> {{$order->qty_of_items}}</h6>
                                              </div>
                                              
                                              <div class="col-lg-7 p-0">
                                                <h6>{{$order->count_of_items}} <b>  Products </b></h6>
                                                <h6>{{$order->total}} <b>LE</b></h6>
                                              </div>
                                            
                                          </div>

                                          <div style="text-align:right;margin-top: 8px;" class="col-lg-5 p-0">
                                              <a class="btn btn-primary btn-sm" title="show order" style="" id="show"  href="{{$urlshow}}/{{$order->id}}"> <i class="fa fa-eye" aria-hidden="true"></i>
                                              </a>
                                              </span>
                                              <a style="display: none;"  data-toggle="modal" id="invoice{{$order->id}}" data-target="#myModal3"  href="{{$urlshow}}/{{$order->id}}/payment?delivery_order_id={{$order_delivery_id}}"  title="Invoice" class="btn btn-warning btn-sm sales_invoice"><i class="fa fa-external-link-square"
                                              aria-hidden="true"></i>
                                              </a>

                                              <a style="display: none;" data-toggle="modal" id="payment{{$order->id}}" data-target="#myModal1"  href="{{$urlshow}}/{{$order->id}}/payment?delivery_order_id={{$order_delivery_id}}" title="Payment"  class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i>
                                              </a>    
                                          </div>
                                      </li>
                                            <!-- </div> -->
                                  @endif


                              @empty
                            @endforelse
                    </ul>
                  </div>
                  <div class="row"> 
                      <div class="col-lg-12">  
                        <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Package</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>

                                <!-- <th>Actions</th> -->
                            </tr>
                            </thead>


                        </table> 
                      </div>

                  </div>
                  <div class="row"> 
                    <div class="col-lg-4"> 
                      @foreach($activities as $activity)
                          <p style="font-size: 12px;">{{$activity->user}} {{$activity->status}} {{$activity->order_id}} at {{$activity->created_at}}</p>
                          <hr>  
                      @endforeach

                    </div>
                  </div>

                  

                     
              </div>
          </div>

                {!! Form::close() !!}
            </div>
        </div>


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

<script src="{{url('components/select2/dist/js/select2.js')}}"></script>
<script src="{{url('/public/')}}/prasley/parsley.js"></script>
<script src="{{url('public/admin/plugins/moment/moment.js')}}"></script>
<script src="{{url('public/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
{{--<script src="{{url('public/admin/pages/jquery.form-pickers.init.js')}}"></script>--}}
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

  <?php $deleteurl = url('/admin/delivery/man/delete'); ?>
  <?php $changestatus = url('/admin/branches'); ?>
  <?php $editurl = url('/admin/branches/');?>

  var table = $('#items_datatable').DataTable({
          processing: true,
          serverSide: true,
          ajax:{
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          },
          url:'{!! route('orderDetailsList') !!}',
          type:"GET",
          data: function(d){
              d.orders=<?php echo json_encode($orders_ids); ?>;
          }
          },
           dom: 'Bfrtip',
          lengthMenu: [[25, 100, -1], [25, 100, "All"]],
          pageLength: 25,
          buttons: [
                  {
                      extend: 'excel',
                      text: '<span class="fa fa-file-excel-o"></span> Excel Export',
                      exportOptions: {
                          modifier: {
                              search: 'applied',
                          }
                      }
                  }
              ],
          columns: [
              {data: 'order_id', name: 'order_id'},
              {data: 'customer', name: 'customer'},
              {data: 'product_name', name: 'product_name'},
              {data: 'package', name: 'package'},
              {data: 'qty', name: 'qty'},
              {data: 'rate', name: 'rate'},
              {data: 'total_amount', name: 'total_amount'},
          ]
      });




  <?php $deliver_order_url = url('admin/runsheet/orders/delivery-order');?>
  $(document).on('click','.unassign',function(event){
    id=$(this).attr('id');
    var order_id = this.id.match(/\d+/); // 123456    
    var delivery_order_id ='<?php echo $order_delivery_id?>;'; 
    <?php $changestatus = url('/admin/runsheet/order/status'); ?>
    $.ajax({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
        url: "{{$changestatus}}",
        type: "POST",
        data: {'unassign_order_id':order_id[0],'delivery_order_id':delivery_order_id},
        success: function (response) {
            // console.log(response);
        if(response=='true'){
          alert('This Order Un-Assigned Successfully');
          var total_order = $('#total_orders').val() ;
          total_order -= 1;
          location.reload(); // then reload the page.(3)
        }
                   
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Internal Error : Item is not delivered');
        }
    });
  });
  function sortable(){
    var sortable1 = [];
    var sortable2 = [];
    var sortable3 = [];
    var sortable4 = [];

     $(function() {
                $('#sortable1 li').each(function(){
                    sortable1.push($(this).attr('id')); 

                });

                $('#sortable2 li').each(function(){
                    id=$(this).attr('id');
                    $('#unassign'+id).hide();
                    // $('#sortable2 #show').css('margin-left','75px');
                    // $('#sortable2 #show').css('margin-bottom','10px');
                    if($('#invoice'+id).css('display','none')){
                        $('#invoice'+id).show();
                    }
                    if($('#payment'+id).css('display','none')){
                        $('#payment'+id).show();
                    }

                     sortable2.push($(this).attr('id'));
                 });
              
                $('#sortable3 li').each(function(){
                    id=$(this).attr('id');
                    // console.log(id);
                    $('#unassign'+id).hide();
                    // $('#sortable3 #show').css('margin-left','125px');
                    if($('#invoice'+id).css('display','block')){
                       $('#invoice'+id).hide();
                    }
                    if($('#payment'+id).css('display','block')){
                        $('#payment'+id).hide();
                    }
                    // $('#cancel'+id).remove();
                     sortable3.push($(this).attr('id'));
                 });
               
                $('#sortable4 li').each(function(){
                    id=$(this).attr('id');
                    // console.log(id);
                    $('#unassign'+id).hide();
                    // $('#sortable3 #show').css('margin-left','125px');
                    if($('#invoice'+id).css('display','block')){
                       $('#invoice'+id).hide();
                    }
                    if($('#payment'+id).css('display','block')){
                        $('#payment'+id).hide();
                    }
                    // $('#cancel'+id).remove();
                     sortable4.push($(this).attr('id'));
                 });
     
             
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    url: "{{$deliver_order_url}}",
                    type: "POST",
                    data: {'delivered':sortable2,'cancelled':sortable3,'void':sortable4 },
                    success: function (data) {
                    if(data=='cancel sales invoice first'){
                        alert('cancel sales invoice first');
                        location.reload(); // then reload the page.(3)
                    }
                    if(data == 'cancel sales invoice and payment first'){
                        alert('cancel sales invoice and payment first');
                        location.reload(); // then reload the page.(3)
                    }
                    document.getElementById("count_void").innerHTML = sortable4.length;
                    document.getElementById("count_cancelled").innerHTML = sortable3.length;
                    document.getElementById("count_delivered").innerHTML = sortable2.length;
                    document.getElementById("count_pending").innerHTML = sortable1.length;

                   
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Internal Error : Item is not delivered');
                    }
                });
                 // console.log('sort1 : '+sortable1 + '  Sort2 : '+sortable2+ ' sortable3 : '+sortable3);
             });
  }
        $("#sortable1 ,#sortable2").sortable({
            stop: function() {
            sortable();
            }
        });




    $('.payment').click(function (event){
        var order_delivery_id = <?php echo $order_delivery_id?>;
        var order_id = this.id.match(/\d+/); // 123456          
         $.ajax({
           method: 'GET',
           url: '{!! route('payment') !!}',
           data: {'order_id' : order_id,'order_delivery_id':order_delivery_id},
           success: function(response){
            // console.log(response);
            if(response == 'no sales invoice submitted' || response=='no sales invoice'){
                alert('Create Sales Invoice First');
                $('#myModal1').modal('hide');
                event.preventDefault();
            }
            if($('.modal_exist').length <= 0){
                $('#myModal1').append(response.data);
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

    $('.sales_invoice').click(function (event){
        var order_delivery_id = <?php echo $order_delivery_id?>;
        // console.log(order_delivery_id);
        var sales_order_id = this.id.match(/\d+/); // 123456   
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


    </script>
    <?php $canceledurl = url('/admin/runsheet/orders/cancel');?>
    <script>
     
     function get_pending_orders(){
                   var pending = [];
                   $('#sortable1 li').each(function(){
                        pending.push($(this).attr('id'));
                    });
                   return pending;
     }
     function get_delivered_orders(){
       var delivered = [];
       $('#sortable2 li').each(function(){
            delivered.push($(this).attr('id'));
        });
       return delivered;
     }
     function get_cancelled_orders(){     
              var cancelled = [];                
               $('#sortable3 li').each(function(){      
                 cancelled.push($(this).attr('id'));
               });
               return cancelled;
     }
 function get_void_orders(){     
          var void_orders = [];                
           $('#sortable4 li').each(function(){      
             void_orders.push($(this).attr('id'));
           });
           return void_orders;
 }

   var items = [];
   $(".searchorder").each(function(i, sel){
       var selectedVal = $(sel).attr('id');
       items.push(selectedVal);
   });
   $(document).on('input', '.searchorder', function(e){ 

       id=$(this).attr('id');
       $.each(items,function(index,value){
         if(value != id){

           $('#'+value).val('');
           $('#'+value).attr('placeholder','Filter By Order Id');
         }
       });
       if (typeof $('#'+id).val() !== "undefined"){
          order_id= document.getElementById(id).value;
       }     
       if(id == 'delivered_orders'){
         if (!$('#delivered_orders').value) {
             delivered = get_delivered_orders();
             delivered.clean(undefined);

             if(delivered.includes(order_id)){
                  sortable2_clear();
                  $('#delivered_orders').val(order_id);
                  $('#sortable2').append('<li class="ui-state-default" id="'+order_id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+order_id+'</b></h6><h6><b>qty: </b>@if(isset($qty_of_items)){{$qty_of_items}} @endif</h6></div><div class="col-lg-7 p-0"><h6>@if(isset($count_of_items)){{$count_of_items}} @endif<b>  Products </b></h6><h6> @if(isset($total)){{$total}} @endif <b>LE</b></h6></div></div><div style="text-align:right;margin-top: 8px;" class="col-lg-5 p-0"><a class="btn btn-primary btn-sm" style="" id="show" title="show order" href="{{$urlshow}}/"'+order_id+'"> <i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;<a style=""  data-toggle="modal" title="Invoice" id="invoice"'+order_id+'" data-target="#myModal3"  href="{{$urlshow}}/"'+order_id+'"/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-warning btn-sm  sales_invoice"><i class="fa fa-external-link-square"                    aria-hidden="true"></i></a>&nbsp;<a style="" title="Payment" data-toggle="modal" id="payment"'+order_id+'"" data-target="#myModal1"  href="{{$urlshow}}/"'+order_id+'"/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i></a></div> </li>');
             }     
           }        
        }else if(id =='pending_orders'){
             if (!$('#pending_orders').value) {
                 pending = get_pending_orders();
                 pending.clean(undefined);
                if(pending.includes(order_id)){
                   sortable1_clear();
                   $('#pending_orders').val(order_id);
                   $('#sortable1').append('<li class="ui-state-default" id="'+order_id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+order_id+'</b></h6><h6><b>qty: </b>@if(isset($qty_of_items)){{$qty_of_items}} @endif</h6></div><div class="col-lg-7 p-0"><h6>@if(isset($count_of_items)){{$count_of_items}} @endif<b>  Products </b></h6><h6>@if(isset($total)){{$total}} @endif <b>LE</b></h6></div></div><div style="text-align:right;margin-top: 8px;" class="col-lg-5 p-0"><button style="" title="un-assign order" id="unassign"'+order_id+'" class=" button btn btn-warning btn-sm unassign">Un-Assign</button><a class="btn btn-primary btn-sm" style="" title="show order" id="show"  href="{{$urlshow}}/"'+order_id+'"> <i class="fa fa-eye" aria-hidden="true"></i></a><a style="display:none"  data-toggle="modal" title="Invoice" id="invoice"'+order_id+'" data-target="#myModal3"  href="{{$urlshow}}/"'+order_id+'"/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-warning btn-sm  sales_invoice"><i class="fa fa-external-link-square"                    aria-hidden="true"></i></a><a style="display:none" title="Payment" data-toggle="modal" id="payment"'+order_id+'"" data-target="#myModal1"  href="{{$urlshow}}/"'+order_id+'"/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i></a></div></li>');
                 }  
             }
        }else if (id =='cancelled_orders'){
               if (!$('#cancelled_orders').value) {
                   cancelled = get_cancelled_orders();
                   cancelled.clean(undefined);
                   if(cancelled.includes(order_id)){
                       sortable3_clear();
                       $('#cancelled_orders').val(order_id);
                       $('#sortable3').append('<li class="ui-state-default" id="'+order_id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+order_id+'</b></h6><h6><b>qty: </b>@if(isset($qty_of_items)){{$qty_of_items}} @endif </h6></div><div class="col-lg-7 p-0"><h6>@if(isset($count_of_items)){{$count_of_items}} @endif <b>  Products </b></h6><h6>@if(isset($total)){{$total}} @endif  <b>LE</b></h6></div></div><div style="text-align:right;margin-top: 8px;" class="col-lg-5 p-0"><a class="btn btn-primary btn-sm" style="" id="show" title="show order" href="{{$urlshow}}/"'+order_id+'"> <i class="fa fa-eye" aria-hidden="true"></i></a><a style="display:none;"  data-toggle="modal" title="Invoice" id="invoice"'+order_id+'" data-target="#myModal3"  href="{{$urlshow}}/"'+order_id+'"/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-warning btn-sm  sales_invoice"><i class="fa fa-external-link-square"                    aria-hidden="true"></i></a><a style="display:none" data-toggle="modal" id="payment"'+order_id+'"" data-target="#myModal1" title="Payment" href="{{$urlshow}}/"'+order_id+'"/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i></a></div></li>');
                   }
           }
        }else if (id =='void_orders'){
               if (!$('#void_orders').value) {
                   void_orders = get_void_orders();
                   void_orders.clean(undefined);
                   if(void_orders.includes(order_id)){
                       sortable4_clear();
                       $('#void_orders').val(order_id);
                       $('#sortable4').append('<li class="ui-state-default" id="'+order_id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+order_id+'</b></h6><h6><b>qty: </b>@if(isset($qty_of_items)){{$qty_of_items}} @endif </h6></div><div class="col-lg-7 p-0"><h6>@if(isset($count_of_items)){{$count_of_items}} @endif <b>  Products </b></h6><h6>@if(isset($total)){{$total}} @endif  <b>LE</b></h6></div></div><div style="text-align:right;margin-top: 8px;" class="col-lg-5 p-0"><a class="btn btn-primary btn-sm" style="" id="show" title="show order" href="{{$urlshow}}/"'+order_id+'"> <i class="fa fa-eye" aria-hidden="true"></i></a><a style="display:none;"  data-toggle="modal" title="Invoice" id="invoice"'+order_id+'" data-target="#myModal3"  href="{{$urlshow}}/"'+order_id+'"/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-warning btn-sm  sales_invoice"><i class="fa fa-external-link-square"                    aria-hidden="true"></i></a><a style="display:none" data-toggle="modal" id="payment"'+order_id+'"" data-target="#myModal1" title="Payment" href="{{$urlshow}}/"'+order_id+'"/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i></a></div></li>');
                   }
                 }
        }       
        
   });
                   

   function sortable1_clear(){
     $("#sortable1").empty();
     $('#sortable1').html('<div class="row note unsortable" ><div class="col-lg-12"><div class="col-lg-5 p-1" style="margin-top: 8px "><label for="" class="form-group"><b> Pending Orders</b></label></div><div class="col-lg-6 p-0" ><input type="number" id="pending_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" ame="order_id"></div></div></div>');
   }
   function sortable2_clear(){
     $("#sortable2").empty();
     $('#sortable2').html('<div class="row note unsortable" ><div class="col-lg-12"><div class="col-lg-5 p-1" style="margin-top: 8px "><label for="" class="form-group"><b> Delivered Orders</b></label></div><div class="col-lg-6 p-0" ><input type="number" id="delivered_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" ame="order_id"></div></div></div>');
   }
   function sortable3_clear(){
     $("#sortable3").empty();
     $('#sortable3').html('<div class="row note unsortable" ><div class="col-lg-12"><div class="col-lg-6 p-1" style="margin-top: 8px "><label for="" class="form-group"><b> Cancelled Orders</b></label></div><div class="col-lg-5 p-0" ><input type="number" id="cancelled_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" ame="order_id"></div></div></div>');
   }

   function sortable4_clear(){
     $("#sortable4").empty();
     $('#sortable4').html('<div class="row note unsortable" ><div class="col-lg-12"><div class="col-lg-5 p-1" style="margin-top: 8px "><label for="" class="form-group"><b> Void Orders</b></label></div><div class="col-lg-6 p-0" ><input type="number" id="void_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" ame="order_id"></div></div></div>');
   }
   function list_clear(){
               sortable1_clear();
               sortable2_clear();
               sortable3_clear();
               sortable4_clear();
   }
     


   // when order_id inputs is empty  
   $(document).on('keyup','.searchorder',function(){
    var orders = <?php echo json_encode($array_order); ?>;
     if($(this).val().trim() == ''){
          if($(this).attr('id') == "cancelled_orders"){
            sortable3_clear();
              $.each(orders,function(index,value){
                if(value.status == 'Cancelled'){
                     $('#sortable3').append('<li class="ui-state-default" id="'+value.id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+value.id+'</b></h6><h6><b>qty: </b>'+value.qty_of_items+'</h6></div><div class="col-lg-7 p-0"><h6>'+value.count_of_items+'<b>  Products </b></h6><h6>'+value.total +'<b>LE</b></h6></div></div><div style="text-align:right;margin-top: 8px;" class="col-lg-5 p-0">'

                      +'<a class="btn btn-primary btn-sm" style="" id="show" title="show order"    href="{{$urlshow}}/"'+value.id+'/"> <i class="fa fa-eye" aria-hidden="true"></i></a><a style="display:none;"  data-toggle="modal" title="Invoice" id="invoice"'+value.id+'" data-target="#myModal3"  href="{{$urlshow}}/"'+value.id+'"/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-warning btn-sm  sales_invoice"><i class="fa fa-external-link-square"                    aria-hidden="true"></i></a>&nbsp;<a style="display:none" data-toggle="modal" id="payment"'+value.id+'"" data-target="#myModal1" title="Payment" href="{{$urlshow}}/"'+value.id+'"/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i></a></div></li>');
                }
         
              });
          }else if($(this).attr('id') == "pending_orders"){
            sortable1_clear();
            $.each(orders,function(index,value){
              if(value.status == 'Assigned'){
              $('#sortable1').append('<li class="ui-state-default" id="'+value.id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+value.id+'</b></h6><h6><b>qty: </b>'+value.qty_of_items+'</h6></div><div class="col-lg-7 p-0"><h6>'+value.count_of_items+'<b>  Products </b></h6><h6>'+value.total +'<b>LE</b></h6></div></div><div style="text-align:right;margin-top: 8px;" class="col-lg-5 p-0"><button style="" title="un-assign order" id="unassign"'+value.id+'" class=" button btn btn-warning btn-sm unassign">Un-Assign</button><a class="btn btn-primary btn-sm" style="" title="show order" id="show"  href="{{$urlshow}}/"'+value.id+'/"> <i class="fa fa-eye" aria-hidden="true"></i></a><a style="display:none"  data-toggle="modal" title="Invoice" id="invoice"'+value.id+'" data-target="#myModal3"  href="{{$urlshow}}/"'+value.id+'"/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-warning btn-sm  sales_invoice"><i class="fa fa-external-link-square"                    aria-hidden="true"></i></a><a style="display:none" data-toggle="modal" title="Payment" id="payment"'+value.id+'"" data-target="#myModal1"  href="{{$urlshow}}/"'+value.id+'"/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i></a></div></li>');
              }
            });
          }else if($(this).attr('id')=="delivered_orders"){
              sortable2_clear();
              // console.log(orders);
              // payment?delivery_order_id={{$order_delivery_id}}
              $.each(orders,function(index,value){
                if(value.status == 'Delivered'){
                  $('#sortable2').append('<li class="ui-state-default" id="'+value.id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+value.id+'</b></h6><h6><b>qty: </b>'+value.qty_of_items+'</h6></div><div class="col-lg-7 p-0"><h6>'+value.count_of_items+'<b>  Products </b></h6><h6>'+value.total+' <b>LE</b></h6></div></div><div style="text-align:right;margin-top: 8px;" class="col-lg-5 p-0"><a class="btn btn-primary btn-sm" style="" id="show" title="show order"  href="{{$urlshow}}/"'+value.id+'/"> <i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;<a style=""  data-toggle="modal" id="invoice"'+value.id+'" data-target="#myModal3" title="Invoice" href="{{$urlshow}}/"'+value.id+'/delivery_order_id={{$order_delivery_id}}"  class="btn btn-warning btn-sm  sales_invoice"><i class="fa fa-external-link-square"                    aria-hidden="true"></i></a>&nbsp;<a style="" data-toggle="modal" title="Payment" id="payment"'+value.id+'"" data-target="#myModal1"  href="{{$urlshow}}/"'+value.id+'/payment?delivery_order_id={{$order_delivery_id}}"   class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i></a></div> </li>');
                }
              });
          }else if($(this).attr('id') == "void_orders"){
              sortable4_clear();
                $.each(orders,function(index,value){
                  if(value.status == 'Void'){
                       $('#sortable4').append('<li class="ui-state-default" id="'+value.id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+value.id+'</b></h6><h6><b>qty: </b>'+value.qty_of_items+'</h6></div><div class="col-lg-7 p-0"><h6>'+value.count_of_items+'<b>  Products </b></h6><h6>'+value.total +'<b>LE</b></h6></div></div><div style="text-align:right;margin-top: 8px;" class="col-lg-5 p-0">'

                        +'<a class="btn btn-primary btn-sm" style="" id="show" title="show order"  href="{{$urlshow}}/"'+value.id+'/"> > <i class="fa fa-eye" aria-hidden="true"></i></a><a style="display:none;"  data-toggle="modal" title="Invoice" id="invoice"'+value.id+'" data-target="#myModal3"   href="{{$urlshow}}/"'+value.id+'/payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-warning btn-sm  sales_invoice"><i class="fa fa-external-link-square"                    aria-hidden="true"></i></a>&nbsp;<a style="display:none" data-toggle="modal" id="payment"'+value.id+'"" data-target="#myModal1" title="Payment" href="{{$urlshow}}/"'+value.id+'payment?delivery_order_id={{$order_delivery_id}}"  class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i></a></div></li>');
                  }
              
                });
         }
    }
   });




   Array.prototype.clean = function(deleteValue) {
     for (var i = 0; i < this.length; i++) {
       if (this[i] == deleteValue) {         
         this.splice(i, 1);
         i--;
       }
     }
     return this;
   };

</script>
  
<!-- JAVASCRIPT AREA -->
</body>
</html>