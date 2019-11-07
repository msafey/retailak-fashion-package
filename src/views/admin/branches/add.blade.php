<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')
    <link href="{{url('public/admin/plugins/select2/css/select2.css')}}" rel="stylesheet" type="text/css"/>
    <!-- App Favicon -->
    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">

    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
    <!-- Switchery css -->


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
                        Branches
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                        Branches
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
                {!! Form::open(['url' => '/admin/branches', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="branch_name">Branch Name <span style="color:red;">*</span>:</label>
                                <input  required data-parsley-maxlength="70" name="branch_name" type="text"
                                       class="form-control" id="branch_name" value="{{old('branch_name')}}"
                                       placeholder="Branch Name "/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="warehouse_name">Warehouse Name <span style="color:red;">*</span>:</label>
                                <select name="warehouse_id" required class="col-md-2 form-control">
                                    <option value="">Choose Warehouse...</option>
                                    @foreach($warehouses as $warehouse)
                                        <option  value="{{$warehouse->id}}" @if (old('warehouse_id') == $warehouse->id) selected @endif>
                                            {{$warehouse->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="district_id">District Name <span style="color:red;">*</span>:</label>
                                <select name="district_id[]"  multiple="multiple" class="form-control select2">
                                    <option value="">Choose District...</option>
                                    @foreach($districts as $district)
                                        <option value="{{$district->id}}" @if (old('district') == $district->id) selected @endif>
                                            {{$district->district_en}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <button style="margin-left: 25px" type="submit" class="btn btn-primary"><i
                                        class="zmdi zmdi-plus-circle-o"></i>
                                Add Branch
                            </button>
                        </div>

                    </div>
                </div>

                {!! Form::close() !!}
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



<!-- JAVASCRIPT AREA -->


@include('layouts.admin.javascript')
<script src="{{url('public/admin/plugins/select2/js/select2.js')}}"></script>
<script src="{{url('/public/')}}/prasley/parsley.js"></script>

<script>
    var resizefunc = []
    $(document).ready(function(){
        $(".select2").select2({
            'width': '100%'
        });
    });
</script>
<!-- Laravel Javascript Validation -->


<!-- JAVASCRIPT AREA -->
</body>
</html>