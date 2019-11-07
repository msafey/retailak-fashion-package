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
@include('layouts.modals.statusModal')
<!-- Start right Content here -->
    <div class="content-page">
        <!-- Start content -->


        <div class="content">
            <div class="container">
                <!-- Bread Crumb And Title Section -->
                @component('layouts.admin.breadcrumb')
                    @slot('title')
                        Products
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        Products
                    @endslot
                    You are not allowed to access this resource!
                @endcomponent            <!--End Bread Crumb And Title Section -->
                @include('layouts.admin.errorMsg')
                <div class="card card-block">

                    <div class="card-title">
                        <a href="{{url('/admin/products/create')}}" class="btn btn-rounded btn-primary"><i
                                    class="zmdi zmdi-plus-circle-o"></i> Add New Product</a>
                        <div class="col-sm-3">
                            <select id="item_group" class="form-control">
                                <option disabled selected value="0">Select Category</option>
                                <option value="-1">All Categories</option>
                                @foreach($categories as $key=> $category)
                                    <optgroup label="{{$key}}">
                                        @foreach($category as $cat)
                                            <option value="{{$cat->id}}">{{$cat->name_en}}-{{$cat->name}}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <a href="#" id="order2" class="btn btn-rounded btn-primary"><i
                                    class="zmdi zmdi-plus-circle-o"></i>Order Of Products According To Category</a>
                        <a href="{{url('admin/product/reordering')}}" id="order3"
                           class="btn btn-rounded btn-primary"><i class="zmdi zmdi-plus-circle-o"></i> Products
                            Reorder</a>

                        <a href="{{url('admin/import/products/step1')}}" id="order3"
                           class="btn btn-rounded btn-primary"><i class="zmdi zmdi-plus-circle-o"></i> Import Products
                            From Excel</a>

                        <a href="{{url('admin/importStocks')}}" id="order3" class="m-a-1 btn btn-rounded btn-primary"><i
                                    class="zmdi zmdi-plus-circle-o"></i> Import Stock</a>

                        <div class="row" style="margin-left:8px;margin-top: 10px; ">
                            <div class="col-sm-3">
                                <input type="checkbox" name="active" value="1" checked id="active">Show Active Products
                            </div>
                            <div class="col-sm-3">
                                <input type="checkbox" name="inactive" value="" id="inactive">Show Inactive Products
                            </div>
                            @if(checkProductConfig('foods'))
                                <div class="col-sm-3">
                                    <input type="checkbox" name="food_extras" value="1" id="food_extras">Show Extra
                                    Products
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-text">
                        <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>SKU</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Selling Price</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                        </table>
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
@include('layouts.admin.datatable')
<script>

        <?php $editurl = url('/admin/products/');?>
        <?php $changestatus = url('/admin/product/status');?>
        <?php $order = url('/admin/products');?>
        <?php $manageUrl = url('/admin/products/');?>
        <?php $details = url('/admin/product-details/');?>
    var item_group = 0;
    var ckbox = $('#active');
    var ckbox1 = $('#inactive');
    if ($('#food_extras').is(':checked')) {
        $('#food_extras').val(1);
    } else {
        $('#food_extras').val(0);
    }

    var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {

            url: '{!! route('productsList') !!}',
            type: "GET",
            data: function (d) {
                d.item_group = document.getElementById('item_group').value,
                    d.active = document.getElementById('active').value,
                    d.inactive = document.getElementById('inactive').value,
                    d.food_extras = $('#food_extras').val()
            }
        },
        columns: [
            {data: 'id', name: 'products.id'},
            {data: 'item_code', name: 'products.item_code'},

            {

                data: 'name_en', render: function (name1, name2, type, row) {
                    if (name1.length > 40) {
                        name1 = name1.substr(0, 38) + '...</span>';
                    }
                    if (type.name.length > 40) {
                        type.name = type.name.substr(0, 38) + '...</span>';
                    }
                    return name1 + '   <b>EN</b>' + ' <br> ' + type.name + '   <b>AR</b>';
                }
            },
            {data: 'name_en', name: 'categories.name_en'},
            {data: 'price.rate', name: 'price.rate'},

            {
                data: 'image.image', name: 'image.image', searchable: false, render: function (image, type, row) {
                    return "<img class='img-data-tables' height='100px;' width='150px' style='object-fit: contain' src='{{url('/public/imgs/products')}}" + '/' + image + "'>"
                }
            },

            {
                data: 'id', render: function (data, data2, type, row) {

                    var edit = "<a href='{{$editurl}}" + '/' + data + '/edit' + "' class='btn btn-w" + "arning'><i class='fas fa-edit' data-toggle='tooltip' data-placement='top' title='Edit Product' ></i></a>&nbsp;";
                    var deactivate = "<button data-toggle='modal' data-target='#myModal' onclick='openModal(" + data + ")' type='button' class='btn btn-danger' title='De-Activate'>De-Activate</button>";
                    var activate = "<button data-toggle='modal' data-target='#myModal' onclick='openModal(" + data + ")' type='button' class='btn btn-danger' title='Activate'>Activate</button>"

                    var details = "<a href='{{$details}}" + '/' + data + "' class='btn btn-primary'><i class='fa fa-list ' data-toggle='tooltip' data-placement='top' title='' id='manage' ></i></a>&nbsp;";
                    if (type.active == 1) {
                        return details + edit + deactivate;
                    } else {
                        return details + edit + activate;
                    }
                }
            }]
    });

    $('input').change(function () {
        if (ckbox.is(':checked')) {
            $('#active').val(1);
        } else {
            $('#active').val(0);
        }
        if (ckbox1.is(':checked')) {
            $('#inactive').val(1);
        } else {
            $('#inactive').val(0);
        }
        if (ckbox1.is(':checked') && ckbox.is(':checked')) {
            $('#active').val(-1);
            $('#inactive').val(-1);
        }
        if ($('#food_extras').is(':checked')) {
            $('#food_extras').val(1);
        } else {
            $('#food_extras').val(0);
        }
        table.ajax.reload();

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

    function delete_record(id) {
        // ajax delete data to database
        $.ajax({
            url: "{{$changestatus}}/" + id,
            type: "GET",
            success: function (data) {
                //if success reload ajax table
                $('#myModal').modal('hide');
                if (data == 'success') {
                    $('#items_datatable').DataTable().draw(false)
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });
    }

    $('select').change(function () {
        item_group = document.getElementById("item_group").value;
        if (item_group == -1 || item_group == 0) {
            $('#order2').addClass('disabled');
        } else {
            $("#order2").attr('href', '{{$order}}' + '/' + item_group + '/order');
            $('#order2').removeClass('disabled');
        }
        table.ajax.reload();
    });
    $(document).ready(function () {
        if (item_group == 0) {
            $('#order2').addClass('disabled');
        }
        $('#reloadTableButton').click(function () {
            table.ajax.reload();
        });
    });
</script>
<!-- JAVASCRIPT AREA -->
</body>
</html>
