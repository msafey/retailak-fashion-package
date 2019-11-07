<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
    @include('layouts.admin.scriptname_desc')

        </script>
    <
    !-- < script
    src = "//code.jquery.com/jquery-1.11.1.min.js" ></script>
    -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css"
          rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <!-- App Favicon -->
<!-- <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/> -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css"/>

    <script type="text/javascript"
            src="https://milankyncl.github.io/jquery-copy-to-clipboard/jquery.copy-to-clipboard.js"></script>


    <!-- Latest compiled and minified bootstrap-select CSS -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">


    {{-- Canceled Model--}}
    <div class="modal fade bs-example-modal-sm" id="myModal2" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true">Confirmation</i>

                </div>
                <div class="modal-body">
                    Are you Sure That You Want To Cancel This Orders ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel</button>

                    <a id="canceled" class="btn btn-sm btn-danger"
                       href="javascript:void(0)"
                       title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Cancel Orders</a>

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
                        Sales Orders
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        Sales Orders
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


                <div class="modal fade bs-example-modal-lg" id="AssignOrder" tabindex="-1" role="dialog"
                     aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myLargeModalLabel">Choose Runsheet</h4>
                            </div>
                            <div class="modal-body">
                                <h4 class="m-t-0 header-title"><b>Runsheets</b></h4>
                                <div class="row">
                                    <div class="col-sm-3" style="float:right;">
                                        <a href="{{url('/admin/runsheet/create')}}" target="_blank"
                                           class="btn btn-rounded btn-primary"><i
                                                    class="zmdi zmdi-plus-circle-o"></i> Add New RunSheet </a>
                                    </div>


                                </div>
                                <table id="users_datatable" class="table table-striped table-bordered"
                                       style="width:100%;">
                                    <thead>
                                    <tr>
                                        <th>select</th>
                                        <th>Runsheet ID</th>
                                    </tr>
                                    </thead>


                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>


                <?php $deliverdturl = url('/admin/delivery/orders/status');?>

                <?php $changestatus = url('/admin/delivery/order/status');?>
                <?php $urlshow = url('/admin/delivery/orders/');?>


                <div class="card card-block" style="width:82% ">
                    <div style="margin-left: 13px;width:92% " class="card-text">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">User Name
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' value="@if(isset($user->name)){{$user->name}} @endif"
                                               disabled="disabled" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">User Phone
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' value="@if(isset($user->phone)){{$user->phone}} @endif"
                                               disabled="disabled" class="form-control">
                                    </div>
                                </div>
                            </div>

                        <!--
                                <div class="col-lg-6">
                                    <div class="col-sm-3">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">User Email
                                        </label>
                                    </div>
                                    <div class="col-sm-6" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id=''>
                                            <input type='text' value="@if(isset($user->email)){{$user->email}} @endif" disabled="disabled"  class="form-control">
                                        </div>
                                    </div>
                                </div>
 -->
                        </div>
                        <hr>

                        <div class="row">

                            <div class="col-lg-12" style="margin-top: 20px;">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Address
                                    </label>
                                </div>
                                <div class="col-sm-7">
                                    <div class='input-group date' style="display: inline;" id=''>
                                    <!--  <input type='text' value="@if(isset($address->building_no)){{$address->building_no}} - @endif @if(isset($address->street)) {{$address->street}} Street - @endif   @if(isset($address->floor_no)) Floor No {{$address->floor_no}} - @endif @if(isset($address->apartment_no)) Apartment No {{$address->apartment_no}} - @endif  @if(isset($address->building_no))  District {{$address->district_id}} - @endif  @if(isset($address->city)) {{$address->city}} - @endif  @if(isset($address->country)) {{$address->country}} - @endif" disabled="disabled"  class="form-control"> -->
                                    </div>

                                    <div class='input-group date' style="display: inline;" id=''>
                                        <textarea disabled="disabled"
                                                  class="form-control">@if(isset($address->street) && isset($districts[$address->district_id]))  {{$address->title}} {{$address->street}}
                                            Street - District {{$districts[$address->district_id]}} @endif</textarea>
                                    </div>

                                </div>

                                <div class="col-sm-3"><label for="">Nearest landmark</label></div>
                                <div class="col-sm-7">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <textarea disabled="disabled"
                                                  class="form-control">@if(isset($address->nearest_landmark) ) {{$address->nearest_landmark}} @endif</textarea>
                                    </div>
                                </div>


                            </div>
                        </div>


                        <div class="row" style="display: none;">
                            <div class="col-lg-6" style="margin-top: 20px;">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Nearest Landmark
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <textarea value="" disabled="disabled"
                                                  class="form-control">@if(isset($address->nearest_landmark) ){{$address->nearest_landmark}}@endif </textarea>
                                    </div>
                                </div>


                            </div>

                        </div>


                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Date Of Order
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' value="{{$sales_order->date}}" disabled="disabled"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Payment Method
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' value="{{$sales_order->payment_method}} " disabled="disabled"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>


                        </div>
                        <hr>

                        <hr>
                        <div class="row">
                            @if(session()->has('message'))
                                <div class="alert alert-success">
                                    {{ session()->get('message') }}
                                </div>
                            @endif
                        </div>
                        <div class="row">

                            <form action="{{ url('admin/updateExternalReciept') }}" method="post">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label style=class="form-group" for="from">External Reciept id
                                            </label>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class='input-group date' style="display: inline;" id=''>
                                                <input type='text' name="reciept_id"
                                                       value="{{$sales_order->external_reciept_id}}"
                                                       class="form-control">
                                                <input type="hidden" name="orderId" value="{{ $sales_order->id }}">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-sm-3">
                                            <label style=class="form-group" for="order_status">Order Status
                                            </label>
                                        </div>

                                        <div class="col-sm-6">
                                            <select id="order_status" name="order_status" class="form-control">
                                                <option disabled data-foo="Select Status">Select Order Status</option>

                                                <option value="Confirmed"
                                                        @if($sales_order->status == "Confirmed") selected @endif>
                                                    Confirmed
                                                </option>
                                                <option value="ReadyToShip"
                                                        @if($sales_order->status == "ReadyToShip") selected @endif>Ready
                                                    To Ship
                                                </option>
                                                <option value="Cancelled"
                                                        @if($sales_order->status == "Cancelled") selected @endif>
                                                    Cancelled
                                                </option>
                                                <option value="Return"
                                                        @if($sales_order->status == "Return") selected @endif>Return
                                                </option>
                                                <option value="OnHold"
                                                        @if($sales_order->status == "OnHold") selected @endif>On Hold
                                                </option>
                                                <option value="Pendig"
                                                        @if($sales_order->status == "Pending") selected @endif>Pending
                                                </option>
                                                <option value="Exchange"
                                                        @if($sales_order->status == "Exchange") selected @endif>Exchange
                                                </option>
                                                <option value="Wait"
                                                        @if($sales_order->status == "Wait") selected @endif>Wait
                                                </option>
                                                <option value="Delivered"
                                                        @if($sales_order->status == "Delivered") selected @endif >
                                                    Delivered
                                                </option>
                                                <option value="outOfStock"
                                                        @if($sales_order->status == "outOfStock") selected @endif >Out
                                                    Of Stock
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <button type="submit" style="" class="btn btn-primary btn-sm">Update</button>

                            </form>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-sm-12">
                                    <label for="">Order Items</label>
                                    <table id="myTable" class=" table order-list card card-block"
                                           style="width: 900px;border: 1px solid;margin-left: 15px">
                                        <thead>
                                        <tr>
                                            <td class="col-sm-3">Items</td>
                                            <td class="col-sm-3">Quantity</td>
                                            <td class="col-sm-2">rate</td>
                                            <td class="col-sm-2">Total Amount</td>
                                        </tr>
                                        </thead>
                                        <tbody id="tblrownew0">
                                        <?php $i = 1;?>
                                        <?php $textItemsDetails = '';?>
                                        @foreach($item_details as $order_item)

                                            <?php $textItemsDetails .= "$order_item->item_name \r\n";?>
                                            <tr id="field{{$i}}">
                                                <td class="col-sm-3">
                                                    <div class='input-group date' id='' style="display: inline;">
                                                        <input type="text" class="form-control" disabled="disabled"
                                                               name="" value="{{$order_item->item_name}}">
                                                    </div>
                                                </td>
                                                <td class="col-sm-2">
                                                    <input type="number" disabled="disabled" min="1" name=""
                                                           value="{{$order_item->qty}}" id="qty"
                                                           class="form-control    qty"/>
                                                </td>
                                                <td class="col-sm-2">
                                                    <input disabled="disabled" value="{{$order_item->rate}}"
                                                           name="rate[]" id="rate" class="form-control"/>
                                                </td>
                                                <td class="col-sm-2">
                                                    <input type="number" disabled="disabled" min="0" step="0.01"
                                                           name="total_amount[]" value="{{$order_item->total_amount}}"
                                                           id="total_amount" class="form-control total"/>
                                                </td>
                                            </tr>
                                            <?php $i++;?>
                                        @endforeach

                                        @if(count($parent_array)>0)
                                            @foreach($parent_array as $key => $array)
                                                @foreach($array as $order_item)
                                                    <?php $qty = $order_item->qty;
                                                    ?>
                                                    @while($qty > 0)
                                                        <?php
                                                        $textItemsDetails .= " $order_item->item_name \r\n";
                                                        $qty -= 1;
                                                        ?>
                                                    @endwhile


                                                @endforeach
                                            @endforeach
                                        @endif

                                        &nbsp; &nbsp;
                                        <button id="copyItems" data-clipboard-text="{{ $textItemsDetails }}"
                                                style="margin:8px;" class="btn btn-primary"><i class="fa fa-copy"></i>
                                            Copy items to clipboard
                                        </button>
                                        <br>
                                        @if(count($parent_array)>0)
                                            @foreach($parent_array as $key => $array)
                                                <tr>
                                                    <td class="col-sm-4"><label>Variations Of ( <span
                                                                    style='color: red;'><b>'#{{$key}}'</b></span>
                                                            )</label></td>
                                                </tr>
                                                @foreach($array as $order_item)
                                                    <tr id="field{{$i}}">
                                                        <td class="col-sm-3">
                                                            <div class='input-group date' id=''
                                                                 style="display: inline;">
                                                                <input type="text" class="form-control"
                                                                       disabled="disabled" name=""
                                                                       value="{{$order_item->item_name}}">
                                                            </div>
                                                        </td>
                                                        <td class="col-sm-2">
                                                            <input type="number" disabled="disabled" min="1" name=""
                                                                   value="{{$order_item->qty}}" id="qty"
                                                                   class="form-control    qty"/>
                                                        </td>


                                                        <td class="col-sm-2">
                                                            <input disabled="disabled" value="{{$order_item->rate}}"
                                                                   name="rate[]" id="rate" class="form-control"/>
                                                        </td>
                                                        <td class="col-sm-2">
                                                            <input type="number" disabled="disabled" min="0" step="0.01"
                                                                   name="total_amount[]"
                                                                   value="{{$order_item->total_amount}}"
                                                                   id="total_amount" class="form-control total"/>
                                                        </td>
                                                    </tr>
                                                    <?php $i++;?>
                                                @endforeach
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-sm-12">
                                    <label for="">Order Returned Items</label>
                                    <table id="myTable" class="card card-block table order-list"
                                           style="width:900px;margin-left:15px;border: 1px solid;">
                                        <thead>
                                        <tr>
                                            <td class="col-sm-3">Items</td>
                                            <td class="col-sm-3">Quantity</td>
                                            <td class="col-sm-2">rate</td>
                                            <td class="col-sm-2">Total Amount</td>
                                        </tr>
                                        </thead>
                                        <tbody id="tblrownew0">
                                        <?php $i = 1;?>
                                        @foreach($returned_items as $order_item)
                                            <tr id="field{{$i}}">
                                                <td class="col-sm-3">
                                                    <div class='input-group date' id='' style="display: inline;">
                                                        <input type="text" class="form-control" disabled="disabled"
                                                               name="" value="{{$order_item->item_name}}">
                                                    </div>
                                                </td>
                                                <td class="col-sm-2">
                                                    <input type="number" disabled="disabled" min="1" name=""
                                                           value="{{$order_item->qty}}" id="qty"
                                                           class="form-control    qty"/>
                                                </td>
                                                <td class="col-sm-2">
                                                    <input disabled="disabled" value="{{$order_item->rate}}"
                                                           name="rate[]" id="rate" class="form-control"/>
                                                </td>
                                                <td class="col-sm-2">
                                                    <input type="number" disabled="disabled" min="0" step="0.01"
                                                           name="total_amount[]" value="{{$order_item->total_amount}}"
                                                           id="total_amount" class="form-control total"/>
                                                </td>
                                            </tr>
                                            <?php $i++;?>
                                        @endforeach
                                        @if(count($returned_parent_array)>0)
                                            @foreach($returned_parent_array as $key => $array)
                                                <tr>
                                                    <td class="col-sm-4"><label>Variations Of ( <span
                                                                    style='color: red;'><b>'#{{$key}}'</b></span>
                                                            )</label></td>
                                                </tr>
                                                @foreach($array as $order_item)
                                                    <tr id="field{{$i}}">
                                                        <td class="col-sm-3">
                                                            <div class='input-group date' id=''
                                                                 style="display: inline;">
                                                                <input type="text" class="form-control"
                                                                       disabled="disabled" name=""
                                                                       value="{{$order_item->item_name}}">
                                                            </div>
                                                        </td>
                                                        <td class="col-sm-2">
                                                            <input type="number" disabled="disabled" min="1" name=""
                                                                   value="{{$order_item->qty}}" id="qty"
                                                                   class="form-control    qty"/>
                                                        </td>
                                                        <td class="col-sm-2">
                                                            <input disabled="disabled" value="{{$order_item->rate}}"
                                                                   name="rate[]" id="rate" class="form-control"/>
                                                        </td>
                                                        <td class="col-sm-2">
                                                            <input type="number" disabled="disabled" min="0" step="0.01"
                                                                   name="total_amount[]"
                                                                   value="{{$order_item->total_amount}}"
                                                                   id="total_amount" class="form-control total"/>
                                                        </td>
                                                    </tr>
                                                    <?php $i++;?>
                                                @endforeach
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <label for="" style="float: right;">Total Returned {{$total_money_of_returned}}
                                            LE</label>
                                    </table>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    <!-- @if(count($returned_items) >0 && count($returned_parent_array)>0) -->

                    <!-- @endif -->


                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Shipping Rate
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text'
                                               value="@if(isset($shipping_role->shipping_rule_label)) {{$shipping_role->shipping_rule_label}} -@endif {{$shipping_rate}} LE"
                                               disabled="disabled" required name="name_en" class="form-control">
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="col-sm-3">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Grand Total
                                    </label>
                                </div>
                                <div class="col-sm-6" style="margin-top: 0px">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <input type='text' value="{{$grand_total_amount}}" disabled="disabled" required
                                               name="name_en" class="form-control">
                                    </div>
                                </div>
                            </div>

                            @if(isset($total_amount_after_discount) && (($sales_order->payment_method == 'CREDIT-CARD') || ($sales_order->payment_method == 'Credit Card')|| ($sales_order->payment_method == 'Credit Card ')))
                                <div class="col-lg-6">
                                    <div class="row m-0 p-0">
                                        <div class="col-sm-6">
                                            <label style="margin-bottom: 0;" class="form-group" for="from">Grand Total
                                                After
                                                Discount
                                            </label>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 0px">
                                            <div class='input-group date' style="display: inline;" id=''>
                                                <input type='text' value="0"
                                                       disabled="disabled" required name="standard_rate"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-lg-6">
                                    <div class="col-sm-6">
                                        <label style="margin-bottom: 0;" class="form-group" for="from">Grand Total After
                                            Discount
                                        </label>
                                        @if($total_amount_after_discount == $grand_total_amount)
                                            <a style="width: 100px;" href="#/" title="Total Amount After Discount"
                                               data-toggle="popover" data-trigger="focus" data-placement="top"
                                               data-content="There's no discount the Grand Total Amount = {{$grand_total_amount}} LE"><span
                                                        class="circle">?</span></a>
                                        @endif
                                    </div>
                                    <div class="col-sm-6" style="margin-top: 0px">
                                        <div class='input-group date' style="display: inline;" id=''>
                                            <input type='text' value="{{$total_amount_after_discount}}"
                                                   disabled="disabled" required name="standard_rate"
                                                   class="form-control">
                                        </div>
                                    </div>


                                </div>
                        </div>
                        @endif
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6">
                            <button class="btn btn-success" @if($sales_order->status !='Added') disabled
                                    @endif id="assign_orders">Assign Order
                            </button>

                            <button class="btn btn-danger"
                                    @if($sales_order->status =='void' || $sales_order->status =='Cancelled') disabled
                                    @endif data-toggle="modal" data-target="#myModal2" id="cancel_order">Cancel
                                Order
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-4">
                        <h2> Order Notes</h2>
                    </div>
                </div>
                <div class="row">

                    <div class="col-lg-6">
                        <div class="col-sm-3">
                            <label style=class="form-group" for="from">Add Note
                            </label>
                        </div>
                        <div class="col-sm-6">
                            <div class='input-group date' style="display: inline;" id=''>
                                <textarea class="form-control" name="note" id="note" cols="30" rows="5"></textarea>


                            </div>
                        </div>

                    </div>
                    <div class="col-lg-3">
                        <input type="button" value="Add" id="addNoteBtn" class="btn btn-success">
                    </div>
                </div>
                <div class="row">
                    <div id="newNotesContainer">

                    </div>

                    @if(isset($orderNotes))

                        @foreach($orderNotes as $orderNote)

                            <div class="col-sm-12 alert alert-warning">
                                <div class="col-sm-9">{{$orderNote->note}}</div>
                                <div class="col-sm-3">{{$orderNote->created_at}}</div>


                            </div>

                        @endforeach

                    @endif

                </div>


                <div class="row">
                    <div class="col-sm-6 col-sm-offset-4"><h2>Order History</h2></div>
                    @if(isset($orderHistoryArr))
                        @if(is_array($orderHistoryArr))
                            @foreach($orderHistoryArr as $orderHistory)

                                <div class="col-sm-12 alert alert-info">The order has
                                    been @if($orderHistory['status'] == "Pending") added by @else updated to
                                    ( {{$orderHistory['status']}} )
                                    by @endif    ( {{$orderHistory['user_type']}} {{$orderHistory['user_name']}} )
                                    With userName ( {{$orderHistory['user_name']}} ) at ( {{$orderHistory['time']}}
                                    )
                                </div>
                            <!-- <div class="col-sm-12 alert alert-info">The order has been @if($orderHistory['status'] == "Pending") added by @else updated to ( {{$orderHistory['status']}} ) by @endif    ( {{$orderHistory['user_type']}} {{$orderHistory['user_name']}} ) With the Id ( {{$orderHistory['user_id']}} ) at ( {{$orderHistory['time']}} )</div> -->

                            @endforeach
                        @endif
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

<script src="{{url('public/admin/plugins/moment/moment.js')}}"></script>
<script src="{{url('public/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
{{--<script src="{{url('public/admin/pages/jquery.form-pickers.init.js')}}"></script>--}}


<script>
    $('#assign_orders').on('click', function () {
        $('#AssignOrder').modal().show();
        var table = $('#users_datatable').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            order: [0, 'asc'],
            ajax: '{!! route('RunsheetList') !!}',
            columns: [
                {
                    data: 'runsheet_id', searchable: false, render: function (data, type, row) {
                        return "<input type='radio' name='runsheet' class='userRadio' value='" + data + " '>"
                    }
                },
                {data: 'runsheet_id', name: 'runsheet_id'},


            ],
            drawCallback: function () {
                $('.userRadio').change(function () {
                    $('#AssignOrder').modal().hide();
                    if ($('input[name=runsheet]:checked').length > 0) {
                        var selectedRunsheet = $(this).val();
                        var order = <?php echo $sales_order->id; ?>;
                        $.ajax({
                            url: "{!! route('assignOrder') !!}",
                            data: {runsheet_id: selectedRunsheet, order: order},
                            success: function (data) {
                                if (data == 'success') {
                                    $('#AssignOrder').modal().hide();
                                    location.reload();
                                } else {
                                    alert('failed to assign');
                                }

                            }
                        });
                        // $('#showUserEmail').show();
                        // $('#Cancel').modal('hide');
                    }
                });
            }
            //do whatever
        });

    });


</script>

<script>
    orders_canceled = [];
    <?php $canceledurl = url('/admin/sales_order/cancel');?>
    <?php $addnoteurl = url('/admin/order/' . $sales_order->id . '/note/add');?>
    $('#canceled').click(function () {
        orders_canceled = <?php echo $sales_order->id; ?>;
        cancel_record(orders_canceled);
    });
    $(document).ready(function () {
        $('.element').CopyToClipboard();
    });
    $(document).on('click', '#addNoteBtn', function () {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{$addnoteurl}}",
            type: "POST",
            data: {
                note: $('#note').val(),

            },
            success(response) {

                $('#newNotesContainer').append(
                    '<div class="col-sm-12 alert alert-warning"><div class="col-sm-9">' + response.noteText + '</div>' +
                    '<div class="col-sm-3">' + response.created_at + '</div></div>'
                );


            }

        });

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
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Internal Error : Item is not Canceled');
            }
        });

    }

    //   function copyToClipboard(element) {
    //    var $temp = $("<input>");
    //    $("body").append($temp);
    //    $temp.val($(element).html()).select();
    //    document.execCommand("copy");
    //    $temp.remove();
    //   }

    //   $(document).on('click',"#copyItems",function(){


    //       copyToClipboard($("#copyData").val());


    // /* Copy the text inside the text field */

    //   });


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
