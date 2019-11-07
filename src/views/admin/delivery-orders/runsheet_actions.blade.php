<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled and minified bootstrap-select CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <link rel="stylesheet" href="/resources/demos/style.css">
 --><style>
#sortable1{
    margin-left: 10px;
}
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
.light h6{
    font-weight: 400;
}
#sortable1 {height:300px; width:18%;}
#sortable1 {overflow:hidden; overflow-y:scroll;}

#sortable2 {height:300px; width:18%;}
#sortable2 {overflow:hidden; overflow-y:scroll;}


#sortable3 {height:300px; width:18%;}
#sortable3 {overflow:hidden; overflow-y:scroll;}


#sortable4 {height:300px; width:18%;}
#sortable4 {overflow:hidden; overflow-y:scroll;}


#sortable1 li, #sortable2 li,#sortable3 li,#sortable4 li {
  margin: 0 5px 5px 5px;
      padding: 0px 8px;
      font-size: 1.2em;
      height: 55px;
}
#show{
  margin-top: 7px;

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
  $( "#sortable1, #sortable3 #sortable4" ).sortable({
    connectWith: ".connectedSortable"
  }).disableSelection();
} );

  $( function() {
  $( "#sortable2, #sortable3" ).sortable({
    connectWith: ".connectedSortable1"
  }).disableSelection();

  // $("#sortable1, #sortable4").sortable({
//     connectWith: ".connectedSortable2"
//   }).disableSelection();
// } );

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

     <div class="content-page">
        <!-- Start content -->

    
        <div class="content">
            <div class="container">
              <div class="modal fade bd-example-modal-lg" id="myModal4" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

              </div>
              <div class="modal fade bd-example-modal-lg" id="myModal1" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

              </div>



              <div class="modal fade bd-example-modal-lg" id="myModal3" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">

              </div>
                <!-- Bread Crumb And Title Section -->
                <div style="width: 90%;margin-left: 10px;"> 
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
                @endcomponent      
              </div>

                <div class="card card-block" style="width: 93%;">
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
                    <div  class="card-text">
                          <div class="row"> 
                              <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3" style="width: 25%;">
                                  <div class="card-box tilebox-one">
                      
                                        <i class="ion-android-data pull-xs-right text-muted"></i>
                                        <h6 class="text-muted text-uppercase m-b-20">Count Pending Orders</h6>
                                        <h2 class="m-b-20" data-plugin="counterup" id="count_pending">0</h2>
                                  </div>
                              </div>

                              <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3" style="width: 25%;">
                                  <div class="card-box tilebox-one">
                                        <i class="ion-social-foursquare pull-xs-right text-muted"></i>
                                        <h6 class="text-muted text-uppercase m-b-20">Count Of Delivered</h6>
                                        <h2 class="m-b-20" data-plugin="counterup" id="count_delivered">0</h2>
                                  </div>
                              </div>

                                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3" style="width: 25%;">
                                  <div class="card-box tilebox-one">
                                        <i class="ion-close-circled pull-xs-right text-muted"></i>
                                        <h6 class="text-muted text-uppercase m-b-20">Count Of Cancelled</h6>
                                        <h2 class="m-b-20" data-plugin="counterup" id="count_cancelled">0</h2>
                                  </div>
                              </div>
                              <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3" style="width: 25%;">
                                  <div class="card-box tilebox-one">
                                    
                                        <i class="ion-pound pull-xs-right text-muted"></i>
                                        <h6 class="text-muted text-uppercase m-b-20">Count Of Void</h6>
                                        <h2 class="m-b-20" data-plugin="counterup" id="count_void">0</h2>
                                  </div>
                              </div>

                        </div> 
                          <div class="row"> 
                                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                                      <div class="card-box tilebox-one">
                                          <i class="icon-rocket pull-xs-right text-muted"></i>
                                          <h6 class="text-muted text-uppercase m-b-20">Total Orders</h6>
                                          <h2 class="m-b-20" data-plugin="counterup" id="total_order">0</h2>
                                     </div>
                                </div>
                                <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                                    <div class="card-box tilebox-one">

                                          <i class="icon-paypal pull-xs-right text-muted"></i>
                                          <h6 class="text-muted text-uppercase m-b-20">Total Money</h6>
                                          <h2 class="m-b-20" data-plugin="counterup" id="total_money">0</h2>
                                    </div>
                                </div>
                             
                        </div>                                          
                        

                      <div class="row">
                         <div class="col-sm-4">
                              <label for="" class="form-group"> Select Courier</label>
                             <select id="courier_id" name="courier_id" class="form-control" >
                                 <option  selected value="0"></option>
                                 <?php foreach ($couriers as $courier) { ?>
                                     <option @if(isset($_GET['courier_id']) && $_GET['courier_id'] == $courier->id)selected @endif value="{{$courier->id}}">{{$courier->name}}</option>
                                 <?php } ?>
                             </select>
                         </div>
                         <div class="col-sm-4">
                              <label for="" class="form-group"> Select Warehouse</label>
                             <select id="warehouse_id" name="warehouse_id" class="form-control" >
                                 <option  selected value="0" data-foo=""></option>
                                 <?php foreach ($warehouses as $warehouse) { ?>
                                     <option @if(isset($_GET['warehouse_id']) && $_GET['warehouse_id'] == $warehouse->id)selected @endif value="{{$warehouse->id}}">{{$warehouse->name_en}}</option>
                                 <?php } ?>
                             </select>
                         </div>

                         <div class="col-sm-4">
                              <label class="form-group" for=""> Select RunSheet</label>
                             <select id="run_sheet_id" name="run_sheet_id" class="form-control select2" >
                                 <option  selected value="0" data-foo=""></option>
                                 <?php foreach ($run_sheets as $run_sheet) { ?>
                                     <option @if(isset($_GET['run_sheet_id']) && $_GET['run_sheet_id'] == $run_sheet->id)selected @endif value="{{$run_sheet->id}}" data-foo="Run Sheet Of Id :{{$run_sheet->id}}">{{$run_sheet->id}}</option>
                                 <?php } ?>
                             </select>
                         </div>                      
                      </div>

                      <div class="row">
                          <div class="col-sm-4">
                              <label  class="form-group" for="from">From
                              </label>
                              <div class='input-group date' id='datetimepicker1'>
                                  <input type='text' name="sfrom" class="form-control" />
                                  <span class="input-group-addon">
                                  <span class="zmdi zmdi-calendar"></span>
                                  </span>
                              </div>
                          </div>
                          <div class="col-sm-4">
                              <label  class="form-group" for="to">To
                              </label>
                              <div class='input-group date' id='datetimepicker2'>
                                  <input type='text'  name="sto" class="form-control" />
                                  <span class="input-group-addon">
                                  <span class="zmdi zmdi-calendar"></span>
                                  </span>
                              </div>
                          </div>
                      </div>
   
                        <div class="row" style="margin-left:100px;margin-top: 50px;"> 

                            <?php $deliverdturl = url('/admin/runsheet/orders/status');?>
                            <?php $changestatus = url('/admin/runsheet/orders/status'); ?>
                            <?php $urlshow = url('/admin/runsheet/'); ?>
                              <ul style="width: 40%;margin-right: 100px;margin-bottom: 50px;" id="sortable1" class="connectedSortable connectedSortable2">
                                <div class="row note unsortable" id="unsort" >
                                  <div class="col-lg-12">
                                    <div class="col-lg-5 p-1" style="margin-top: 4px ">
                                      <label for="" class="form-group"><b> Pending Orders</b></label>
                                    </div>
                                    <div class="col-lg-6 p-0" >
                                      <input type="number" id="pending_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" name="order_id">
                                    </div>
                                  </div>
                                </div>


                                     
                              </ul>

                            <ul id="sortable2" style="width: 40%;" class="connectedSortable ">
                              <div class="row note unsortable" id="unsort" >
                                <div class="col-lg-12">
                                  <div class="col-lg-6 p-1" style="margin-top: 4px ">
                                    <label for="" class="form-group"><b> Delivery Orders</b></label>
                                  </div>
                                  <div class="col-lg-5 p-0" >
                                    <input type="number" id="delivered_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" name="order_id">
                                  </div>
                                </div>
                              </div>


                            </ul>

                            
                        </div>
                        <div class="row" style="margin-left: 100px;">
                          <ul id="sortable3" style="width: 40%;margin-right: 100px;" class="connectedSortable1 connectedSortable">
                            <div class="row note unsortable" id="unsort" >
                              <div class="col-lg-12">
                                <div class="col-lg-6 p-1" style="margin-top: 4px ">
                                  <label for="" class="form-group"><b> Cancelled Orders</b></label>
                                </div>
                                <div class="col-lg-5 p-0" >
                                  <input type="number" id="cancelled_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" name="order_id">
                                </div>
                              </div>
                            </div>
                          </ul>
                          <ul style="width: 40%;" id="sortable4" class="connectedSortable connectedSortable2">
                            <div class="row note unsortable" id="unsort" >
                              <div class="col-lg-12">
                                <div class="col-lg-5 p-1" style="margin-top: 4px ">
                                  <label for="" class="form-group"><b> Void Orders</b></label>
                                </div>
                                <div class="col-lg-6 p-0" >
                                  <input type="number" id="void_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" name="order_id">
                                </div>
                              </div>
                            </div>


                                 
                          </ul>
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

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="{{url('/public/admin/')}}/js/bootstrap-datetimepicker.js"></script>



    <script src="{{url('components/components/select2/dist/js/select2.js')}}"></script>

<script>
 



$(document).ready(function() {
    ajax_call_by_courier();
    $(function() {
        $('#datetimepicker1').datetimepicker({
          icons: {
            time: 'zmdi zmdi-time',
            date: 'zmdi zmdi-calendar',
            up: 'zmdi zmdi--up',
            down: 'zmdi zmdi--down',
            //previous: 'glyphicon glyphicon-chevron-left',
            previous: 'zmdi zmdi-backward',
            next: 'zmdi zmdi-right',
            today: 'zmdi zmdi-screenshot',
            clear: 'zmdi zmdi-trash',
            close: 'zmdi zmdi-remove'
           },
        });
        $('#datetimepicker2').datetimepicker({
            icons: {
                time: 'zmdi zmdi-time',
                date: 'zmdi zmdi-calendar',
                up: 'zmdi zmdi--up',
                down: 'zmdi zmdi--down',
                //previous: 'glyphicon glyphicon-chevron-left',
                previous: 'zmdi zmdi-backward',
                next: 'zmdi zmdi-right',
                today: 'zmdi zmdi-screenshot',
                clear: 'zmdi zmdi-trash',
                close: 'zmdi zmdi-remove'
            },
        });
        $('#run_sheet_id').select2({
                   matcher: matchCustom,
                   templateResult: formatCustom
        });
    });
});


  var items = [];
  $(".searchorder").each(function(i, sel){
      var selectedVal = $(sel).attr('id');
      items.push(selectedVal);
  });

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
               $('#sortable2').append('<li class="ui-state-default" id="'+order_id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+order_id+'</b></h6><h6><b>qty: </b>32526</h6></div><div class="col-lg-7 p-0"><h6>325325<b>  Products </b></h6><h6>52352 <b>LE</b></h6></div></div><div style="text-align:right;margin-top: 4px;" class="col-lg-5 p-0"><a class="btn btn-primary btn-sm" style="" id="show"  href="{{$urlshow}}/"'+order_id+'"> <i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;</div> </li>');    
          }     
        }        
     }else if(id =='pending_orders'){
          if (!$('#pending_orders').value) {
              pending = get_pending_orders();
              pending.clean(undefined);
             if(pending.includes(order_id)){
                sortable1_clear();
                $('#pending_orders').val(order_id);
                $('#sortable1').append('<li class="ui-state-default" id="'+order_id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+order_id+'</b></h6><h6><b>qty: </b>32526</h6></div><div class="col-lg-7 p-0"><h6>325325<b>  Products </b></h6><h6>52352 <b>LE</b></h6></div></div><div style="text-align:right;margin-top: 4px;" class="col-lg-5 p-0"><a class="btn btn-primary btn-sm" style="" id="show"  href="{{$urlshow}}/'+order_id+'"> <i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;</div> </li>');
              }  
          }
     }else if (id =='cancelled_orders'){
            if (!$('#cancelled_orders').value) {
                cancelled = get_cancelled_orders();
                cancelled.clean(undefined);
                if(cancelled.includes(order_id)){
                    sortable3_clear();
                    $('#cancelled_orders').val(order_id);
                    $('#sortable3').append('<li class="ui-state-default" id="'+order_id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+order_id+'</b></h6><h6><b>qty: </b>32526</h6></div><div class="col-lg-7 p-0"><h6>325325<b>  Products </b></h6><h6>52352 <b>LE</b></h6></div></div><div style="text-align:right;margin-top: 4px;" class="col-lg-5 p-0"><a class="btn btn-primary btn-sm" style="" id="show"  href="{{$urlshow}}/'+order_id+'"> <i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;</div> </li>');
                }
          }
    }else if(id =='void_orders'){
          if (!$('#void_orders').value) {
              void_orders = get_void_orders();
              void_orders.clean(undefined);
             if(void_orders.includes(order_id)){
                sortable4_clear();
                $('#void_orders').val(order_id);
                $('#sortable4').append('<li class="ui-state-default" id="'+order_id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+order_id+'</b></h6><h6><b>qty: </b>32526</h6></div><div class="col-lg-7 p-0"><h6>325325<b>  Products </b></h6><h6>52352 <b>LE</b></h6></div></div><div style="text-align:right;margin-top: 4px;" class="col-lg-5 p-0"><a class="btn btn-primary btn-sm" style="" id="show"  href="{{$urlshow}}/'+order_id+'"> <i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;</div> </li>');
              }  
          }
     }          
});
                

// when order_id inputs is empty  
$(document).on('keyup','.searchorder',function(){
  if($(this).val().trim() == ''){
    ajax_call_by_courier();                 
  }
});


  <?php $deliver_order_url = url('admin/runsheet/orders/delivery-order');?>


   function sortable1_clear(){
     $("#sortable1").empty();
     $('#sortable1').html('<div class="row note unsortable" ><div class="col-lg-12"><div class="col-lg-6 p-1" style="margin-top: 4px "><label for="" class="form-group"><b> Pending Orders</b></label></div><div class="col-lg-5 p-0" ><input type="number" id="pending_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" ame="order_id"></div></div></div>');
   }
   function sortable2_clear(){
     $("#sortable2").empty();
     $('#sortable2').html('<div class="row note unsortable" ><div class="col-lg-12"><div class="col-lg-6 p-1" style="margin-top: 4px "><label for="" class="form-group"><b> Delivered Orders</b></label></div><div class="col-lg-5 p-0" ><input type="number" id="delivered_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" ame="order_id"></div></div></div>');
   }
   function sortable3_clear(){
     $("#sortable3").empty();
     $('#sortable3').html('<div class="row note unsortable" ><div class="col-lg-12"><div class="col-lg-6 p-1" style="margin-top: 4px "><label for="" class="form-group"><b> Cancelled Orders</b></label></div><div class="col-lg-5 p-0" ><input type="number" id="cancelled_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" ame="order_id"></div></div></div>');
   }

   function sortable4_clear(){
     $("#sortable4").empty();
     $('#sortable4').html('<div class="row note unsortable" ><div class="col-lg-12"><div class="col-lg-6 p-1" style="margin-top: 4px "><label for="" class="form-group"><b> Void Orders</b></label></div><div class="col-lg-5 p-0" ><input type="number" id="void_orders" class="searchorder form-control"  placeholder="Filter By Order Id" style="" ame="order_id"></div></div></div>');
   }
   function list_clear(){
      sortable1_clear();
      sortable2_clear();
      sortable3_clear();
      sortable4_clear();
   }
     
  
  $('select').change(function () {
      ajax_call_by_courier();
    });


function ajax_call_by_courier(){
  courier_id =0;
  run_sheet_id = 0;
  if (typeof $('#courier_id').val() != "undefined"){
    courier_id= document.getElementById("courier_id").value;
  }
  if (typeof $('#warehouse_id').val() != "undefined"){
    warehouse_id= document.getElementById("warehouse_id").value;
  }

  if (typeof $('#run_sheet_id').val() != "undefined"){
    run_sheet_id= document.getElementById("run_sheet_id").value;
  }
  var count_pending = 0;
  var count_delivered = 0;
  var count_cancelled = 0;
  var count_void = 0;
  var total_money=0;
  var count_of_all = 0;
     $.ajax({
          method: 'GET',
          url: '{!! route('orders') !!}',
          data: {'courier_id' : courier_id,'run_sheet_id':run_sheet_id,'warehouse_id':warehouse_id},
          success: function(response){
            list_clear();
            $.each(response, function(index, value ) {
              var anchors = '<a class="btn btn-primary btn-sm" style="" id="show"  href="{{$urlshow}}/'+value.id+'"> <i class="fa fa-eye" aria-hidden="true"></i></a>&nbsp;<a style="margin-top: 7px;"  data-toggle="modal" id="invoice'+value.id+'" data-target="#myModal3"  href="{{$urlshow}}/'+value.id+'/payment?delivery_order_id=0"  title="Invoice" class="btn btn-warning btn-sm sales_invoice"><i class="fa fa-external-link-square"aria-hidden="true"></i></a>&nbsp;<a style="    margin-top: 7px;" data-toggle="modal"  id="payment'+value.id+'" data-target="#myModal1"  href="{{$urlshow}}/'+value.id+'/payment?delivery_order_id=1" title="Payment"  class="btn btn-success btn-sm payment"><i class="fa fa-money" aria-hidden="true"></i></a>'

              if(value.status == 'Delivered'){
               if($('#'+value.id).length <= 0){
                $('#sortable2').append('<li class="ui-state-default" id="'+value.id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+value.id+'</b></h6><h6><b>qty: </b>'+value.qty_of_items+'</h6></div><div class="col-lg-7 p-0"><h6>'+value.count_of_items+'<b>  Products </b></h6><h6>'+value.total+' <b>LE</b></h6></div></div><div style="text-align:right;margin-top: 4px;" class="col-lg-5 p-0">'+anchors+'</div></li>');
                    count_delivered ++;
                    total_money = total_money + value.total;

                }
              }else if(value.status == 'Cancelled'){
                  if($('#'+value.id).length <= 0){
                      $('#sortable3').append('<li class="ui-state-default" id="'+value.id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+value.id+'</b></h6><h6><b>qty: </b>'+value.qty_of_items+'</h6></div><div class="col-lg-7 p-0"><h6>'+value.count_of_items+'<b>  Products </b></h6><h6>'+value.total +'<b>LE</b></h6></div></div><div style="text-align:right;margin-top: 4px;" class="col-lg-5 p-0">'
                            +anchors+'</div></li>');
                      $("#payment"+value.id).css('display','none');
                      $("#invoice"+value.id).css('display','none');
                      count_cancelled ++;
                      total_money = total_money + value.total;

                  }
              }else if(value.status=='Assigned'){
                if($('#'+value.id).length <= 0){
                $('#sortable1').append('<li class="ui-state-default" id="'+value.id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+value.id+'</b></h6><h6><b>qty: </b>'+value.qty_of_items+'</h6></div><div class="col-lg-7 p-0"><h6>'+value.count_of_items+'<b>  Products </b></h6><h6>'+value.total +'<b>LE</b></h6></div></div><div style="text-align:right;margin-top: 4px;" class="col-lg-5 p-0">'+anchors+'</div></li>');   
                $("#payment"+value.id).css('display','none');
                $("#invoice"+value.id).css('display','none'); 
                count_pending ++;
                total_money = total_money + value.total;
   
               }
              }else if(value.status=='Void'){
                if($('#'+value.id).length <= 0){
                $('#sortable4').append('<li class="ui-state-default" id="'+value.id+'" ><div style="text-align: left;" class="col-lg-7 p-0 light"><div class="col-lg-5 p-0"><h6><b> ID: '+value.id+'</b></h6><h6><b>qty: </b>'+value.qty_of_items+'</h6></div><div class="col-lg-7 p-0"><h6>'+value.count_of_items+'<b>  Products </b></h6><h6>'+value.total +'<b>LE</b></h6></div></div><div style="text-align:right;margin-top: 4px;" class="col-lg-5 p-0">'+anchors+'</div></li>');   
                $("#payment"+value.id).css('display','none');
                $("#invoice"+value.id).css('display','none');   
                count_void ++;
                total_money = total_money + value.total;
 
               }
              }
              count_of_all = count_pending+count_delivered+count_cancelled+count_void;
              document.getElementById("count_void").innerHTML = count_void;
              document.getElementById("count_cancelled").innerHTML = count_cancelled;
              document.getElementById("count_delivered").innerHTML = count_delivered;
              document.getElementById("count_pending").innerHTML = count_pending;
              document.getElementById("total_order").innerHTML = count_of_all;
              document.getElementById("total_money").innerHTML = total_money;




            });
          },
          error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
          }
        });
}

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
            // $('#cancel'+id).hide();

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
            $('#unassign'+id).hide();
            $('show').css('margin-left','161px');
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
    });
  }

  $("#sortable1 ,#sortable2").sortable({
      stop: function() {
      sortable();
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



  //This Functions related to Select 2 data-foo 
  function stringMatch(term, candidate) {
      return candidate && candidate.toLowerCase().indexOf(term.toLowerCase()) >= 0;
  }

  function matchCustom(params, data) {
      // If there are no search terms, return all of the data
      if ($.trim(params.term) === '') {
          return data;
      }
      // Do not display the item if there is no 'text' property
      if (typeof data.text === 'undefined') {
          return null;
      }
      // Match text of option
      if (stringMatch(params.term, data.text)) {
          return data;
      }
      // Match attribute "data-foo" of option
      if (stringMatch(params.term, $(data.element).attr('data-foo'))) {
          return data;
      }
      // Return `null` if the term should not be displayed
      return null;
  }

  function formatCustom(state) {
      return $(
          '<div><div>' + state.text + '</div><div class="foo">'
              + $(state.element).attr('data-foo')
              + '</div></div>'
      );
  }


  $(document).on('click','.payment',function (event){
      var order_delivery_id = 0;
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
  });

  $(document).on('click','.sales_invoice',function (event){
      var order_delivery_id = 0;
      var sales_order_id = this.id.match(/\d+/); // 123456   
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
  
<!-- JAVASCRIPT AREA -->
</body>
</html>