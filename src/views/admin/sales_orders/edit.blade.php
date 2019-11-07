<!DOCTYPE html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
    @include('layouts.admin.scriptname_desc')

    <style>
        .page-title-box {
            background-color: #ffffff;
            margin-left: -5px;
            width: 94%;
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script> -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" /> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <!-- App Favicon -->
<!-- <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/> -->

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css"/>


    <!-- Latest compiled and minified bootstrap-select CSS -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">


    <div class="modal" id="myModal" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Create User</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-group">
                        <label style="margin-bottom: 0;" class="form-group" for="from">Customer Name: </label>
                        <input required id="user_name" name="user_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label style="margin-bottom: 0;" class="form-group" for="from">Phone Number: </label>
                        <input required id="phone" name="phone" class="form-control">
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="add_user">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="myModal3" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true">User Cart</i>
                </div>
                <div class="modal-body">
                    <table id="myTablee" class=" table order-lists">
                        <thead>
                        <tr>
                            <td>Select Item</td>
                            <td>Items</td>
                            <td>Quantity</td>
                            <td>rate</td>
                            <td>Total Amount</td>
                        </tr>
                        </thead>
                        <tbody id="tblrownew55">
                        <!-- <tr id="fieldset">
                        </tr> -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <a id="canceled" class="btn btn-sm btn-danger"
                       href="javascript:void(0)"
                       title="Hapus"><i
                                class="glyphicon glyphicon-trash"></i> Cancelled Products</a>
                </div>
            </div>
        </div>
    </div>

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
                @endcomponent             <!--End Bread Crumb And Title Section -->


                {!! Form::open(['url' => ['/admin/sales-orders', $order->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block" style="width: 93%">

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
                        <div class="col-lg-6">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">User
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-8">
                                    <div class='input-group date' id='selected' style="display: inline;">
                                        <select required name="user_id" style="width:300px;" id="users"
                                                class="form-control  select2" style="">
                                            <option value="-1" disabled data-foo="Select User" selected>Select User
                                            </option>
                                            @foreach ($users as $user)
                                                <option value="{{$user->id}}" @if($order->user_id == $user->id)selected
                                                        @endif id="user{{$user->id}}"
                                                        @if(isset($sales_order_request) && $sales_order_request == $user->id)selected
                                                        @endif data-foo="{{$user->phone}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3" style="display: inline;">
                                <?php $adduser = url('/admin/users/create'); ?>
                                <!-- sales_order_request -->
                                    <input type="button" style="" class="btn btn-sm  btn-primary " data-toggle="modal"
                                           data-target="#myModal" id="create_user" value="Create User"/>
                                </div>
                            <!-- <a href="{{$adduser}}?sales_order_request=1"}
                                 class=" button btn btn-primary ">Create User
                              </a> -->
                                <!-- </div> -->
                                <!-- </div> -->
                            </div>
                        </div>
                        <div class="col-lg-6"
                             @if(Auth::guard('admin_user')->user()->hasRole('store_admin')) style="display:none;" @endif>
                            <label style="margin-bottom: 0;" class="form-group" for="from"><b>Address</b>
                            </label>
                            <div class="col-sm-12">
                                <div class="col-sm-8">
                                    <div class='input-group date' id='' style="display: inline;">
                                        <select required name="address_id" id="address" class="form-control">
                                            <option value="-1" disabled selected>Select Address</option>
                                        </select>
                                        <input type="text" hidden="hidden" id="district" name="district_id"/>
                                        <input type="text" hidden="hidden" id="token" name="token"/>
                                    </div>
                                </div>
                                <div class="col-sm-4" style="display: inline;">
                                    <?php $add_address = url('/admin/address/create'); ?>
                                    <a href="#"
                                       class=" button btn btn-primary " id="create_address">Create Address
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <label for="note">Note</label>
                            </div>
                            <div class="col-sm-12">
                                <textarea name="note" cols="30" rows="10">{{$order_item->note}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="col-sm-12">

                                <table id="myTable" class=" table order-list">
                                    <thead>
                                    <tr>
                                        <td>Items</td>
                                        <td>Quantity</td>
                                        <td>rate</td>
                                        <td>Total Amount</td>
                                        <td>Actions</td>
                                    </tr>
                                    </thead>
                                    <tbody id="tblrownew0">
                                    <?php $i = 1; ?>
                                    @foreach($item_details as $order_item)
                                        <tr id="{{$order_item->id}}" class="itemRow">
                                            <td class="col-sm-3">{{$order_item->name}}</td>
                                            <td class="col-sm-1"><input type="number" class="qtyInput"
                                                                        id="{{$order_item->id}}-qty" name="quantity[]"
                                                                        value="{{$order_item->qty}}"/></td>
                                            <td class="col-sm-2">{{$order_item->rate}}</td>
                                            <td class="col-sm-2"><input type="text" class="total"
                                                                        value="{{$order_item->qty*$order_item->rate}}"
                                                                        disabled="disabled"/></td>
                                            <td class="col-sm-2"></td>
                                        </tr>
                                    @endforeach
                                    {{--@foreach($item_details as $order_item)--}}

                                    {{--<tr id="field{{$i}}">--}}
                                    {{--<td class="col-sm-3">--}}
                                    {{--<div class='input-group date' id='' style="display: inline;">--}}
                                    {{--<select required name="items[]" style="width:300px;" id="items{{$i}}" class="form-control selectpickerr select2" style="" >--}}
                                    {{--<option value="-1" disabled  data-foo="Select Item" selected>Select Item</option>--}}
                                    {{--@foreach ($products as $product)--}}
                                    {{--<option value="{{$product->id}}" @if($order_item->item_id == $product->id)selected @endif data-foo="{{$product->name_en}}" id="option-{{$product->id}}">{{$product->name}}</option>--}}
                                    {{--@endforeach--}}
                                    {{--</select>--}}
                                    {{--</div>--}}
                                    {{--</td>--}}
                                    {{--<td class="col-sm-2">--}}

                                    {{--<input type="number"  min="1"    name="quantity[]" value="1" id="qty" value="{{$order_item->qty}}"  class="form-control qty"/>--}}
                                    {{--</td>--}}
                                    {{--<td class="col-sm-2">--}}
                                    {{--<input  value="{{$order_item->rate}}"   name="rate[]" id="rate" disabled="disabled"  class="form-control"/>--}}
                                    {{--</td>--}}
                                    {{--<td class="col-sm-2">--}}
                                    {{--<input type="number"  disabled="disabled"  min="0"  step="0.01" name="total_amount[]" value="{{$order_item->total_amount}}" id="total_amount" class="form-control total"/>--}}
                                    {{--</td>--}}
                                    {{--<td class="col-sm-2"><a class="deleteRow"></a>--}}

                                    {{--</td>--}}
                                    {{--</tr>--}}
                                    {{--@endforeach--}}

                                    {{--@if(count($parent_array)>0)--}}
                                    {{--@foreach($parent_array as $key => $array)--}}
                                    {{--<tr>--}}
                                    {{--<td class="col-sm-4"><label>Variations Of ( <span style='color: red;'><b>'#{{$key}}'</b></span> )</label></td>--}}
                                    {{--</tr>--}}
                                    {{--@foreach($array as $order_item)--}}
                                    {{--<tr id="field{{$i}}">--}}
                                    {{--<td class="col-sm-3">--}}
                                    {{--<div class='input-group date' id='' style="display: inline;">--}}
                                    {{--<select required name="items[]" style="width:300px;" id="items{{$i}}" class="form-control selectpickerr select2" style="" >--}}
                                    {{--<option value="-1" disabled  data-foo="Select Item" selected>Select Item</option>--}}
                                    {{--@foreach ($products as $product)--}}
                                    {{--<option value="{{$product->id}}" @if($order_item->item_id == $product->id)selected @endif data-foo="{{$product->name_en}}" id="option-{{$product->id}}">{{$product->name}}</option>--}}
                                    {{--@endforeach--}}
                                    {{--</select>--}}
                                    {{--</div>--}}
                                    {{--</td>--}}
                                    {{--<td class="col-sm-2">--}}

                                    {{--<input type="number"  min="1"    name="quantity[]" value="1" id="qty" value="{{$order_item->qty}}"  class="form-control qty"/>--}}
                                    {{--</td>--}}
                                    {{--<td class="col-sm-2">--}}
                                    {{--<input  value="{{$order_item->rate}}"   name="rate[]" id="rate" disabled="disabled"  class="form-control"/>--}}
                                    {{--</td>--}}
                                    {{--<td class="col-sm-2">--}}
                                    {{--<input type="number"  disabled="disabled"  min="0"  step="0.01" name="total_amount[]" value="{{$order_item->total_amount}}" id="total_amount" class="form-control total"/>--}}
                                    {{--</td>--}}
                                    {{--<td class="col-sm-2"><a class="deleteRow"></a>--}}

                                    {{--</td>--}}
                                    {{--</tr>--}}
                                    {{--@endforeach--}}
                                    {{--@endforeach--}}
                                    {{--@endif--}}

                                    <!-- <hr> -->
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>


                <!-- <div class="row">
                            <div class="col-lg-12">
                              <div class="col-sm-12">

                            <table id="myTable"  class=" table order-list">
                            <thead>
                                <tr>
                                    <td>Items</td>
                                    <td>Quantity</td>
                                    <td>rate</td>
                                    <td>Total Amount</td>
                                    <td colspan="1" style="text-align: right;float: center;margin-top: 10px;padding-right: 150px;">
                                        <input type="button" class="btn btn-lg btn-primary " id="addrow" value="+" />
                                    </td>
                                </tr>
                            </thead>
                            <tbody id="tblrownew0" >
                                <?php $i = 1; ?>
                @forelse($item_details as $order_item)
                    <tr id="field{{$i}}">
                                    <td class="col-sm-3">
                                      <div class='input-group date' id='' style="display: inline;">
                                             <select required name="items[]" style="width:300px;" id="items1" class="form-control selectpickerr select2" style="" >
                                              <option value="-1" disabled  data-foo="Select Item" selected>Select Item</option>
                                                @foreach ($products as $product)
                        <option value="{{$product->id}}" @if($order_item->item_id == $product->id)selected @endif  data-foo="{{$product->name_en}}" id="option-{{$product->id}}">{{$product->name}}</option>
                                              @endforeach
                            </select>
                    </div>
                  </td>
                  <td class="col-sm-2">

                      <input type="number"  min="1"    name="quantity[]" value="{{$order_item->qty}}" id="qty"  class="form-control qty"/>
                                    </td>
                                    <td class="col-sm-2">
                                        <input  readonly="readonly" value="{{$order_item->rate}}"   name="rate[]" id="rate"   class="form-control"/>
                                    </td>
                                    <td class="col-sm-2">
                                        <input type="number"  disabled="disabled"  min="0"  step="0.01" name="total_amount[]" value="{{$order_item->total_amount}}"    id="total_amount" class="form-control total"/>
                                    </td>
                                    <td class="col-sm-2"><a class="deleteRow"></a>

                                    </td>
                                </tr>
                                @empty

                    <tr id="field1">
                        <td class="col-sm-3">
                          <div class='input-group date' id='' style="display: inline;">
                                 <select required name="items[]" style="width:300px;" id="items1" class="form-control selectpickerr select2" style="" >
                                              <option value="-1" disabled  data-foo="Select Item" selected>Select Item</option>
                                                @foreach ($products as $product)
                        <option value="{{$product->id}}"  data-foo="{{$product->name_en}}" id="option-{{$product->id}}">{{$product->name}}</option>
                                              @endforeach
                            </select>
                    </div>
                  </td>
                  <td class="col-sm-2">

                      <input type="number"  min="1"    name="quantity[]" value="1" id="qty"  class="form-control qty"/>
                                    </td>
                                    <td class="col-sm-2">
                                        <input  disabled="disabled" value="0.00"    name="rate[]" id="rate"   class="form-control"/>
                                    </td>
                                    <td class="col-sm-2">
                                        <input type="number"  disabled="disabled"  min="0"  step="0.01" name="total_amount[]" value="0.00"    id="total_amount" class="form-control total"/>
                                    </td>
                                    <td class="col-sm-2"><a class="deleteRow"></a>

                                    </td>
                                </tr>

                                @endforelse
                        <hr>
                    </tbody>
                    <tfoot>
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <br>

                        </tr>
                        <tr>
                        </tr>
                    </tfoot>
                    </table>
                    <hr>

                    </div>
                    </div>
              </div>
-->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">Shipping Rate
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <input type="number" readonly="readonly" class="form-control" id="shipping_rate"
                                       name="shipping" @if($freeShipping ==0) value="{{$shipping_rate}}"
                                       @else value="0" @endif >
                            </div>
                        </div>

                    </div>
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">PromoCode
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <input type="text" id="promocode" class="form-control promocode" name="promocode"
                                       @if(isset($promo_code)) readonly="readonly" value="{{$promo_code}}" @endif>

                            </div>
                        </div>


                        <div class="col-lg-6" id="validate" @if(!isset($promo_code)) style="display: none;@endif">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">PromoCode Validate
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <input type="text" value="Promocode is valid" id="promocode_response"
                                       disabled="disabled" class="form-control promocode" name="promocode" value="">

                            </div>
                        </div>
                    </div>


                    <input type="text" hidden="hidden" name="sales_order_request"
                           value="@if(isset($sales_order_request)){{$sales_order_request}}@endif">

                    <div class="row">
                        <div class="col-lg-6"
                             style="left:20px;width: 480px;right: 30px; ; height:150px;padding:10px;border: 2px solid gray; margin-top: 100px;">

                            <div class="col-lg-12"
                                 style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Grand
                                    Total With Shipping Fees
                                </label>
                            </div>
                            <div class="col-lg-12" style="margin-top: 0">
                                <div class='input-group' id='' style="display: inline;  text-align: right">
                                    <input readonly type="text" name="grand_total_amount"
                                           style="height: 40px;text-align: center; " id="grand_total"
                                           class="form-control" value="0">

                                </div>
                            </div>


                        </div>
                        <div class="col-lg-6" id="discount_block"
                             style="display:none;right:-30px;width: 480px ; height:150px;border: 2px solid gray; margin-top: 100px;">

                            <div class="col-lg-12"
                                 style="margin-top: 25px;display: block;text-align: center;line-height: 150%;font-size: 1.2em;">
                                <label style="margin-bottom: 0;direction: center" class="form-group" for="from">Total
                                    After Promo code Discount
                                </label>
                            </div>
                            <div class="col-lg-12" style="margin-top: 0">
                                <div class='input-group' id='' style="display: inline;  text-align: right">
                                    <input readonly type="text" name="total_amount_after_discount"
                                           style="height: 40px;text-align: center; " id="total_amount_after_discount"
                                           class="form-control" value="0">

                                </div>
                            </div>


                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-32">
                            <button type="submit" id="save" style="margin-left: 12px" class="btn btn-primary">Save
                            </button>
                            <!-- <input type="button" class="btn  btn-warning " data-toggle="modal" data-target="#myModal3" id="mycart" value="User Cart" /></div> -->
                            <div class="col-sm-32">
                            </div>
                        </div>


                    </div>

                    {!! Form::close() !!}

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
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script src="{{url('components/components/select2/dist/js/select2.js')}}"></script>

<script type="text/javascript">
    var response_array = [];
    var items_array = <?php echo json_encode($products); ?>;
    var selected = [];
    var items_options = [];
    var checkout;

    var selected_option = $(".selectpickerr:first").attr('id');


    $(document).on('click', '#add_user', function () {
        let name = $('#user_name').val();
        let phone = $('#phone').val();
        if (name == '' || phone == '') {
            alert('please complete required data');
            return false;
        } else {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: '{!! route('createUser') !!}',
                type: "POST",
                data: {'name': name, 'phone': phone},
                success: function (response) {
                    if (response == 'user_exists') {
                        alert('User already exists');
                        $('#myModal').modal('hide');
                    } else if (response == 'invalid_phone_format') {
                        alert('enter valid phone number');
                        return false;
                    } else if (response == 'false') {
                        alert('something went wrong, try again later.');
                        $('#myModal').modal('hide');
                    } else {
                        var optionExists = ($('#user' + response.id).length > 0);
                        if (!optionExists) {
                            $('#users').append('<option selected  value="' + response.id + '" id="user' + response.id + '" data-foo="' + response.phone + '">' + response.name + '</option>').change();
                        }
                        var addressExists = ($('#address option[value=' + response.address_id + ']').length > 0);
                        if (!optionExists) {
                            $('#address').html('<option selected id="option-' + response.address_id + '"  value="' + response.address_id + '">' + response.street + '</option>').change();
                        }
                        ;
                        $('#district').val(response.district);
                        $('#token').val(response.token);
                        $('#myModal').modal('hide');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Internal Error : User Not Created');
                }
            });
        }
    });
    $(document).on('input', '.promocode', function (e) {
        var promocode = $(this).val();
        var token = $('#token').val();
        promocode_ajax(token, promocode);
    });

    $(document).on('keyup', '.promocode', function () {
        var shipping = <?php echo $shipping_rate; ?>;
        $('#shipping_rate').val(shipping);
        $('#discount_block').css('display', 'none');
    });

    function promocode_ajax(token, promocode) {
        $.ajax({
            headers: {
                'token': token,
                'lang': 'en',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'GET',
            url: '{!! route('getPromoCodeDetails') !!}',
            data: {'promocode': promocode},
            success: function (response) {
                if (!$('#promocode').val()) {
                    $('#validate').css('display', 'none');
                } else {
                    $('#validate').css('display', 'block');
                }

                var obj = jQuery.parseJSON(response);
                // console.log(response);
                if (typeof obj.discount_rate !== "undefined") {
                    $('#promocode_response').val('Promocode is valid');
                    var total_amount = $('#grand_total').val();

                    if (obj.has_free_shipping == 1) {
                        grandTotalAmount();
                        total_amount = total_amount - shipping_rule_rate;
                        $('#shipping_rate').val(0);
                        // var shipping_rule_rate = $('#shipping_rate').val();
                        total_amount = total_amount - obj.discount_rate;
                        $('#total_amount_after_discount').val(total_amount);
                        $('#discount_block').css('display', 'block');

                        // console.log(total_amount);
                    } else {
                        grandTotalAmount();

                        total_amount = total_amount - obj.discount_rate;
                        $('#total_amount_after_discount').val(total_amount);
                    }
                } else {
                    if ($('#users option:selected').val() == -1) {
                        $('#promocode_response').val('Select User First');
                    } else {
                        $('#promocode_response').val('Invalid Code')
                    }
                }


            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                // console.log(JSON.stringify(jqXHR));
                // console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    }

    $(document).ready(function () {
        grandTotalAmount();
        $('#users').select2({
            disabled: true,
            matcher: matchCustom,
            templateResult: formatCustom
        });
        $('#users').attr('disabled', true);

        $('#items1').select2({
            matcher: matchCustom,
            templateResult: formatCustom
        });
        if ($('#items1').val()) {
            $('#items1').attr('disabled', true);
        }

        if ($('#promocode').val() != '') {
            // console.log(32);
            totalAmountAfterDiscount();
            $('#discount_block').css('display', 'block');
        }
        // setInterval(promocode_ajax(token,promocode), 5000);
        userAddress();
    });

    // all products
    $.each(items_array, function (index, value) {
        items_options.push('<option id="option-' + value.id + '" data-foo="' + value.name_en + '" value="' + value.id + '">' + value.name + '</option>');
    });

    if (selected_option === undefined) {
        selected_option = $(".selectpickerr:last").attr('id');
    }

    function totalAmountAfterDiscount() {
        var discount_amount = '<?php echo $discount_amount;?>';
        var discount_type = '<?php echo $discount_type ?>';
        var shipping = '<?php echo $freeShipping; ?>';
        var rate_of_shipping = '<?php echo $shipping_rate; ?>';

        grandTotalAmount();

        var total_price = $('#grand_total').val() - rate_of_shipping;

        if (discount_type == "rate") {
            total_price_discount = total_price * discount_amount / 100;
            total_price = total_price - total_price_discount;
        } else if (discount_type == "amount") {
            total_price = total_price - discount_amount;
        }
        if (shipping == 0) {
            total_price = total_price + rate_of_shipping;
        }
        if (total_price > 0) {
            $('#total_amount_after_discount').val(total_price);
        } else {
            $('#total_amount_after_discount').val(0);

        }
    }


    function userAddress() {
        if ($('#users').val() == null) {
            return false;
        }
        var id = $('#users').val();
        <?php  $address_url = url('/admin/address/create'); ?>
        $("#create_address").attr('href', '{{$address_url}}' + '?id=' + id);
        $.ajax({
            method: 'GET',
            url: '{!! route('getUserAddress') !!}',
            data: {'id': id},
            success: function (response) {
                var cart = [];
                cart = response.item_details;
                if (cart != undefined) {
                    $.each(cart, function (index, value) {
                        var newRow = $("<tr id='fieldset" + value.item_id + "'>");
                        var cols = "";
                        var id = $('#tblrownew55 tr:last').attr('id');
                        cols += '<td class="col-sm-1"><input  type="checkbox"  name="items_cancel[]" style="" value="' + value.item_id + '"  id="cancel-"' + value.item_id + '" class="form-control checkbox" /></td>';
                        cols += '<td class="col-sm-3"><input disabled name="items[]" style="width:300px;" value="' + value.item_name + '"  id="" class="form-control" /><input readonly hidden name="items[]" style="width:300px;" value="' + value.item_id + '"  id="" class="form-control" /></td>';
                        cols += '<td class="col-sm-2"><input type="number" disabled class="form-control qty" min="1" value="' + value.qty + '" id="qty" name="quantity[]"/></td>';
                        cols += '<td class="col-sm-2"><input type="number" class="form-control" min="0" value="' + value.rate + '" step="0.01"  readonly id="rate" name="rate[]"/></td>';
                        cols += '<td class="col-sm-2"><input type="number" class="form-control total" min="0" value="' + value.total_amount + '" step="0.01" disabled id="" name="total_amount[]"/></td>';
                        newRow.append(cols);
                        $("table.order-lists").append(newRow);
                    });
                }
                var variant_products = response.variant_products;
                if (variant_products != undefined) {
                    var products = [];
                    $.each(variant_products, function (index, value) {
                        var parent = index;
                        var optionExists = ($('#' + index).length > 0);
                        if (!optionExists) {
                            var newRow = $("<tr style='margin-bottom:10px;margin-top:20px;' id='" + index + "'>");
                            var cols = "";
                            cols += '<td class="col-sm-1"><input  type="checkbox"  name="items_cancel[]" style="" id="' + index + '1" class="form-control checkbox parent" disabled /></td>';
                            cols += "<td><label>Variations Of ( <span style='color: red;'><b>'#" + index + "'</b></span> )</label></td>";
                            newRow.append(cols);
                            $("table.order-lists").append(newRow);
                        }
                        products = value;
                    });
                    $.each(products, function (index, value) {
                        var newRow = $("<tr class='" + value.parent_name + "' style='background-color: #fbfbfb;' id='fieldset" + value.item_id + "'>");
                        var cols = "";
                        cols += '<td class="col-sm-1"><input  type="checkbox"  name="items_cancel[]" style="" value="' + value.item_id + '"  id="cancel-"' + value.item_id + '" class="form-control checkbox" /></td>';
                        cols += '<td class="col-sm-3"><input disabled name="items[]" style="width:300px;" value="' + value.item_name + '"  id="" class="form-control" /><input readonly hidden name="items[]" style="width:300px;" value="' + value.item_id + '"  id="" class="form-control" /></td>';
                        cols += '<td class="col-sm-2"><input type="number" disabled class="form-control qty" min="1" value="' + value.qty + '" id="qty" name="quantity[]"/></td>';
                        cols += '<td class="col-sm-2"><input type="number" class="form-control" min="0" value="' + value.rate + '" step="0.01"  readonly id="rate" name="rate[]"/></td>';
                        cols += '<td class="col-sm-2"><input type="number" class="form-control total" min="0" value="' + value.total_amount + '" step="0.01" disabled id="" name="total_amount[]"/></td>';
                        newRow.append(cols);
                        $("table.order-lists").append(newRow);
                    });
                }
                var selected_user_address_id = '<?php echo $selected_user_address_id;?>';
                $.each(response.address, function (index, value) {
                    var optionExists = ($('#address option[value=' + value.id + ']').length > 0);
                    if (!optionExists) {
                        $('#address').append('<option id="option-' + value.id + '"  value="' + value.id + '">' + value.address + '</option>');
                        if (selected_user_address_id == value.id) {
                            $('#option-' + value.id).attr('selected', true);
                        }
                    }
                    ;
                });
                $('#district').val(response.district);
                $('#token').val(response.token);
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    }

    $('#users').on('change', function () {
        userAddress();
    });


    $('#address').on('change', function () {
        var address_id = $(this).val();
        $.ajax({
            method: 'GET',
            url: '{!! route('getUserShippingRate') !!}',
            data: {'address_id': address_id},
            success: function (response) {
                // console.log(response.standard_rate);
                $('#shipping_rate').val('');
                $('#shipping_rate').val(response);
                grandTotalAmount();
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    });

    $(document).on('change', '.selectpickerr', function (e) {
        var attr_id = $(this).closest('tr').attr('id');
        var item = e.target.value;
        var cTypeIncrementNum = parseInt(attr_id.match(/\d+/g), 10) + 1;
        $.ajax({
            method: 'GET',
            url: '{!! route('getItemDetails') !!}',
            data: {'item': item, 'sales_order': 1},
            success: function (response) {
                if (response.product_variations.length > 0) {
                    var newRow = $("<tr class='" + item + "'>");
                    var cols = "";
                    cols += "<td ><label>Variations Of ( <span style='color: red;'><b>'#" + response.parent_name + "'</b></span> )</label></td>";
                    var id = $('#tblrownew0 tr:last').attr('id');
                    newRow.append(cols);
                    $("table.order-list").append(newRow);
                    var last_id = $("#" + id + " .selectpickerr").attr("id");
                    var last_qty = $("#" + id + " .qty").removeAttr("min");
                    $("#" + last_id).attr('disabled', true);
                    $.each(response.product_variations, function (index, value) {
                        var newRow = $("<tr style='background-color: #fbfbfb;' class='" + item + "' id='field" + cTypeIncrementNum + "'>");
                        var cols = "";
                        var lastid = $("#" + id + " .selectpickerr").attr("id");
                        selected.push($('#' + lastid).val());
                        cols += '<td><select required name="items[]"   style="width: 240px;margin-left: 80px;"  id="items' + cTypeIncrementNum + '" class="form-control selectpickerr select2"><option value="' + value.item_id + '" data-foo="' + value.item_name + '" selected id="option-' + value.item_id + '">' + value.item_name + '</option></select></td>';
                        cols += '<td><input type="number" class="form-control qty" min="0" value="1"   id="qty" name="quantity[]"/></td>';
                        cols += '<td><input type="number" class="form-control item_rate" min="0" value="' + value.rate + '" step="0.01"  id="rate" readonly name="rate[]"/></td>';
                        cols += '<td><input type="number" class="form-control total" min="0" value="' + value.rate + '" step="0.01" disabled id="total_amount" name="total_amount[]"/></td>';

                        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
                        newRow.append(cols);
                        $("table.order-list").append(newRow);
                        $('#items' + cTypeIncrementNum).select2({
                            matcher: matchCustom,
                            templateResult: formatCustom
                        });
                        cTypeIncrementNum++;
                        // }
                        $('#' + attr_id + ' #rate').val('0');
                        $('#' + attr_id + ' #qty').val('0');
                        $('#' + attr_id + ' #rate').attr('readonly', true);
                        $('#' + attr_id + ' #qty').attr('disabled', true);
                        if ($('#' + attr_id + ' .ibtnDel').length == 1) {
                            $('#' + attr_id + ' .ibtnDel').attr('id', 'del' + item);
                        } else {
                            $('#' + attr_id).append('<td><input type="button" id="del' + item + '"  class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>');
                        }
                    });
                } else {
                    $('#' + attr_id + ' #rate').val('');
                    $('#' + attr_id + ' #rate').val(response.item_rate);
                    var rate = $('#' + attr_id + ' #rate').val();
                    var qty = $('#' + attr_id + ' #qty').val();
                    var total_amount = rate * qty;
                    $('#' + attr_id + ' #total_amount').val(Math.round(total_amount * 100) / 100);
                }
                // $('#'+attr_id+' #rate').val('');
                // $('#'+attr_id+' #rate').val(response);
                // var rate = $('#'+attr_id+' #rate').val();
                // var qty = $('#'+attr_id+' #qty').val();
                // var total_amount = rate * qty;
                // $('#'+attr_id+' #total_amount').val(Math.round(total_amount * 100) / 100);
                grandTotalAmount();
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    });

    // on change the quantity values
    $(document).on('input', '.qty', function (e) {
        var attr_id = $(this).closest('tr').attr('id');
        var rate = $('#' + attr_id + ' #rate').val();
        var qty = $('#' + attr_id + ' #qty').val();
        var total_amount = rate * qty;
        $('#' + attr_id + ' #total_amount').val(Math.round(total_amount * 100) / 100);
        grandTotalAmount();
    });


    // getting the grand total amount
    function grandTotalAmount() {
        var grand_total_amount = 0;
        var idArray = [];
        $('.total').each(function (value) {
            idArray.push(this.value);
        });
        idArray.clean("");
        $.each(idArray, function (index, value) {
            grand_total_amount += parseFloat(value);
        });
        shipping_rule_rate = $('#shipping_rate').val();
        grand_total_amount += parseFloat(shipping_rule_rate);
        $('#grand_total').val(Math.round(grand_total_amount * 100) / 100);
    }


    $(document).ready(function () {
        var counter = 0;
        $(document).on("click", "#addrow", function () {
            alert('addrowClicked');
            var newRow = $("<tr>");
            var cols = "";
            var id = $('#tblrownew0 tr:last').attr('id');
            // console.log(id);
            var lastid = $("#" + id + " .selectpickerr").attr("id");
            $('.selectpickerr')
            var selected_ids = [];
            $(".selectpickerr").each(function () {
                selected_ids[i++] = $(this).val(); //this.id
            });
            selected_ids.clean(undefined);


            if ($('#' + id + ' #' + lastid).val() == null || $('#' + id + ' #qty:last').val() == null) {
            } else {
                cols += '<td><select required name="items[]" style="width:300px;"  id="items1" class="form-control selectpickerr select2" /></td>';
                cols += '<td><input type="number" class="form-control qty" min="1" value="1"   id="qty" name="quantity[]"/></td>';
                cols += '<td><input type="number" class="form-control item_rate" min="0" value="0.00" readonly step="0.01"  id="rate" name="rate[]"/></td>';
                cols += '<td><input type="number" class="form-control total" min="0" value="0.00" step="0.01" disabled id="total_amount" name="total_amount[]"/></td>';
                cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
                newRow.append(cols);
                $("table.order-list").append(newRow);
                counter++;
                var contentTypeInput = $('#tblrownew0 tr:last').prop('id');
                var cTypeIncrementNum = parseInt(id.match(/\d+/g), 10) + 1;
                $('#tblrownew0 tr:last').attr('id', 'field' + cTypeIncrementNum);
                $('#tblrownew0 tr:last #items1').attr('id', 'items' + cTypeIncrementNum);
                $('#items' + cTypeIncrementNum).select2({
                    matcher: matchCustom,
                    templateResult: formatCustom
                });
                var new_items_options = [];
                var selected_item_to_remove = [];
                $.each(selected_ids, function (index, value) {
                    var text_value = $('#items' + 1 + ' #option-' + value).text();
                    var data_subtext = $('#items' + 1 + ' #option-' + value).attr('data-foo');
                    // console.log(data_subtext);
                    selected_item_to_remove.push('<option id="option-' + value + '" data-foo="' + data_subtext + '" value="' + value + '">' + text_value + '</option>');
                });
                new_items_options = items_options.filter(function (el) {
                    return !selected_item_to_remove.includes(el);
                });
                // console.log(new_items_options);

                $('#items' + cTypeIncrementNum).append('<option value="-1" data-foo="Select Item" disabled selected>Select Item</option>');
                $.each(new_items_options, function (index, value) {
                    $('#items' + cTypeIncrementNum).append(value);
                });

                $('#items' + cTypeIncrementNum).select2("destroy").select2({
                    matcher: matchCustom,
                    templateResult: formatCustom
                });
                selected = [];

            }
        });


        $("table.order-list").on("click", ".ibtnDel", function (event) {
            if ($(this).attr('id') != undefined) {
                var parent_id = $(this).attr('id').match(/\d+/); // 123456
                var trlength = $('tr.' + parent_id).length;
                if ($('#tblrownew0 tr').length > trlength + 1) {
                    $('tr.' + parent_id + ' .selectpickerr').each(function () {
                        var item_id = $('tr.' + parent_id + ' .selectpickerr').attr('id');
                        $('#' + item_id).select2("destroy").select2({
                            matcher: matchCustom,
                            templateResult: formatCustom
                        });
                    });
                    $("tr." + parent_id).remove();
                } else {
                    return false;
                }
            }
            var count = $('#tblrownew0 tr').length - 1;
            if (count >= 1) {
                var tr_id = $(this).closest("tr").attr('id');
                var selectpicker_id = $("#" + tr_id + " .selectpickerr").attr("id");
                var removed_option_value = document.getElementById(selectpicker_id).value;
                var text_value = $('#' + selectpicker_id + ' #option-' + removed_option_value).text();
                var data_subtext = $('#' + selectpicker_id + ' #option-' + removed_option_value).attr('data-foo');
                $(this).closest("tr").remove();
                var ids = [];
                var not_selected_options = [];
                // getting ids of selects that have class .selectpickerr
                $(".selectpickerr").each(function () {
                    ids[i++] = $(this).attr("id"); //this.id
                });
                ids.clean(undefined);
                // after remove the row looping over the ids and check if the count of the first lists > current select list i append it
                $.each(ids, function (index, value) {
                    var optionExists = ($('#' + value + ' option[value=' + removed_option_value + ']').length > 0);
                    if (!optionExists) {
                        $("#" + value).append('<option id="option-' + removed_option_value + '" data-foo="' + data_subtext + '" value="' + removed_option_value + '">' + text_value + '</option>');
                        $('#' + value).select2("destroy").select2({
                            matcher: matchCustom,
                            templateResult: formatCustom
                        });
                    }
                    counter -= 1
                });
            }
        });
    });


    // array_filter
    Array.prototype.clean = function (deleteValue) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] == deleteValue) {
                this.splice(i, 1);
                i--;
            }
        }
        return this;
    };

    function calculateRow(row) {
        var price = +row.find('input[name^="qty"]').val();

    }

    function calculateGrandTotal() {
        var grandTotal = 0;
        $("table.order-list").find('input[name^="qty"]').each(function () {
            grandTotal += +$(this).val();
        });
        $("#grandtotal").text(grandTotal.toFixed(2));
    }


</script>
<?php $canceledurl = url('/admin/cart-remove/');?>
<script>
    orders_canceled = [];
    $(document).on('change', '.checkbox', function () {
        if (this.checked) {
            var id = $(this).val();
            if (orders_canceled.indexOf(id) == -1) {
                orders_canceled.push(id);
            }
        }
        if (this.checked == false) {
            var id = $(this).val();
            var index = orders_canceled.indexOf(id);
            if (index > -1) {
                orders_canceled.splice(index, 1);
            }
        }

    });

    $('#canceled').click(function (event) {
        // event.stopImmediatePropagation();
        if (orders_canceled.length == 0) {
            alert('No Items In Cart To Be Cancelled');
            $('#myModal3').modal('hide');
            return false;
        }
        cancel_cart(orders_canceled);
        // $( "#delivered").unbind( "click" );
    });

    function cancel_cart(orders_canceled) {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            url: "{{$canceledurl}}",
            type: "POST",
            data: {
                'caneceled_product_ids': orders_canceled,
                'token': $('#token').val(),
            },
            success: function (response) {
                var responseArray = response;
                var arrayLength = responseArray.length;
                for (var i = 0; i < arrayLength; i++) {
                    $('#fieldset' + responseArray[i]).remove();
                }

                if (response == 'false') {
                    alert("There's no product in cart to be removed");
                }
                grandTotalAmount();

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
