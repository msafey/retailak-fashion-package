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
                                          Edit CMS User
                                   @endslot

                                   @slot('slot1')
                                           Home
                                   @endslot

                                   @slot('current')
                                           CMS Users
                                   @endslot
                                   You are not allowed to access this resource!
                                   @endcomponent                      <!--End Bread Crumb And Title Section -->
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
    {!! Form::open(['url' => ['admin/cmsusers', $cmsuser->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}
                    <div class="card card-block">
                        <div class="card-title">
                            Add New CMS User
                        </div>
                      



                      <div class="row">
                            <div class="form-group">
                                <label for="name" class="col-md-2 control-label">Name</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" value="{{ $cmsuser->name}}" name="name" required>
                                </div>
                            </div>
                            </div>



                            <div class="row">
                            <div class="form-group">
                                <label for="email" class="col-md-2 control-label">Email</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" value="{{ $cmsuser->email}}" name="email" required>
                                </div>
                            </div>
                            </div>




                      

                            <div class="row">
                            <div class="form-group">
                                <label for="role" class="col-md-2 control-label">Role</label>
                                <div class="col-sm-6">
                                    <select name="role" id="role" class="form-control" required="required">
                                        <option value="" class="select-hr">-- Choose Role --</option>
                                        <option disabled="disabled">ــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــ</option>
                                        @foreach($all_roles as $role)
                                        <option value="{{$role->id}}" @if($role->id == $selected_role) selected @endif>{{ $role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group">
                                <label for="role" class="col-md-2 control-label">Warehouses</label>
                                <div class="col-sm-6">
                                    <select id="related_warehouses" class="form-control" required="" @if($selected_role != 3) disabled="" @endif name="warehouse_id">
                                        <option disabled="" selected="" value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                       <option @if($cmsuser->warehouse_id == $warehouse->id)selected @endif value="{{$warehouse->id}}">
                                           {{$warehouse->name_en}}
                                       </option>
                                    @endforeach
                                    </select> 
                                </div>
                            </div>
                        </div>
                            




                            <div class="row">
                                <div class="col-sm-12"><button type="submit" style="margin-left: 12px" class="btn btn-primary">Submit</button></div>
                            </div>
                        </div>
                    </div>

                    <input value ="@if($auth_user->id == $cmsuser->id)1 @endif" type="hidden" id="user" name="auth_user">


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
    <script type="text/javascript">
   
    $(document).on('change','#role',function(){
        if($(this).val() == 3){
            $('#related_warehouses').attr('disabled',false);
        }else{
            $('#related_warehouses').attr('disabled',true);
        }
    });
        
         var auth_user = document.getElementById('user').value;
         if(auth_user == 1){
           $("#role").prop("disabled", true);
        // /console.log(1)
         }
    
        $(".mydropdown2").select2();

    function makecode() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < 8; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }
    </script>
    <!-- Laravel Javascript Validation -->
    <!-- JAVASCRIPT AREA -->
</body>

</html>