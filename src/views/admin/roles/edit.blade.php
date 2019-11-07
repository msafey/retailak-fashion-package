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
        <link href="{{url('public/admin/plugins/mjolnic-bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}"
              rel="stylesheet">
        <link href="{{url('public/admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
        <link href="{{url('public/admin/plugins/clockpicker/bootstrap-clockpicker.min.css')}}" rel="stylesheet">
        <link href="{{url('public/admin/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">

        <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
        <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

        <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>




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
                                               Edit Role
                                       @endslot

                                       @slot('slot1')
                                               Home
                                       @endslot

                                       @slot('current')
                                               Roles
                                       @endslot
                                       You are not allowed to access this resource!
                                       @endcomponent                          <!--End Bread Crumb And Title Section -->
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

    <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 card card-block">


        {!! Form::open(['url' => ['/admin/roles', $role->id],'method'=>'PATCH', 'id'=>'form','files' => true, 'data-parsley-validate'=>'']) !!}


             <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 ">
            <br>
                <div class="col-sm-6  change_direction ">
                    <label for="name" class="">Name<span style="color:red;">*</span>:</label>
                    <input name="name" type="text" value="{{$role->name}}" style=""  placeholder="Name" class="form-control" id="name" placeholder="" required="required" data-parsley-maxlength="150"/>
                </div>
        </div>



    <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12">
            <br>
                <div class="col-sm-6   ">
                    <label for="display_name" class="">Display Name<span style="color:red;">*</span>:</label>

                    <input name="display_name" value="{{$role->display_name}}" type="text" style=""  placeholder="Display Name " class="form-control" id="display_name" placeholder="" required="required" data-parsley-maxlength="150"/>

                </div>
      </div>
    


        <div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12 ">
            <br>

                <div class="col-sm-6   ">
                        <label for="desc" class="">Description<span style="color:red;">*</span>:</label>
                    <br>
                    <textarea name="description"  rows="10" cols="80"  class="form-control col-sm-6" cols="7"
                          required >{{$role->description}}</textarea>
                </div>
        </div>


<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12 col-xl-12  " style="margin-top: 30px;">
  <div class=" ">
    <h3>Select Permissions</h3>
    <br>
    <div>
        <div class="checkbox checkbox-custom" style="margin-bottom: 50px">
          <input type="checkbox"   id="checkAll"   data-parsley-multiple="groups"
          data-parsley-mincheck="2">
          <label for="checkAll">Check All</label>
        </div>

      <div class="checkbox checkbox-custom" style="margin-bottom: 50px">
        
      <div class="checkbox checkbox-pink col-sm-offset-1" >
        @foreach($permissions as $permission)
        <div class="col-sm-3">
          <input id="checkbox{{$permission->id}}" name="permission[]" type="checkbox"
          data-parsley-multiple="groups"
          data-parsley-mincheck="1" {{in_array($permission->id,$role_permissions)?"checked":""}}   name="permission[]" value="{{$permission->id}}" >
          <label for="checkbox{{$permission->id}}" class="" style="margin-right: 40px; margin-bottom: 20px;"> {{$permission->name}} </label>
        </div>
        @endforeach
        <br>
      </div>
    </div>
  </div>
</div>



        <div class="form-group  col-lg-12 col-sm-12 col-xs-9 col-md-12 col-xl-12">
            <div class="col-sm-3  change_direction "">
                <button type="submit" class="btn btn-primary"><i
                            class=""></i>
        Save Changes
                </button>
                <button id="back" onclick="history.go(-1);" type="button" class="btn btn-default"><i
                            class="zmdi "></i>
        Back
                </button>
            </div>
        </div>
                        {!! Form::close() !!}
                    </div>
                </div>


            </div>


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



<script src="{{url('/public/')}}/prasley/parsley.js"></script>


{{--<script src="{{url('public/admin/pages/jquery.form-pickers.init.js')}}"></script>--}}


<script type="text/javascript">
    
       $(document).ready(function() {
        $("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});
      });
</script>



<!-- JAVASCRIPT AREA -->
</body>
</html>