<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
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

    {{-- Canceled Model--}}
    <div class="modal fade bs-example-modal-sm" id="myModal2" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                     <i class="fa fa-exclamation-triangle" aria-hidden="true">Confirmation</i>

                </div>
                <div class="modal-body">
                    Are you Sure That You Want To Canceled This Orders ?
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
                        Variations Stock Details
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

                <?php $deliverdturl = url('/admin/delivery/orders/status');?>

                <?php $changestatus = url('/admin/delivery/order/status'); ?>
                <?php $urlshow = url('/admin/delivery/orders/'); ?>


                <div class="card card-block">
                    <div style="margin-left: 13px;" class="card-text">

                                   @if($product->has_variants == 1)

                            @foreach($childs_array as $key => $array)
                                @foreach($array as $k)
                                    <!-- <label for=""></label> -->
                                    <div class="row" style="margin-left: 10px;">
                                        <div class="col-sm-3">
                                            <label for="">#<span id="" style="color: red">{{$k['product_name']}}</span></label>
                                        </div>

                                        <!-- <div class="col-sm-3" > -->
                                            <div class="col-sm-6" style="margin-top: 0px;">
                                                 <div class='input-group date' style="display: inline;" id=''>
                                                    <a href="{{url('/admin/products/'
                                                    .$key.'/manage')}}" style="float: right;"" class="btn btn-primary">Manage Stocks</a>
                                                </div>
                                            </div>
                                            <div class="col-sm-3" style="float: right;">  
                                                <input type="" disabled="" class="form-control" value="Total Stocks : {{$k['product_stock_qty']}}">
                                            </div>
                                        <!-- </div> -->

                                     </div>
                                        <hr>
                                    @foreach($k[$key] as $stock)
                                    <div class="row" style="margin-left: 30px;background-color: #fbfbfb;">
                                        <div class="col-lg-6">
                                            <div class="col-sm-6">
                                                <label style="margin-bottom: 0;" class="form-group" for="from">Stocks In {{$stock->warehouse_name}} Warehouse
                                                </label>
                                            </div>
                                            <div class="col-sm-6" style="margin-top: 0px">
                                                <div class='input-group ' style="display: inline;" id=''>
                                                        <input type='text' value="{{$stock->projected_qty}}" disabled="disabled" required  class="form-control">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                    <hr>
                                @endforeach
                            @endforeach
                        @endif


         


                    </div>

                </div>
            </div>

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


<script>
    orders = [];
    $(document).on('change', '.checkbox', function () {
        if (this.checked) {
            var id = $(this).val();

            orders.push(id);
        }
    });
            $('#delivered').click(function () {
            delivery_record(orders);

        });


//     function delivery_record(orders) {

//         $.ajax({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             url: "{{$deliverdturl}}",
//             type: "POST",
//             data: {'orders_ids': orders},


//             success: function (data) {
              
                  
// //                        location.reload(); // then reload the page.(3)
                
                
//             },
//             error: function (jqXHR, textStatus, errorThrown) {
//                 alert('Internal Error : Item is not delivered');
//             }
//         });

//     }


</script>

<?php $canceledurl = url('/admin/delivery/orders/cancel');?>
<script>
    orders_canceled = [];
    $(document).on('change', '.checkbox', function () {
        if (this.checked) {
            var id = $(this).val();

            orders_canceled.push(id);
            console.log(orders_canceled);
        }
    });

    $('#canceled').click(function () {
//             event.stopImmediatePropagation();
            cancel_record(orders_canceled);
              $( "#delivered").unbind( "click" );
        });

    function cancel_record(orders_canceled) {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{$canceledurl}}",
            type: "POST",
            data: {'caneceled_orders_ids': orders_canceled},


            success: function (data) {
                location.reload();
//                alert(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Internal Error : Item is not Canceled');
            }
        });

    }


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>