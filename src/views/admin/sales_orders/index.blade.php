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
                                <button type="button" class="btn btn-default" data-dismiss="modal">No Cancel
                                </button>
                                <a class="btn btn-sm btn-danger" href="javascript:void(0)" id="delItem"
                                   title="Hapus"><i
                                            class="glyphicon glyphicon-trash"></i> Delete Item</a>

                            </div>
                        </div>

                    </div>
                </div>


                <div class="modal fade bs-example-modal-sm" id="changeModal" tabindex="-1" role="dialog"
                     aria-labelledby="mySmallModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                Change Order Status
                            </div>
                            <form action="{{ url('admin/changeorderstatus') }}" method="post">
                                <div class="modal-body">

                                    <input type="hidden" id="changeOrderId" name="orderId">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <select id="order_statusmodal" name="newOrderStatus" class="form-control">
                                        <option data-foo="Select Status">Select Order Status</option>
                                        <option value="Confirmed">Confirmed</option>
                                        <option value="ReadyToShip">Ready To Ship</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Return">Return</option>
                                        <option value="OnHold">On Hold</option>
                                        <option value="Pendig">Pending</option>
                                        <option value="Exchange">Exchange</option>
                                        <option value="Wait">Wait</option>
                                        <option value="Delivered">Delivered</option>
                                        <option value="outOfStock">Out of Stock</option>
                                    </select>
                                    <br/>


                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">No
                                        Cancel
                                    </button>
                                    <button class="btn btn-sm btn-primary" type="submit">Save</button>


                                </div>
                            </form>
                        </div>

                    </div>
                </div>

                <div class="modal fade bd-example-modal-lg" id="myModalReturn" data-backdrop="static"
                     data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
                     aria-hidden="true">
                </div>


                <div class="row">

                    <div class="card card-block">
                        <div class="card-title">
                            <div class="row">
                                <div class="col-sm-2">
                                    <a href="{{url('/admin/sales-orders/create')}}" class="btn btn-rounded btn-primary"><i
                                                class="zmdi zmdi-plus-circle-o"></i> Add New Order</a>
                                </div>

                                <div class="col-sm-1">
                                    <label for="">Order Id</label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="number" placeholder="Order Id" class="form-control" id="order_id"
                                           name="order_id">
                                </div>
                                <div class="col-sm-3">
                                    <div class="col-sm-4" style="margin-top: 7px;">
                                        <label for="crewdateofjoining">Products: <span
                                                    style="color:red;"></span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select id="product_id" class="form-control select2">
                                            <option disabled selected value="0" data-foo="Select Product">Select
                                                Product
                                            </option>
                                            <!-- <option value="-1">All Categories</option> -->

                                            @foreach ($products as $product)
                                                <option value="{{$product->id}}"
                                                        @if($product_id == $product->id)selected
                                                        @endif data-foo="{{$product->name}}">{{$product->name_en}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-sm-3">
                                    <div class="col-sm-4" style="margin-top: 7px;">
                                        <label for="crewdateofjoining">Status: <span style="color:red;"></span></label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select id="order_status" class="form-control select2">
                                            <option value="0" data-foo="Select Status">Select Order Status</option>

                                            <option value="Confirmed">Confirmed</option>
                                            <option value="ReadyToShip">Ready To Ship</option>
                                            <option value="Cancelled">Cancelled</option>
                                            <option value="Return">Return</option>
                                            <option value="OnHold">On Hold</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Exchange">Exchange</option>
                                            <option value="Wait">Wait</option>
                                            <option value="Delivered">Delivered</option>
                                            <option value="outOfStock">Out Of Stock</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <form action="{{url('admin/updateOrderStatus')}}" method="POST">
                            {!! csrf_field() !!}
                            <div class="card-text">
                                <a class="btn btn-secondary" href="{{route('track-orders')}}"
                                   style="float: right; margin-bottom: 10px;">order tracking <i
                                            style="margin-left: 45px;" class="fas fa-user-secret fa-lg"></i> </a>
                                <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>User</th>
                                        <th>Payment Method</th>
                                        <th>Date</th>
                                        <th>Customer Phone</th>
                                        <th>Customer Address Phone</th>
                                        <th>Status</th>
                                        <th>External Receipt Id</th>
                                        <th>Actions</th>
                                        <th>Shipping</th>
                                        <th>Source</th>
                                    </tr>
                                    </thead>
                                </table>
                                <div class="row">
                                    <div class="col-sm-4">Update Orders Status <select name="newStatus"
                                                                                       class="form-control">
                                            <option data-foo="Select Status">Select Order Status</option>
                                            <option value="Confirmed">Confirmed</option>
                                            <option value="ReadyToShip">Ready To Ship</option>
                                            <option value="Cancelled">Cancelled</option>
                                            <option value="Return">Return</option>
                                            <option value="OnHold">On Hold</option>
                                            <option value="Pendig">Pending</option>
                                            <option value="Exchange">Exchange</option>
                                            <option value="Wait">Wait</option>
                                            <option value="Delivered">Delivered</option>
                                            <option value="outOfStock">Out of Stock</option>
                                        </select></div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-4">

                                        <input type="submit" class="btn btn-success"></div>
                                </div>
                            </div>
                        </form>
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
        <?php $editurl = url('/admin/sales-orders/');?>
        <?php $deleteurl = url('/admin/sales-orders/delete');?>
        <?php $details = url('/admin/sales-details/');?>

        <?php $shipmentUrl = url('/admin/sales-orders/');?>
        <?php $trackUrl = url('/admin/sales-orders/');?>

    var table = $('#items_datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 50,
            order: [[0, "desc"]],
            ajax: {
                url: '{!! route('ordersList') !!}',
                type: "GET",
                data: function (d) {
                    d.product_id = document.getElementById('product_id').value,
                        d.order_status = document.getElementById('order_status').value
                }
            },
            columns: [
                {
                    data: 'id', searchable: true, render: function (data, row, data2) {
                        return '<input type="checkbox" class="orderIdToChangeStatus" name="orderids[]" value="' + data + '">';
                    }
                },
                {data: 'user.name', searchable: true, name: 'user.name'},
                {data: 'payment_method', name: 'orders.payment_method', searchable: false},
                {data: 'date', name: 'date', searchable: true},
                {data: 'user.phone', name: 'user.phone', orderable: false, visible: false, searchable: true},
                {
                    data: 'address.address_phone',
                    name: 'address.address_phone',
                    orderable: false,
                    visible: false,
                    searchable: true
                },
                {
                    data: 'status', searchable: false, render: function (data, row, data2) {

                        var status = '';
                        if (data == 'Added') {
                            var status = '<button disabled class="btn btn-primary">Added</button>'
                        }
                        if (data == 'Confirmed') {
                            var status = '<button disabled class="btn btn-success">Confirmed</button>'
                        }
                        if (data == 'Delivered') {
                            var status = '<button disabled class="btn btn-success">Delivered</button>'
                        }
                        if (data == 'Pending') {
                            var status = '<button disabled class="btn btn-warning">Pending</button>'
                        }
                        if (data == 'Wait') {
                            var status = '<button disabled class="btn btn-warning">Wait</button>'
                        }
                        if (data == 'ReadyToShip') {
                            var status = '<button disabled class="btn btn-rounded">Ready To Ship</button>'
                        }

                        if (data == 'Return') {
                            var status = '<button disabled class="btn btn-default">Return</button>'
                        }
                        if (data == 'Cancelled') {
                            var status = '<button disabled class="btn btn-danger">Cancelled</button>'
                        }

                        if (data == 'OnHold') {
                            var status = '<button disabled class="btn btn-primary">On Hold</button>'
                        }
                        if (data == 'Pending' || data == 'PendingCredit') {
                            var status = '<button disabled class="btn btn-warning">Pending</button>'
                        }
                        if (data == 'Exchange') {
                            var status = '<button disabled class="btn btn-warning">Exchange</button>'
                        }
                        if (data == 'outOfStock') {
                            var status = '<button disabled class="btn btn-warning">Out Of Stock</button>'
                        }
                        return status;
                    }
                },
                {
                    data: 'external_reciept_id', searchable: false, name: 'orders.external_reciept_id'
                },

                {
                    data: 'id', render: function (data, data2, type, row) {
                        var details = "<a class='btn btn-warning' href='{{$editurl}}/" + data + "/edit'> <i class='fa fa-edit ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i> </a> &nbsp; <a href='{{$details}}" + '/' + data + "' class='btn btn-primary'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";
                        if (type.status != 'Cancelled') {
                            var returns = "<button id='button" + data + "'   type='button' class='btn btn-danger return_button' id='return_products' title='Returns'><i class='fa fa-undo'></i></button> " +
                                "  <button class='btn btn-warning changeOrderStatus' type='button' title='Change Status' order_id='" + data + "' status = '" + type.status + "' > <i class='fa fa-refresh'></i></button>";
                            return details + returns;
                        } else {
                            return details;
                        }
                    }
                },
                {
                    data: 'shipment', searchable: false, render: function (data, data2, type, row) {

                        if (!data) {
                            return "<a class='btn btn-secondary' href='{{$shipmentUrl}}/" + type.id + "/shipment/create'> " +
                                "<i class='fas fa-truck' data-toggle='tooltip' data-placement='top' title='' id='shipment' ></i> </a> &nbsp;";
                        } else {
                            return "<a class='btn btn-dark' href='{{$trackUrl}}/" + data.shipment_id + "/shipment/track'> " +
                                "<i class='fas fa-road' data-toggle='tooltip' data-placement='top' title='' id='track' ></i> </a> &nbsp;" +
                                '<br>' +
                                "<a class='btn btn-success' href='" + data.label_url + "' download='proposed_file_name'  target= '_blank'> " +
                                "<i class='fas fa-download' data-toggle='tooltip' data-placement='top' title='' id='track' ></i> </a> &nbsp;"
                                ;

                        }
                    }
                },
                {
                    data: 'device_os', searchable: true, name: 'orders.device_os'

                }
            ]
        });

    $(document).ready(function () {
        $('#product_id').select2({
            matcher: matchCustom,
            templateResult: formatCustom
        });

        $('#order_status').select2({});

        $('#reloadTableButton').click(function () {
            table.ajax.reload();
        });
    });

    $(document).ready(function () {
        $("[data-toggle='tooltip']").tooltip();
    });

    function openModal(id) {
        $('#delItem').one('click', function (e) {
            e.preventDefault();
            delete_record(id);
        });
    }

    $(document).on('click', '.return_button', function (e) {
        var id = $(this).attr('id').match(/\d+/);
        returnProducts(id);
    });


    function returnProducts(id) {
        $.ajax({
            url: '{!! route('returnOrderProductsModal') !!}',
            type: "GET",
            data: {'id': id},
            success: function (response) {
                //if success reload ajax table
                if (response == 'return_date_expired') {
                    alert('This order return date has been expired');
                    return false;
                }
                if (response == 'not_found') {
                    alert('This order not found');
                    return false;
                }
                if ($('.email_modal_exist').length == 0) {

                }
                if ($('.email_modal_exist').length <= 0) {
                    $('#myModalReturn').append(response.data);
                }
                $('#myModalReturn').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });

    }

    function delete_record(id) {
        $.ajax({
            url: "{{$deleteurl}}/" + id,
            type: "GET",

            success: function (data) {
                $('#myModal').modal('hide');

                if (data == 'true') {
                    $('#items_datatable').DataTable().draw(false)

                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });

        $('#myModal').modal('toggle');
    }

    $(document).on('change', 'select', function () {


        product_id = document.getElementById("product_id").value;
        if (product_id == -1 || product_id == 0) {
            //return false;
        }
        order_status = document.getElementById("order_status").value;

        if (order_status == -1 || order_status == 0) {

            //return false;
        }
        table.ajax.reload();

    });

    $(document).on("click", ".changeOrderStatus", function () {
            myorderid = $(this).attr('order_id');
            myorderStatus = $(this).attr('status');
            $('#changeOrderId').val(myorderid);
            $('#order_statusmodal').val(myorderStatus);
            $('#changeModal').modal('toggle');


        }
    );


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
