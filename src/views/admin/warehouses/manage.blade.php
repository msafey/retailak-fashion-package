<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

<!-- Plugins css -->
    <link href="{{url('public/admin/plugins/timepicker/bootstrap-timepicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/mjolnic-bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}"
          rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
    <link href="{{url('public/admin/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>


    <style>

        .table td:nth-of-type(3) {
            width: 87px;

        }

        .table td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;

        }

        .table tr:nth-child(even) {
            width: 100px;
            /*background-color: #dddddd;*/
        }

        .table2 td:nth-of-type(2) {
            width: 200px;
        }

        .table2 td:nth-of-type(3) {
            width: 170px;
        }

        .table2 td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        .table2 tr:nth-child(even) {
            width: 100px;
            /*background-color: #dddddd;*/
        }
    </style>

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
                        Manage Stocks
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        Warehouses Stock
                    @endslot
                    You are not allowed to access this resource!
                @endcomponent             <!--End Bread Crumb And Title Section -->
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
                {!! Form::open(['url' => '/admin/import-stocks', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">

                    <div style="margin-left: 13px" class="card-text">
                        <div class="row">

                            <input type="file" id="imported_file" name="import_file"/>
                            
                            <button class="btn btn-primary" type="button" id="import"
                                    onclick="$('#import').attr('disable',true)">Import File</button>

                            <img src="{{url('public/admin/images/pleasewait.gif')}}" id="myElem" style="display: none;width: 50px;height: auto;" alt="">

                            <input type="text" hidden="" name="items_data" id="items_data" value="{{ json_encode([]) }}">

                        </div>
                        <label for="time_section">Select All:

                        </label>
                        <input type="checkbox" id="select_all" value="1">

                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <table id="items_datatable" class="table table-striped table-bordered" cellspacing="0"
                                    width="100%">
                                <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Name</th>
                                    <th>Product Sku</th>
                                    <th>Qty</th>
                                </tr>
                                </thead>


                            </table>
                        </div>
                        <div class="col-md-4" class="card card-block" style="border: 2px solid #eee;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <label for="">Import Type:</label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="radio" required="" name="request_type" class="request_type"
                                               value="transfer">
                                        <label for="">Transfer</label>
                                    </div>
                                    <div class="com-md-4">
                                        <input type="radio" checked="" required="" class="request_type"
                                               name="request_type" value="add">
                                        <label for="">Add</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="text-align: center;">
                                    <p><b>Items Selected ( <span id="item_count" style="color: red"> 0 items</span>
                                            )</b></p>

                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Warehouse From:</label>
                                    <select name="warehouse_from" disabled="" id="warehouse_from" required=""
                                            class="form-control">
                                        <option value="-1" disabled selected>Select Warehouse</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{$warehouse->id}}">{{$warehouse->name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Warehouse To:</label>
                                    <select name="warehouse_to" id="warehouse_to" required class="form-control">
                                        <option value="-1" disabled selected>Select Warehouse</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{$warehouse->id}}">{{$warehouse->name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="text-align: center;">
                                <button type="submit" class="btn btn-primary save"><i
                                            class="zmdi zmdi-plus-circle-o"></i>
                                    Proceed
                                </button>
                            </div>

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


<script type="text/javascript">
    $("#crewgender").select2();
    $('#crewdateofbirth').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    $('#crewdateofjoining').datepicker({
        autoclose: true,
        todayHighlight: true
    });
</script>


<script type="text/javascript">

    $('#warehouse_from').removeAttr('required');

    $('input[name="request_type"]').on('change', function () {
        if ($(this).val() == 'transfer') {
            $('#warehouse_from').attr('disabled', false);
            $('#warehouse_from').attr('required', true);
        } else {
            $('#warehouse_from').attr('disabled', true);
            $('#warehouse_from').removeAttr('required');
        }
    });

    $('#select_all').on('click', function () {
        if ($(this).is(':checked')) {
            var count = $('.select_item_ids').length;
            $('#item_count').html(count + ' items');
            if (count > 0) {
                var select_items = [];
                $('.select_item_ids').each(function () {
                    $(this).attr('checked', true);
                    var attr_id = $(this).attr('id').match(/\d+/);
                    var array_object = {
                        item_id: $(this).val(),
                        qty: $('#qty' + attr_id).val()
                    }
                    select_items.push(array_object);
                });
                console.log(select_items);
                $('#items_data').val(JSON.stringify(select_items));
            }
        } else {
            if ($('.select_item_ids').length > 0) {
                $('.select_item_ids').each(function () {
                    $(this).attr('checked', false);
                });
                $('#item_count').html('0 items');
                $('#items_data').val(JSON.stringify([]));

            }
        }
    });

    // $(document).ready(function () {
        var table = $('#items_datatable').DataTable({
            processing: true,
            serverSide: true,
            iDisplayLength: -1,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: '{!! route('importStocksList') !!}',
                type: "POST",
                data: function (d) {
                    d.items = document.getElementById('items_data').value
                }
            },
            columns: [
                {
                    data: 'sku', searchable: false, render: function (data, data2, type, row) {
                        return '<input  type="checkbox" id="item_id' + type.sku + '" class="select_item_ids" value="' + data + '" name="item_ids[]">';
                    }
                },
                {data: 'name', name: 'name'},
                {data: 'sku', name: 'sku'},
                {
                    data: 'qty', searchable: false, render: function (data, data2, type, row) {


                        return '<input  hidden="hidden" type="text" id="qty' + type.sku + '" class="qty" value="' + data + '" name="qty[]">' + data;
                    }
                },
            ]
        });

        $(document).on('click', '.select_item_ids', function () {
            var select_items = [];
            var count = 0;
            $('.select_item_ids').each(function (e) {
                if ($(this).is(':checked')) {
                    count += 1;
                    var attr_id = $(this).attr('id').match(/\d+/);
                    var array_object = {
                        item_id: $(this).val(),
                        qty: $('#qty' + attr_id).val()
                    }
                    select_items.push(array_object);
                }
            });
            $('#item_count').html(count + ' Items');
            $('#items_data').val(JSON.stringify(select_items));
        });

        var _validFileExtensions = [".xlsx", ".xls"];

        function Validate(oForm) {
            var arrInputs = oForm.getElementsByTagName("input");
            for (var i = 0; i < arrInputs.length; i++) {
                var oInput = arrInputs[i];
                if (oInput.type == "file") {
                    var sFileName = oInput.value;
                    if (sFileName.length > 0) {
                        var blnValid = false;
                        for (var j = 0; j < _validFileExtensions.length; j++) {
                            var sCurExtension = _validFileExtensions[j];
                            if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                                blnValid = true;
                                break;
                            }
                        }


                        if (!blnValid) {
                            alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                            return false;
                        }
                    }
                }
            }
            // $("#myElem").show();

            return true;
        }



        $(document).on('click', '#import',function () {

            if ($('#imported_file')[0].files.length == 0) {
                alert('select file to upload');
                return false;
            }
            $("#myElem").show();
            var import_file = $('#imported_file')[0].files[0];
            // console.log();

            var form = new FormData();
            form.append('import_file', import_file);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: "{{URL::to('admin/importStocks')}}",
                type: "POST",
                data: form,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);
                    $("#myElem").hide();
                    $('#items_data').val(JSON.stringify(response.data)); //store array
                    table.ajax.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Internal Error : Item is not delivered');
                }
            });
        });

    // });



    $("#warehouse_from").change(function (e) {
        var warehouse = e.target.value;
        $.ajax({
            method: 'GET',
            url: '{!! route('dest_warehouse') !!}',
            data: {'warehouse': warehouse},
            success: function (response) {
                // console.log(response[0].id);
                $('#warehouse_to').empty();
                $('#warehouse_to').append('<option value="-1" disabled selected>Select Warehouse</option>');
                $.each(response, function (index, value) {
                    $('#warehouse_to').append('<option value="' + value.id + '">' + value.name_en + '</option>');
                });
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }
        });

    });

</script>


<!-- Laravel Javascript Validation -->
<script>


</script>
<!-- JAVASCRIPT AREA -->
</body>
</html>
