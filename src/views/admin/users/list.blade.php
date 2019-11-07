<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>


    <!-- App Favicon -->
    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">

    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
    <!-- Switchery css -->
    <link href="{{url('public/admin/plugins/switchery/switchery.min.css')}}" rel="stylesheet"/>

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <style>
        a{
            color: black;
        }
        .header-title {
            font-size: 1.1rem;
            text-transform: none;
        }
        .pagination{
            text-align: center;
        }
        .pagination li a {
            font-size:14px;
            padding:4px;
            border:1px solid #eee;
            border-radius: 4px;
        }

        .pagination li.active span {
            font-size:14px;
            padding:4px;
            background-color: #eee;
            border:1px solid #eee;
            border-radius: 4px;
        }
        .pagination li{
            float:left;
            margin-left: 4px;
            margin-right: 4px;
            list-style: none;
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
                @if(session()->has('success'))
                    <div class="alert alert-success text-dark">
                        {{ session()->get('success') }}
                    </div>
                @endif
                <!-- Bread Crumb And Title Section -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title"> Customers </h4>
                            <ol class="breadcrumb p-0">
                                <li>
                                    <a href="{{url('/')}}">Dashboard</a>
                                </li>
                                <li class="active">
                                    Customers
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!--End Bread Crumb And Title Section -->
                <div class="row">
                    <div class="card card-block">
                        <div class="card-text">
                            <div class="row">
                                <div class="row" id="testing">

                                </div>
                                <div class="row" style="width: 98%;margin: 11px;">
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
                                    <div class="col-sm-12" >
                                        <div class="card-box table-responsive">
                                            <div class="card-title">
                                                <!-- Add menu Button -->
                                                {{--<a href="{{url('/admin/users/create')}}" class="btn btn-rounded btn-primary">--}}
                                                    {{--<i class="zmdi zmdi-plus-circle-o"></i> Add New Customer--}}
                                                {{--</a>--}}
                                                <!-- Add menu Button End-->
                                                <div style="float: right;">
                                                    {!! Form::open(['url' => '/admin/user/importExcel','onsubmit' => 'return Validate(this)','class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}
                                                    <input type="file" id="imported_file" name="import_file" />
                                                    <button class="btn btn-primary" id="import"
                                                            onclick="$('#import').attr('disable',true)">Import File</button>
                                                    <img src="{{url('public/admin/images/pleasewait.gif')}}" id="myElem" style="display: none;width: 50px;height: auto;" alt="">
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                        <h4 class="m-t-0 header-title"><b>Customers</b></h4>
                                        <p class="text-muted font-13 m-b-30"> Customers </p>
                                        <div id="datatable-buttons_wrapper" style="padding-left: 0px;" class="col-md-6">
                                            <a href="{{ url('admin/export') }}" class="btn btn-success">
                                                <span class="zmdi zmdi-file"></span> Export Excel
                                            </a>
                                        </div>
                                        <div class="col-sm-12" style="text-align: right; margin-bottom: 4px;">
                                        </div>
                                        <table id="datatable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                            </tr>
                                            </thead>
                                            <tbody id="usersTableBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->

<!-- End content-page -->
<!-- Footer Area -->@include('layouts.admin.footer')

<!-- End Footer Area-->
<!-- END wrapper -->
<script>
    var resizefunc = [];
</script>

<!-- JAVASCRIPT AREA -->
<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
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
    var _validFileExtensions = [".xlsx",".xls"];
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
        $("#myElem").show();

        return true;
    }
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 50,
        order: [[0, "desc"]],
        ajax: {
            url: '{!! route('userslist') !!}',
            type: "GET",
        },
        columns: [
            {
                data: 'name', searchable: true, render: function (data , x , row) {
                    return '<a href="{{url('admin/user/details')}}/'+ row.id +'">' + data +  '</a>';
                }
            },
            {data: 'phone', searchable: true, name: 'phone'},
            {data: 'address',  render: function (data) {
                    addresses = 'Address Not Mentioned';
                    for (let address of data) {
                        if (address['title']  !== null) {
                            addresses = address['title'] + '<br>';
                        }
                    }
                    return addresses;
                }
            },
        ]
    });

</script>

</body>
</html>
