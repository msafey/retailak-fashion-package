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
                        Product Details
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

                <?php $changestatus = url('/admin/delivery/order/status');?>
                <?php $urlshow = url('/admin/delivery/orders/');?>


                <div class="card card-block">
                    <div style="margin-left: 13px;" class="card-text">


                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Name
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' style="width: 350px;max-width: 335px;"
                                               value="{{$product->name_en}} - {{$product->name}}" disabled="disabled"
                                               required name="name_en" class="form-control">
                                    </div>
                                </div>


                            </div>
                            <div class="col-lg-6">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Main Category
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' value="{{$product->itemGroup->name}}" disabled="disabled"
                                               required name="name_en" class="form-control">
                                    </div>
                                </div>


                            </div>


                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Description
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <textarea disabled="disabled" style="height: 100px;width: 335px;" class="form-control"
                                                  cols="30"
                                                  rows="7">@if(isset($product->description)){{$product->description}} - {{$product->description_en}}@endif</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">2nd Category
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' value="@if(isset($product->second_item_group)){{$product->seconditemGroup->name}} @endif"
                                               disabled="disabled" required name="name_en" class="form-control">

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6" style="margin-top: 20px;">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">UOM & Weight
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' value="{{$product->weight.' '.$product->uom}}"
                                               disabled="disabled" required name="name_en" class="form-control">

                                    </div>
                                </div>
                            </div>
                            <br>
                            @if(checkProductConfig('item_code'))
                            <div class="col-lg-6" style="display:none;margin-top: 20px">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">SKU
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' value="{{$product->item_code}}" disabled="disabled" required
                                               name="name_en" class="form-control">

                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>
                        @if(!checkProductConfig('foods'))
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-sm-4">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Brand
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text'
                                               value="@if(isset($product->brand_id) && isset($product->brand_name)){{$product->brand_name->name}} @endif"
                                               disabled="disabled" required name="name_en" class="form-control">

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif


                        <div class="row">
                            @if($product->is_bundle == 0 && checkProductConfig('maintaining_stocks')==true)

                                <div class="col-lg-6">
                                    <div class="col-sm-4">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Purchase Order
                                        </label>
                                    </div>
                                    <div class="col-sm-5" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id=''>
                                            <a href="{{url('/admin/purchase-orders?product_id='.$product->id)}}"
                                               class="btn btn-rounded btn-primary"" class="btn btn-primary">Purchase
                                            Order({{$product->count_of_orders}})</a>
                                        </div>
                                    </div>
                                </div>
                            @endif


                            @if($product->is_bundle == 1)
                                <div class="col-lg-6">
                                    <div class="col-sm-3">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Bundle
                                        </label>
                                    </div>
                                    <div class="col-sm-6" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id=''>
                                        <!-- <a href="{{url('/admin/products/'
                                            .$product->id.'/manage_bundle')}}" class="btn btn-primary">Manage Bundles</a> -->
                                            <button class="btn btn-primary" disabled="">Manage Bundle</button>
                                        <!-- <a href="{{url('/admin/product_bundle/manage_bundle?product='.$product->id)}}" class="btn btn-primary">Manage Bundles</a> -->
                                        </div>
                                    </div>


                                </div>
                            @else
                                <div class="col-lg-6" @if(!checkProductConfig('maintaining_stocks')) style="display: none;@endif">
                                    <div class="col-sm-3">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Stocks
                                        </label>
                                    </div>
                                    <div class="col-sm-6" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id=''>
                                            <a class="btn btn-primary"
                                               @if($product->has_variants !=1)
                                               href="{{url('/admin/products/'.$product->id.'/manage')}}"
                                               @else
                                               href="{{url('/admin/product-variations-details/'.$product->id.'/')}}"
                                                    @endif
                                            >Manage Stocks</a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>


                        <div class="row">
                            @if($product->is_bundle == 0 && checkProductConfig('maintaining_stocks') == true && $product->has_variants == 0)

                                <div class="col-lg-6">
                                    <div class="col-sm-4">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Total Stock Qty
                                        </label>
                                    </div>
                                    <div class="col-sm-6" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id=''>
                                            <input type='text' value="{{$product_stock_qty}}" disabled="disabled"
                                                   required class="form-control">
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-sm-4">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Selling Price
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' id="selling_price" value="{{$product->selling_price}}" disabled="disabled"
                                               required name="name_en" class="form-control">
                                    </div>
                                </div>
                            </div>
                        <!-- </div> -->
                        <!-- <div class="row"> -->
                            <div class="col-lg-6">
                                <div class="col-sm-4">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Item Price
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <a href="{{url('/admin/products/'
                                        .$product->id.'/standard-rate')}}" class="btn btn-danger">Manage Item Price</a>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <hr>

                        @if($product->is_bundle == 0
                        && checkProductConfig('variations') == false && checkProductConfig('maintaining_stocks') == true)
                            <div class="row">
                                @foreach($stocks_warehouse as $warehouse)
                                    <div class="col-lg-6">
                                        <div class="col-sm-6">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Stocks
                                                In {{$warehouse->warehouse_name}} Warehouse
                                            </label>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 0px">
                                            <div class='input-group ' style="display: inline;" id=''>
                                                <input type='text' value="{{$warehouse->projected_qty}}"
                                                       disabled="disabled" required class="form-control">

                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            </div>
                        @endif
                        @if(checkProductConfig('variations') == true && $product->has_variants == 1)
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="">Variations</label>
                                </div>
                            </div>

                            <?php $i = 0;?>
                            @foreach($childs_array as $key => $array)
                            <div class="row card card-block" style="border:1px solid;margin-bottom: 40px;">
                                @foreach($array as $data)
                                <div class="col-lg-12">
                                    <div class="col-lg-7">
                                        <div class="row" style="">
                                        <div class="col-sm-12">
                                            <div class="col-sm-6">
                                                <label for=""><span style="color: red"><b>#{{$data['product_name']}}</b></span></label><br>
                                                @if(isset($data['barcode']))
                                                <img  id="img{{$data['item_code']}}"
                                                style="height: 30px;max-height: 30px;min-height: 30px;"
                                                src="{{url('public/imgs/products/barcodes/'.$data['barcode'])}}"/>
                                                <button type="button" class="download_barcode btn btn-primary" id="{{$data['item_code']}}"
                                                        name="{{$product->name_en}}-{{$data['product_name']}}" style="margin-left: 10px;height: 31px;"
                                                        ><i class="fa fa-print"></i></button>
                                                @endif


                                            </div>
                                            <div class="col-sm-4" style="">
                                                <h6>Sku : {{$data['item_code']}}</h6>
                                                <label for="" style="">Selling Price : {{$data['selling_price']}} LE</label><br>
                                                <label for="" ><a href="{{url('/admin/products/'.$key.'/standard-rate')}}" class="btn btn-danger">Manage Price</a></label>
                                                <label for="" ><a href="{{url('/admin/products/'.$key.'/manage/price_rule')}}" class="btn btn-info">Price Rules</a></label>
                                            </div>


                                        </div>
                                        </div>
                                       <div class="row">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr style="">
                                                        <th>Warehouse</th>
                                                        <th>Stock Qty</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($data[$key] as $dat)
                                                    <tr>
                                                        <td>{{$dat->warehouse_name}}</td>
                                                        <td>{{$dat->projected_qty}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                       </div>

                                    </div>
                                    <div class="col-lg-5">
                                        <div id="myCarousel{{$i}}" class="carousel slide" data-ride="carousel">
                                            <!-- Indicators -->
                                            <?php $j = 0;?>
                                            <ol class="carousel-indicators">
                                                @if(is_array($data['images']) && count($data['images']))

                                                    @foreach($data['images'] as $img)
                                                        <li data-target="#myCarousel{{$i}}"
                                                            data-slide-to="{{$j}}"
                                                            @if($j==0) class="active" @endif></li>
                                                        <?php $j++;?>
                                                    @endforeach

                                                @endif
                                            </ol>
                                            <!-- Wrapper for slides -->
                                            <div class="carousel-inner">
                                                <?php $j = 0;?>
                                                @if(is_array($data['images']) && count($data['images']))
                                                    @foreach($data['images'] as $img)
                                                        <div @if($j ==0) class="carousel-item active"
                                                             @else class="carousel-item" @endif>
                                                            <img
                                                                    style="height: 200px;max-height: 200px;min-height: 200px;"
                                                                    src="{{$img}}"/>
                                                        </div>
                                                        <?php $j++;?>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <!-- Left and right controls -->
                                            <a class="left carousel-control" href="#myCarousel{{$i}}"
                                               data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-left"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="right carousel-control" href="#myCarousel{{$i}}"
                                               data-slide="next">
                                                <span class="glyphicon glyphicon-chevron-right"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++;?>
                                @endforeach
                            </div>
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
    $(document).on('click','.download_barcode',function(e){
        var attr_id = $(this).attr('id');
        var pname = $(this).attr('pname');
        var sellingPrice = $('#selling_price').val();
        var url = '<?php echo url('public/imgs/products/barcodes/') ?>';
        // console.log();

        var source = url+'/'+attr_id+'.png';
        console.log(PrintImage(source, attr_id,pname,sellingPrice));
        // ImagetoPrint();
    });

        function ImagetoPrint(source,attr_id,pname,sellingPrice)
    {
        return "<html><head><style>.paper { position:relative;  margin: 0.00in; padding: 0; left: 0.00in; top: 0.00in; width: 1.88in; height: 1.2in; max-height: 1.2in; font-size:12px;}</style></head><body style='height: 1.2in; max-height: 1.2in; background-color:rgba(0,0,0,0.7)'>" +
                "<page class='paper' ><div class='label' style='left:0.00in;top:0.1in;margin-left:0.0in; margin-top:0.25in; '><center>"+pname+"</center><center> <img  style='margin:0.05cm;width:90%;'  src="+source+" /></center><center>"+attr_id+"</center><center>"+sellingPrice+"EGP</center></div></page></body></html>";
    }

    function PrintImage(source,attr_id,pname,sellingPrice)
    {
        Pagelink = "about:blank";
        //var pwa = window.open(Pagelink, "_new");
        var pwa = window.open('', 'PRINT');
        //pwa.document.open();
        pwa.document.write(ImagetoPrint(source,attr_id,pname,sellingPrice));
        //pwa.document.close(); // necessary for IE >= 10
        pwa.print();
        pwa.focus(); // necessary for IE >= 10*/
        //pwa.document.print();
        pwa.document.close();
    }



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
    $(document).on('change','.qty',function(){
        $(this).closest('#total_amount').val($(this).val() *  $(this).closest('#total_amount').attr('value').val() );
    });

    $('#canceled').click(function () {
//             event.stopImmediatePropagation();
        cancel_record(orders_canceled);
        $("#delivered").unbind("click");
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
