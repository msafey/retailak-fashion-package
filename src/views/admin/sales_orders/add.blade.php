<!DOCTYPE html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
    @include('layouts.admin.scriptname_desc')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <!-- App Favicon -->

    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css"/>
    <style>
        .page-title-box {
            margin-right: 80px !important;
            margin-left: -10px !important;
        }

        #addressSelectorContainer {
            font-size: 11px;
        }
    </style>


    <!-- Latest compiled and minified bootstrap-select CSS -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">

    <div class="modal fade bd-example-modal-lg" id="myModal3" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true">User Cart</i>

                </div>
                <div class="modal-body">

                    <table id="myTablee" class="table order-lists">
                        <thead style="margin-bottom: 10px;">
                        <tr>
                            <td>Select Item</td>
                            <td>Items</td>
                            <td>Quantity</td>
                            <td>rate</td>
                            <td>Total Amount</td>
                        </tr>
                        </thead>

                        <tbody id="tblrownew55">

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
                    <div class="form-group">
                        <label style="margin-bottom: 0;" class="form-group" for="from">Email: </label>
                        <input required id="email" name="email" class="form-control">
                    </div>

                    <button class="btn btn-primary" id="addAddressBtn"> Add address</button>
                    <button class="btn btn-danger" id="removeAddressBtn" style="display:none;"> Remove address</button>
                    <div id="addressContainer" class="row">
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="add_user">Save
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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


                <div class="modal" id="addItemModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Select an item</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body add-item-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class=" col-sm-10" id="ItemSelectorContainer">
                                            <div id="outOfStockItem" class="col-sm-12 alert alert-danger"
                                                 style="display: none"></div>
                                            <label for="parent_item"> Select an item </label>


                                            <select name="parent_item" id="parent_item" class="form-control"
                                                    style="width:250px !important;">
                                                <option value="">Select an item</option>
                                                @foreach($parentProducts as $parentProduct)
                                                    <option value="{{$parentProduct->item_code}}">{{$parentProduct->name_en}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class=" col-sm-10" id="VariantSelectorContainer"></div>
                                        <input type="text" id="SelectedItemForCart" class="hidden">
                                        <div class=" col-sm-10 alert alert-danger" style="display:none;"
                                             id="AddToCartResponseError"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="addItem">Add Item</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="modal" id="checkoutModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Order Confirmation</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                Confirm Order
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="confirmOrderBtn">Confirm</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>


                {!! Form::open(['url' => '/admin/cart', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block" style="width: 92%">
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

                    <div class="alert alert-danger" role="alert" id="error-div" style="display: none">

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

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3" style="display: inline;">
                                <?php $adduser = url('/admin/users/create');?>
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
                                    <div class='input-group date' id='addressSelectorContainer'
                                         style="display: inline;">
                                        <div id="addressErrorMessageContainer"></div>
                                        <select required name="address_id" id="address" class="form-control">
                                            <option value="-1" disabled selected>Select Address</option>
                                        </select>
                                        <input type="text" hidden="hidden" id="district" name="district_id"/>
                                        <input type="text" hidden="hidden" id="token" name="token"/>
                                    </div>
                                </div>
                                <div class="col-sm-4" style="display: inline;">
                                    <?php $add_address = url('/admin/address/create');?>
                                    <a href="#"
                                       class=" button btn btn-primary " id="create_address">Create Address
                                    </a>
                                </div>
                            </div>


                        </div>

                    </div>
                    <input type="hidden" id="userCartItems">


                    <div class="row">
                        <div class="col-lg-12">

                            <h3>Cart Items</h3>
                            <table id="myTable" class=" table order-list">
                                <thead>
                                <tr>
                                    <td>Items</td>
                                    <td>Quantity</td>
                                    <td>Stock Qty</td>
                                    <td>rate</td>
                                    <td>Total Amount</td>
                                    <td>Actions</td>
                                </tr>
                                </thead>
                                <tbody id="tblrownew0"> {{--items[]--}} {{--quantity[]--}} {{--rate[]--}} {{--total_amount[]--}} {{--Actions--}}
                                <tr>
                                    <td colspan="5" class="col-sm-12"> Please Select or add user</td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr id="addItemBtnContainer" style="display: none;">

                                    <td colspan="5" class="col-sm-12">
                                        <input type="button"
                                               class="btn btn-lg btn-primary " id="addrow"
                                               value="+" data-toggle="modal"
                                               data-target="#addItemModal"/> Add Item
                                    </td>
                                </tr>
                                </tfoot>
                            </table>


                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-12">

                            <div class="col-sm-6" style="margin-left:8px; margin-top:6px;">
                                <div class="col-sm-12">
                                    <label style="margin-bottom: 0;" class="form-group" for="from">Note
                                    </label>
                                </div>
                                <div class="col-sm-12">
                                    <div class='input-group date' style="display: inline;" id=''>
                                        <textarea name="note" id="note" cols="30" rows="5"></textarea>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{--                        <div class="col-lg-4">--}}
                        {{--                            <div class="col-sm-12">--}}
                        {{--                                <label style="margin-bottom: 0;" class="form-group" for="from">Time Section--}}
                        {{--                                </label>--}}
                        {{--                            </div>--}}
                        {{--<div class="col-sm-12">
                            <div class='input-group date' id='' style="display: inline;">
                                <select required name="timesection_id" id="timesection_id" class="form-control">
                                    <option value="-1" disabled selected>Select Time Section</option>
                                    @foreach ($time_sections as $time_section)
                                        <option value="{{$time_section->id}}">{{$time_section->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>--}}
                        {{--                        </div>--}}
                        <div class="col-lg-4">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">Shipping Role
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <div class='input-group date' id='' style="display: inline;">
                                    <select name="shipping_role_id" id="shipping_role_id" class="form-control">
                                        <option value="-1">Select Shipping role</option>
                                        <?php foreach ($shippingRoles as $shipping_rule) {?>
                                        <option rate="{{$shipping_rule->rate}}"
                                                value="{{$shipping_rule->id}}">{{ $shipping_rule->shipping_rule_label}}</option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">Shipping Rate
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <input type="number" step="0.01" readonly="readonly" class="form-control"
                                       id="shipping_rate" name="shipping" value="{{$shipping_rate}}">
                                <input type="hidden" step="0.01" readonly="readonly" class="form-control"
                                       id="shipping_rate_hidden" value="{{$shipping_rate}}">
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
                                <input type="text" id="promocode" class="form-control promocode" name="promocode">

                            </div>
                        </div>


                        <div class="col-lg-6" id="validate" style="display: none;">
                            <div class="col-sm-12">
                                <label style="margin-bottom: 0;" class="form-group" for="from">PromoCode Validate
                                </label>
                            </div>
                            <div class="col-sm-12">
                                <input type="text" id="promocode_response" disabled="disabled"
                                       class="form-control promocode" id="shipping_rate" name="promocode" value="">

                            </div>
                        </div>
                    </div>


                    <input type="text" hidden="hidden" name="sales_order_request"
                           value="@if(isset($sales_order_request)){{$sales_order_request}}@endif">

                    <div class="row">
                        <div class="col-lg-6"
                             style="left:200px;width: 515px ; height:150px;padding:10px;border: 2px solid gray; margin-top: 100px;">

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
                    </div>

                    <div class="row">

                        <button type="button" id="ConfirmOrder" data-toggle="modal" data-target="#checkoutModal"
                                style="margin-left: 12px" class="btn btn-primary">Save
                        </button>
                        {{--<div class="col-sm-32"><button type="submit" id="save" style="margin-left: 12px" class="btn btn-primary">Save</button>--}}
                        {{--<input type="button" class="btn  btn-warning " data-toggle="modal" data-target="#myModal3" id="mycart" value="User Cart" /></div>--}}
                        {{--<div class="col-sm-32">--}}
                    </div>
                </div>
            </div>

            {!! Form::close() !!}

            <div id="addressHtml" style="display: none;">
                <div class="col-lg-6">
                    <div class="col-lg-12">
                        <label style="margin-bottom: 0;" class="form-group" for="from">Street : <span
                                style="color:red;">*</span>
                        </label>
                    </div>
                    <div class="col-lg-12" style="margin-top: 0px">
                        <div class='input-group date' style="display: inline;" id=''>
                            <input class="form-control" id="street" name="street"
                                   placeholder="" required="required" type="text"/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="col-lg-12">
                        <label style="margin-bottom: 0;" class="form-group" for="from">Districts : <span
                                style="color:red;">*</span>
                        </label>
                    </div>
                    <div class="col-lg-12" style="margin-top: 0px">
                        <div class='input-group date' style="display: inline;" id='district'>
                            <select required name="district_id" id="district_id" class="form-control">
                                <option disabled selected>Select District</option>
                                @foreach($districts as $district)
                                    <option value="{{$district->id}}">{{$district->district_en}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="addressEnabled" name="addressEnabled" value="0">

            </div>

        </div>
    </div>


</div>

<!-- End content-page -->
<!-- Footer Area -->
@include('layouts.admin.footer')

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
    var currentUser;
    var response_array = [];
    var items_array = <?php echo json_encode($products); ?>;
    var selected = [];
    var items_options = [];
    var checkout;
    var selected_option = $(".selectpickerr:first").attr('id');


    $(document).on('keyup', '.select2-search__field', delay(function (e) {

        $.ajax({
            url: '{!! url('admin/search/user') !!}/' + $('.select2-search__field')[0].value,

            method: "GET",
            success: function (users) {
                $.each(users, function (key, response) {
                    let option = '<option  value="' + response.id + '" id="user' + response.id + '" data-foo="' + response.phone + '">' + response.name + '</option>';

                    $('#users').append(option);

                    $('#users').trigger('change');

                });

            }


        })
    }, 500));

    function delay(callback, ms) {
        var timer = 0;
        return function () {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    }


    var selected_parents = [];
    $(document).on('change', '#parent_item', function () {
        selectedItem = $("#parent_item option:selected").val();
        $('#SelectedItemForCart').val(selectedItem.id);
        selected_parents.push(selectedItem);
        district_id = $('#district').val();
        $.ajax({
            url: '{!! url('admin/getProductVariants/') !!}/' + selectedItem,
            headers: {
                'token': token,
                'lang': 'en',
                'district_id': district_id,

            },
            success: function (response) {
                $("#VariantSelectorContainer").css("display", "block");

                if (Array.isArray(response) && response.length > 0) {
                    $('#ItemSelectorContainer').hide();
                    variantSelector = '<label for="VariantSelector">This Product has variants please choose one</label> <select id="VariantSelector"> <option> Select Variant</option> ';
                    $('#VariantSelectorContainer').html('');
                    availableVariants = 0;
                    $.each(response, function (index, Variant) {

                        console.log('VariantData', Variant);
                        if (Variant.stock_qty > 0) {
                            availableVariants = availableVariants + 1;
                            variantSelector += '<option value=' + Variant.id + '>' + Variant.name + '</option>';

                        }
                        console.log('availableVariants:' + availableVariants);
                    });
                    variantSelector += '</select>';
                    if (availableVariants > 0) {
                        $('#VariantSelectorContainer').html(variantSelector);
                    } else {
                        $('#outOfStockItem').html('The Item (' + selectedItem + ') is out of stock ');
                        $('#outOfStockItem').show();
                        $('#ItemSelectorContainer').show();
                    }


                } else {
                    $('#SelectedItemForCart').val(selectedItem.id);
                }
                console.log(response);
            }

        })


    }); //Beshir

    $(document).on('change', '#VariantSelector', function () {
        $('#SelectedItemForCart').val($(this).val());

    });

    $(document).on('click', '#addItem', async function () {

        selectedItemId = $('#SelectedItemForCart').val();
        FirstItem = [];
        FirstItem['id'] = parseInt(selectedItemId);
        FirstItem['qty'] = 1;
        CartItemsList = [FirstItem];

        $('.itemRow').each(function (index, Value) {

            var myselectedItem = [];
            //myselectedItem = {"id":parseInt(Value.id),"qty":parseInt($('#'+Value.id+' #'+Value.id+'-qty').val())};
            myselectedItem['id'] = parseInt(Value.id);
            myselectedItem['qty'] = parseInt($('#' + Value.id + ' #' + Value.id + '-qty').val());
            CartItemsList.push(myselectedItem);
        });
        userId = $('#users').val();
        currentUser = await getUserDetails(userId);
        console.log('CartList:', CartItemsList);
        await addItemsToCart(CartItemsList, currentUser.token);

        $('#addItemModal').modal('hide');

        console.log('SelectedItems', CartItemsList);
    });

    $("#addItemModal").on("hidden.bs.modal", function () {
        $("#ItemSelectorContainer").css("display", "block");
        $("#VariantSelectorContainer").css("display", "none");
        $("#SelectedItemForCart").val("");
        $('#outOfStockItem').hide();
        $("#parent_item").val("");
        $("#ItemSelectorContainer option:selected").remove();
        $("#VariantSelectorContainer option:selected").remove();
        grandTotalAmount();
    });

    $(document).on('click', '#addAddressBtn', function () {

        var addressHtml = $('#addressHtml').html();
        $('#addressContainer').css('height', '75px');

        $('#addressContainer').html(addressHtml);
        $('#addressEnabled').val('1');
        $('#addAddressBtn').hide();

        $('#removeAddressBtn').show();

    });

    $(document).on('click', '#removeAddressBtn', function () {
        $('#addressEnabled').val('0');
        $('#addressContainer').css('height', '0px');
        $('#addressContainer').html('');
        $('#addAddressBtn').show();
        $('#removeAddressBtn').hide();

    });

    $(document).on('click', '#add_user', function () {

        let name = $('#user_name').val();
        let phone = $('#phone').val();
        let email = $('#email').val();
        let street;
        let district;
        let lat;
        let lng;
        if ($('#addressEnabled').val() == '1') {
            street = $("input[name='street']").val();

            district = $('#district_id').val();

            lng = $('#lng').val();
            lat = $('#lat').val();

            if (street == '') {
                alert('please add street name');
                return false;
            }

            if (district == '' || district == null || !district) {
                alert('please select district');
                return false;
            }

        }
        if (name == '' || phone == '' || email == '') {
            alert('please complete required data');
            return false;
        } else {
            info = {};
            info.name = name;
            info.phone = phone;
            info.email = email;
            console.log('address', street);
            if ($('#addressEnabled').val() == '1') {
                info.street = street;
                info.district = district;
                info.lat = lat;
                info.lng = lng;
                info.addressEnabled = 1;

                //var data = {'name': name,'phone':phone,'email':email,'street':street,'district':district,'lat':lat,'lng':lng,'addressEnabled':1};
            }
            console.log(info);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: '{!! route('createUser') !!}',
                type: "POST",
                data: info,
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
                            alert('user created successfully');
                            $('#user_name').val('');
                            $('#phone').val('');
                            $('#email').val('');
                            if ($('#addressEnabled').val() == '1') {
                                $("input[name='street']").val('');
                                $('#district_id').val('');
                                $('#lng').val('');
                                $('#lat').val('');
                            }
                        }

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
                // convert string to object in javascript
                var obj = jQuery.parseJSON(response);
                if (typeof obj.discount_rate !== "undefined") {
                    if (obj.has_free_shipping == 1) {
                        shippingRate = $('#shipping_rate').val();
                        $('#shipping_rate').val(0);
                        $('#grand_total').val($('#grand_total').val() - shippingRate);

                    }
                    if (obj.discount_rate > 0) {
                        $('#grand_total').val($('#grand_total').val() - obj.discount_rate);
                    }
                    $('#promocode_response').val('Promocode is valid');
                } else {
                    grandTotalAmount();
                    $('#shipping_rate').val($('#shipping_rate_hidden').val());
                    $('#grand_total').val(parseFloat($('#grand_total').val()) + parseFloat($('#shipping_rate').val()));
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
    });

    $(document).ready(function () {
        grandTotalAmount();
        $('#users').select2({
            matcher: matchCustom,
            templateResult: formatCustom
        });
        //parent_item
        $('#parent_item').select2({});
        $('#items1').select2({
            matcher: matchCustom,
            templateResult: formatCustom
        });
        if ($('#items1').val()) {
            $(this).attr('disabled', true);
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

    async function addItemsToCart(CartItemsList, token) {
        console.log("Token", token);
        console.log("AddToCartItems", CartItemsList);
        JsonCart = [];

        $.each(CartItemsList, function (index, item) {
            JsonCart.push({"id": item.id, "qty": item.qty});
        });
        console.log("jsonCart", JsonCart);
        await fetch(`{!! url('api/cart') !!}`, {
            'method': 'POST',
            headers: {
                "Content-Type": "application/json",
                'lang': 'en',
                'token': token
            },
            'body': JSON.stringify(JsonCart)

        }).then(async (response, err) => {
            let json = await response.json();
        console.log('MyNewCart', json);
        if (response.status = 200) {
            district_id = $('#district').val();
            // loadUserCartItems(token,district_id);
            await loadUserCartItems(token, district_id);
        } else {
            return json.then(err => {
                throw err;
        })
        }
    }).catch((err) => {
            console.log('err', err);
        $('#AddToCartResponseError').html(err.message);
        $('#AddToCartResponseError').show();
    });
    }

    function Checkout(address_id, shipping_role_id, payment_method, note, token) {


        district_id = $('#district').val();


        data = {
            "address_id": address_id,
            // "time_section_id": timesection_id,
            "shipping": shipping_role_id,
            "payment_method": payment_method,
            "note": note,
            "admin": "{{$adminId}}",

        };
        //data =["address_id"] = address_id:address_id,"time_section_id":timesection_id,"payment_method":payment_method};

        return fetch(`{!! url('api/checkout') !!}`, {
            'method': 'POST',
            headers: {
                "Content-Type": "application/json",
                'lang': 'en',
                'token': token,
                'district_id': district_id
            },
            'body': JSON.stringify(data)

        });

    }

    async function getUserDetails(userId) {
        var userData = await
        fetch("{!! route('getUserData') !!}?id=" + userId)
            .then((response) => response.json());
        $('#token').val(userData.token);
        return userData;
    }

    async function getUserAddresses() {
        if ($('#users').val() == null) {

            $('#address').hide();
            $('#addressSelectorContainer').html('<div  class="alert alert-danger">Please Select User</div>');

            return false;
        }
        userId = $('#users').val();


        currentUser = await getUserDetails(userId);


        console.log('USer Id :' + userId);
        console.log(currentUser);
        generateCreateAddressUrl();

        $('#address').empty();

        $.ajax({
            method: 'GET',
            url: '{!! route('getCurrentUserAddresses') !!}',
            // data: {'email': currentUser.email},
            // Updated By Osama Yasser to get user address by id instead of email
            data: {'id': currentUser.id},
            success: function (addresses) {

                if (Array.isArray(addresses)) {
                    $('#address').show();
                    $('#addressErrorMessageContainer').html('');
                    $('#address').append('<option value="-1" disabled selected>Select Address</option>');
                    $.each(addresses, function (index, value) {
                        var optionExists = ($('#address option[value=' + value.id + ']').length > 0);
                        if (!optionExists) {
                            $('#address').append('<option id="option-' + value.id + '" district_id="' + value.district_id + '"  @if(old('address_id') == '+value.id+') selected @endif value="' + value.id + '">' + value.address + '</option>');
                        }
                        ;
                    });
                } else {
                    $('#address').hide();

                    $('#addressErrorMessageContainer').html('<div id="addressErrorMessageContainer" class="alert alert-danger">' + addresses + '</div>');

                }


                //$('#district').val(response.district);
                //$('#token').val(response.token);
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail

                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });

        return currentUser;


    }

    function generateCreateAddressUrl() {
        var email = $('#users').val();
        <?php $address_url = url('/admin/address/create');?>
        $("#create_address").attr('href', '{{$address_url}}' + '?id=' + email);
    }

    function loadUserCartItems(token, district_id = null) {
        $.ajax({
            headers: {
                'token': token,
                'lang': 'en',
                'district_id': district_id,
            },
            method: 'GET',
            url: '{!! url('api/cart') !!}',

            success: function (cart) {
                console.log("user Cart", cart);
                if (Array.isArray(cart) && cart.length > 0) {

                    itemRows = '';
                    cartItemIds = '';
                    $.each(cart, function (index, CartItem) {
                        if (cartItemIds.length > 0)
                            cartItemIds += ',' + CartItem.id;
                        else
                            cartItemIds += CartItem.id;
                        console.log("CartItem:", CartItem);
                        itemRows += '<tr id="' + CartItem.id + '" class="itemRow" >' +
                            '<td class="col-sm-3">' + CartItem.name + '</td>' +
                            '<td class="col-sm-1">' +
                            '<input type="number" class="qtyInput" id="' + CartItem.id + '-qty" name="quantity[]" value="' + CartItem.qty + '" /> </td>' +
                            '</td>' +
                            '<td class="col-sm-1">' +
                            '<input type="number"  disabled="disabled" class="stockQtyInput" id="' + CartItem.id + '-stock" name="stockqty[]" value="' + CartItem.stock_qty + '" /> ' +
                            '</td>' +

                            '<td class="col-sm-2">' +
                            '<input type="number" class="rateInput" readonly id="' + CartItem.id + '-rate" name="rate[]" value="' + CartItem.standard_rate + '" /> </td>' +
                            '</td>' +
                            '<td class="col-sm-2">' +
                            '<input type ="text" class="total" id="' + CartItem.id + '-total_amount"  value="' + CartItem.qty * CartItem.standard_rate + '" disabled = "disabled" /></td>' +
                            '<td class="col-sm-2"><td>' +
                            '<input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete" />' +
                            '</td>' +
                            '</tr>';
                    });
                    $('#userCartItems').val(cartItemIds);
                    $('#tblrownew0').html(itemRows);
                    grandTotalAmount();
                } else {

                    $('#tblrownew0 tr td').html('User Cart is empty you may start adding items to cart after selecting address')

                }

            }
        });


    }

    function userAddress() {
        if ($('#users').val() == null) {
            return false;
        }
        $('#address').empty();
        var email = $('#users').val();
        <?php $address_url = url('/admin/address/create');?>
        $("#create_address").attr('href', '{{$address_url}}' + '?id=' + email);
        $.ajax({
            method: 'GET',
            url: '{!! route('getUserAddress') !!}',
            data: {'email': email},
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
                        $.each(products, function (index, val) {
                            //alert(77);
                            //var newRow = $();
                            var cols = "<tr class='" + val.parent_name + "' style='background-color: #fbfbfb;' id='fieldset" + val.item_id + "'>";
                            cols += '<td class="col-sm-1"><input  type="checkbox"  name="items_cancel[]" style="" value="' + val.item_id + '"  id="cancel-"' + value.item_id + '" class="form-control checkbox" /></td>';
                            cols += '<td class="col-sm-3"><input disabled name="items[]" style="width:300px;" value="' + val.item_name + '"  id="" class="form-control" /><input readonly hidden name="items[]" style="width:300px;" value="' + value.item_id + '"  id="" class="form-control" /></td>';
                            cols += '<td class="col-sm-2"><input type="number" disabled class="form-control qty" min="1" value="' + val.qty + '" id="qty" name="quantity[]"/></td>';
                            cols += '<td class="col-sm-2"><input type="number" class="form-control" min="0" value="' + val.rate + '" step="0.01"  readonly id="rate" name="rate[]"/></td>';
                            cols += '<td class="col-sm-2">' +
                                '<input type="number" class="form-control total" min="0" value="' + val.total_amount + '" step="0.01" readonly id="" name="total_amount[]"/>' +
                                '</td>';
                            cols += '</tr>'
                            //newRow.append(cols);
                            //console.log('newRow:',newRow);
                            $("table.order-lists").append(cols);
                        });
                    });

                }

                $('#address').append('<option value="-1" disabled selected>Select Address</option>');
                if (response.address != undefined) {
                    $.each(response.address, function (index, value) {
                        var optionExists = ($('#address option[value=' + value.id + ']').length > 0);
                        if (!optionExists) {
                            $('#address').append('<option id="option-' + value.id + '"  @if(old('address_id') == '+value.id+') selected @endif value="' + value.id + '">' + value.address + '</option>');
                        }
                        ;
                    });
                }

                $('#district').val(response.district);
                $('#token').val(response.token);
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });
    }

    $('#users').on('select2:select', async function () {

        currentUser = await getUserAddresses();
        userCartItems = loadUserCartItems(currentUser.token);


    });

    // Osama
    // On click delete button remove item from cart
    $(document).on('click', '.ibtnDel', function () {

        thisIs = $(this).closest('tr').attr('id');

        $('#' + thisIs + '-qty').val(0);
        var updatedCart = [];
        $('#tblrownew0 tr').each(function (index, item) {
            var cartItem = [];
            var itemId = item.id;


            var itemQty = $('#' + item.id + '-qty').val();

            cartItem['id'] = parseInt(itemId);
            cartItem['qty'] = parseInt(itemQty);
            updatedCart.push(cartItem);
        });

        thisIs = $(this).closest('tr').hide();

        userId = $('#users').val();

        $.ajax({
            method: 'GET',
            url: "{!! route('getUserData') !!}?id=" + userId,
            success: function (response) {

                console.log('in update CartL:', updatedCart);

                addItemsToCart(updatedCart, response.token);

                grandTotalAmount();
            }
        });
    });


    $('#address').on('change', function (e) {
        $('#addItemBtnContainer').show();
        address_id = $(this).val();
        var token = $('#token').val();
        // currentUser =  getUserAddresses();
        // currentUser = getUserDetails(userId);
        district_id = e.target.selectedOptions[0].getAttribute('district_id');
        $('#district').val(district_id);
        console.log('DistrictID:' + district_id);
        fetch("{!! route('getUserShippingRate') !!}?address_id=" + address_id, {
            headers: {
                "Content-Type": "application/json",
                'lang': 'en',
                'token': currentUser.token
            }
        }).then(async (response, err) => {
            let json = await response.json();

        console.log("shipping rate", json);

        $('#shipping_rate').val(json);
        $('#shipping_rate_hidden').val(json);
        //console.log('got shipping rate '+currentUser.token +" -- "+ district_id+" -- "+$('#token').val());
        currentUser = getUserDetails(userId);
        loadUserCartItems(token, district_id);
        console.log('response', json);

        // console.log(response.standard_rate);

        grandTotalAmount(); //Show additembtn
    });
        $('#token').val(token);
    });

    // on change the quantity values
    function delay(callback, ms) {
        var timer = 0;
        return function () {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    }

    $(document).on('input', '.qtyInput', function (e) {
        // setTimeout(function () {

        var attr_id = $(this).closest('tr').attr('id');

        var rate = $('#' + attr_id + '-rate').val();

        var qty = $('#' + attr_id + '-qty').val();

        // 6-stock
        var stockqty = $('#' + attr_id + '-stock').val();

        console.log(qty);
        console.log(rate);
        console.log(stockqty);

        if (parseInt(qty) > parseInt(stockqty)) {
            alert("Selected Qty greater Than Stock Qty");
            $('#' + attr_id + '-qty').val(stockqty);
            return false;
        } else {
            console.log('total_amount', qty * rate);

            $('#' + attr_id + '-total_amount').val(qty * rate);

            grandTotalAmount();
        }


        // }, 2000);
    });


    // getting the grand total amount
    function grandTotalAmount() {
        var grand_total_amount = 0;
        var idArray = [];

        $('.total').each(function (value) {
            console.log('Total', this.value);
            idArray.push(this.value);
        });

        $.each(idArray, function (index, value) {
            grand_total_amount += parseFloat(value);
        });
        shipping_rule_rate = $('#shipping_rate').val();
        grand_total_amount += parseFloat(shipping_rule_rate);
        $('#grand_total').val(Math.round(grand_total_amount * 100) / 100);
    }

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

    $(document).on('click', '#confirmOrderBtn', async function () {
        $('#confirmOrderBtn').attr("disabled", true);

        newCart = [];
        $('#tblrownew0 tr').each(function (index, item) {
            var cartItem = [];
            var itemId = item.id;

            var itemQty = $('#' + item.id + '-qty').val();

            cartItem['id'] = parseInt(itemId);
            cartItem['qty'] = parseInt(itemQty);
            newCart.push(cartItem);


        });

        note = $('#note').val();

        userId = $('#users').val();
        currentUser = await getUserDetails(userId);
        console.log('newCart:', newCart);
        await addItemsToCart(newCart, currentUser.token);
        var address_id = $('#address').val();
        var shipping_role_id = $('#shipping_role_id').val();
        // var timesection_id = $('#timesection_id').val();
        var payment_method = 'Cash';
        // var checkoutResponse = await Checkout(address_id, shipping_role_id, timesection_id, payment_method, note, currentUser.token);

        let checkoutPromise = Checkout(address_id, shipping_role_id, payment_method, note, currentUser.token);

        checkoutPromise.then(async (response, err) => {
            let json = response.json();
        if (response.status != 200) {


            let error = await json;
            $('#error-div').html(error.message);
            $('#error-div').show();

            $('#checkoutModal').modal('hide');
            window.scrollTo(0, 0);

        } else {
            setTimeout(function () {
                window.location.replace('{{url('/admin/sales-orders')}}');
            }, 200);

        }

    })
    .catch((err) => {

            console.log('err', err);
        $('#AddToCartResponseError').html(err.message);
        $('#AddToCartResponseError').show();

        alert(err);
    });


    });

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

    $(document).on('change', '#shipping_role_id', function () {

        //rate = $(this).attr('rate');
        var element = $("option:selected", this);
        rate = element.attr('rate');
        console.log('Rate', rate);
        $('#shipping_rate').val(rate);
        grandTotalAmount();
    });


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>

