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
                        Brands
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        Brands
                    @endslot
                    You are not allowed to access this resource!
            @endcomponent
            <!--End Bread Crumb And Title Section -->
                <div class="row">

                    <div class="card card-block">
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
                        <div class="card-title">
                            <div class="row card card-block" style="margin-left:8px;margin-top: 10px; ">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="col-lg-12" style="float: right">
                                            <label for="brand_id">Brand :<span style="color:red;">*</span></label>
                                            <select class="mydropdown2 select2 form-control filters select2-multiple"
                                                    required="" id="brand_id" data-placeholder="Choose Brands ..."
                                                    multiple="" name="brand_id[]">
                                                @foreach($brands as $brand)
                                                    <option value="{{$brand->id}}">
                                                        {{$brand->name_en}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="col-lg-12" style="float: right">
                                            <label for="brand_id">Seasons :<span style="color:red;">*</span></label>
                                            <select class="mydropdown3 select2 form-control filters select2-multiple"
                                                    required="" data-placeholder="Choose Seasons ..." id="season_id"
                                                    multiple="" name="season_id[]">
                                                @foreach($seasons as $season)
                                                    <option value="{{$season->id}}">
                                                        {{$season->name_en}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="col-lg-12" style="float: right">
                                            <label for="brand_id">Stock Level :<span style="color:red;">*</span></label>
                                            <select class="form-control" required=""
                                                    data-placeholder="Choose Stock Level ..." id="stock_level"
                                                    name="stock_level">
                                                <option selected="" value="all">
                                                    All
                                                </option>
                                                <option value="1">
                                                    >0
                                                </option>
                                                <option value="2">
                                                    =0
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="col-lg-12" style="float: right">
                                            <label for="brand_id">Minimum Price:<span
                                                    style="color:red;">*</span></label>
                                            <input type="number" name="min_price" id="min_price" value=""
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="col-lg-12" style="float: right">
                                            <label for="brand_id">Maximum Price :<span
                                                    style="color:red;">*</span></label>
                                            <input type="number" id="max_price" name="max_price" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="col-lg-12" style="float: right">
                                            <label for="brand_id">Barcode:<span style="color:red;">*</span></label>
                                            <input type="number" name="barcode" id="barcode" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="col-lg-12" style="float: right">
                                            <label for="warehouse_id">Warehouse :<span
                                                    style="color:red;">*</span></label>
                                            <select class="form-control filters" required=""
                                                    data-placeholder="Choose Warehouse ..." id="warehouse_id"
                                                    name="warehouse_id">
                                                <option value="">Choose Warehouse</option>
                                                @foreach($warehouses as $warehouse)
                                                    <option value="{{$warehouse->id}}">
                                                        {{$warehouse->name_en}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="col-lg-12">
                                            <a id="csv" href="{{route('export.csv.stock')}}"
                                               class="form-control btn btn-success"><i
                                                    class="fa fa-download"></i>CSV</a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="col-lg-12">
                                            <a id="csv" href="{{route('export.pdf.stock')}}"
                                               class="form-control btn btn-custom"><i
                                                    class="fa fa-download"></i>PDF</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="col-lg-12">
                                            <button id="search" class="form-control btn btn-primary"><i
                                                    class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-text">
                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>Barcode</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                {{--                                    <th>Season</th>--}}
                                <!-- <th>Size</th> -->
                                    {{--                                    <th>Brand</th>--}}
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                </tr>
                                </thead>


                            </table>
                            <input type="text" hidden="" disabled="" id="clicked" value="0">

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
</div>
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
    $(".mydropdown1").select2();
    $(".mydropdown2").select2();
    $(".mydropdown3").select2();

    <?php $editurl = url('/admin/brands/');?>
    <?php $deleteurl = url('/admin/brands/delete'); ?>
    $('#search').on('click', function () {
        // var cons = $("select[name='category_id[]']")
        // .map(function(){return $(this).val();}).get();
        // console.log(cons);
        // return false;
        $('#clicked').val(1);
        table.ajax.reload();
    });
    var table = $('#items_datatable').DataTable({
        processing: true,
        serverSide: false,
        lengthMenu: [ 10, 25, 50, 75, 100, 500],
        ajax: {
            url: '{!! route('stockReportsList') !!}',
            type: "GET",
            data: function (d) {

                d.category_id = JSON.stringify($("select[name='category_id[]']")
                    .map(function () {
                        return $(this).val();
                    }).get()),
                    d.brand_id = JSON.stringify($("select[name='brand_id[]']")
                        .map(function () {
                            return $(this).val();
                        }).get()),
                    d.season_id = JSON.stringify($("select[name='season_id[]']")
                        .map(function () {
                            return $(this).val();
                        }).get()),
                    d.stock_level = document.getElementById('stock_level').value,
                    d.barcode = document.getElementById('barcode').value,
                    d.max_price = document.getElementById('max_price').value,
                    d.min_price = document.getElementById('min_price').value,
                    d.warehouse_id = $('#warehouse_id').val(),
                    d.clicked = $('#clicked').val();
            }
        },
        columns: [
            {data: 'warehouse', name: 'warehouse'},
            {data: 'item_code', name: 'item_code'},
            {
                data: 'name_en', render: function (name1, name2, type, row) {
                    if (name1.length > 40) {
                        name1 = name1.substr(0, 38) + '...</span>';
                    }
                    return name1;
                }
            },
            {data: 'cat_name', name: 'cat_name'},
            {data: 'standard_rate', name: 'standard_rate'},
            {data: 'stock_qty', name: 'stock_qty'},
            {data: 'color', name: 'color'},
            {data: 'size', name: 'size'},
        ]
    });


    $(document).ready(function () {
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

    function delete_record(id) {

        // ajax delete data to database
        $.ajax({
            url: "{{$deleteurl}}/" + id,
            type: "GET",

            success: function (data) {
                //if success reload ajax table
                // console.log(data.success);
                $('#myModal').modal('hide');

                if (data == 'true') {
                    $('#items_datatable').DataTable().draw(false)

                }
                //                reload_table();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Error deleting data');
            }
        });

        $('#myModal').modal('toggle');

        //            setTimeout(function () {
        //                table.ajax.reload();
        //            }, 300);


    }


</script>

<!-- JAVASCRIPT AREA -->
</body>
</html>
