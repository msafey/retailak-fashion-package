<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
    <!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css" />
    <!-- App Favicon -->
    <link rel="shortcut icon" href="{{url('public/admin/images/R.jpg')}}">
    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>
    <!-- Switchery css -->

     <!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
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
                                           Change Password
                                   @endslot

                                   @slot('slot1')
                                           Home
                                   @endslot

                                   @slot('current')
                                           Change Password
                                   @endslot
                                   You are not allowed to access this resource!
                        @endcomponent    

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


    {!! Form::open(['url' => ['/admin/cmsusers/changepassword',$cmsuser->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}




                    <div class="card card-block">
                    
<div class="col-md-9">
   
    <label for="password" class="col-sm-4 control-label">New Password</label>
    <div class="col-sm-8">
        <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
        </div>
    </div>
    <label for="password_confirmation" class="col-sm-4 control-label">Re-enter Password</label>
    <div class="col-sm-8">
        <div class="form-group">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Re-enter Password">
        </div>
    </div>
</div>







                            




                            <div class="row">
                                <div class="col-sm-12"><button type="submit" style="margin-left: 12px" class="btn btn-primary">Submit</button></div>
                            </div>
                        </div>
                    </div>

                    <!-- /.modal -->
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
  
                            
    <!-- container -->
    </div>
    <!-- content -->
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
    
<!-- Required datatable js -->
<script src="{{url('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
<!-- Buttons examples -->
<script src="{{url('public/admin/plugins/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/jszip.min.js')}}"></script>

<script src="{{url('public/admin/plugins/datatables/vfs_fonts.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.html5.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.print.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/buttons.colVis.min.js')}}"></script>
<!-- Responsive examples -->
<script src="{{url('public/admin/plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{url('/public/admin/')}}/js/bootstrap-datetimepicker.js"></script>
  
</body>

</html>