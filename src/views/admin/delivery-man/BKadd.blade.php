<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
@include('layouts.admin.scriptname_desc')

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
            @include('layouts.admin.breadcrumb')
            <!--End Bread Crumb And Title Section -->
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
                {!! Form::open(['url' => '/admin/delivery/man', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="card card-block row">
                    <div style="margin-left: 13px" class="card-text">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="crewname">Name <span style="color:red;">*</span>:</label>
                                <input required data-parsley-maxlength="50" name="name" type="text"
                                       class="form-control" id="crewname"
                                       placeholder="Delivery Man Name "/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="crewemail">Email <span style="color:red;">*</span>:</label>
                                <input required  name="crewemail" type="email"
                                       class="form-control" id="crewemail"
                                       placeholder="Delivery Man Email "/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-4"><label for="gender">Gender <span style="color:red;">*</span>:</label>
                            <select class="form-control select2" name="gender" id="crewgender">
                                <option value="">Select gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-4"><label for="crewdateofbirth">Date of birth <span style="color:red;">*</span>:</label>
                            <input required class="form-control" type="date" name="date_of_birth" placeholder="mm/dd/yyyy" id="crewdateofbirth" />
                        </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="crewdateofjoining">Date of joining <span style="color:red;">*</span>:</label>
                            <input  required class="form-control" type="date" name="date_of_joining" placeholder="mm/dd/yyyy" id="crewdateofjoining" />
                        </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="crewname">Phone <span style="color:red;">*</span>:</label>
                                <input required data-parsley-type="number" name="mobile" type="text"
                                       class="form-control" id="crewname"
                                       placeholder="Delivery Man Mobile "/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="crewname">Password <span style="color:red;">*</span>:</label>
                                <input required data-parsley-maxlength="12" name="password" type="password"
                                       class="form-control" id="crewname"
                                       placeholder="Delivery Man Name "/>
                            </div>
                        </div>

                        <div class="row">
                            <button style="margin-left: 25px" type="submit" class="btn btn-primary"><i
                                        class="zmdi zmdi-plus-circle-o"></i>
                                Add Dlivery Man
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
<script>
    var resizefunc = [];
</script>
<script src="{{url('components/select2/dist/js/select2.js')}}"></script>
<script src="{{url('public/admin/plugins/moment/moment.js')}}"></script>
<script src="{{url('public/admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
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
<!-- JAVASCRIPT AREA -->


@include('layouts.admin.javascript')
<script src="{{url('/public/')}}/prasley/parsley.js"></script>


<!-- Laravel Javascript Validation -->


<!-- JAVASCRIPT AREA -->
</body>
</html>