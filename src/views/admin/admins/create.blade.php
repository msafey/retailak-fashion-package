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
                                           Add CMS User
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
                    {!! Form::open(['url' => '/admin/cmsusers', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}
                    <div class="card card-block">
                        <div class="card-title">
                            Add New CMS User
                        </div>




                      <div class="row">
                            <div class="form-group">
                                <label for="name" class="col-md-2 control-label">Name</label>
                                <div class="col-md-6">
                                    <input id="name" value="{{old('name')}}" type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            </div>



                            <div class="row">
                            <div class="form-group">
                                <label for="email" class="col-md-2 control-label">Email</label>
                                <div class="col-md-6">
                                    <input id="email" value="{{old('email')}}" type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                            </div>




                            <div class="row">
                            <div class="form-group">
                                <label for="password" class="col-md-2 control-label">Password</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                </div>
                            </div>
                            </div>
                            <div class="row">
                            <div class="form-group">
                                <label for="password-confirm" class="col-md-2 control-label">Confirm Password</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                            </div>


                            <div class="row">
                            <div class="form-group">
                                <label for="role" class="col-md-2 control-label">Role</label>
                                <div class="col-sm-6">
                                    <select name="role" value="{{old('role')}}" id="role" class="form-control" required="required">
                                        <option value="" class="select-hr">-- Choose Role --</option>
                                        <option disabled="disabled">ــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــــ</option>
                                        @foreach($user_role as $role)
                                            <option value="{{$role->id}}">{{ $role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group">
                                <label for="role" class="col-md-2 control-label">Warehouses</label>
                                <div class="col-sm-6">
                                    <select id="related_warehouses" class="form-control"  disabled="" name="warehouse_id">
                                        <option disabled="" selected="" value="">Select Warehouse</option>
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
    <script type="text/javascript">
    $('#demo-form').parsley();
    $(function() {
        $('#datetimepicker1').datetimepicker({
            icons: {
        time: 'zmdi zmdi-time',
        date: 'zmdi zmdi-calendar',
        up: 'zmdi zmdi--up',
        down: 'zmdi zmdi--down',
        //previous: 'glyphicon glyphicon-chevron-left',
        previous: 'zmdi zmdi-backward',
        next: 'zmdi zmdi-right',
        today: 'zmdi zmdi-screenshot',
        clear: 'zmdi zmdi-trash',
        close: 'zmdi zmdi-remove'
    },
        });
        $('#datetimepicker2').datetimepicker({
            icons: {
        time: 'zmdi zmdi-time',
        date: 'zmdi zmdi-calendar',
        up: 'zmdi zmdi--up',
        down: 'zmdi zmdi--down',
        //previous: 'glyphicon glyphicon-chevron-left',
        previous: 'zmdi zmdi-backward',
        next: 'zmdi zmdi-right',
        today: 'zmdi zmdi-screenshot',
        clear: 'zmdi zmdi-trash',
        close: 'zmdi zmdi-remove'
    },
        });
    });

    $(document).on('change','#role',function(){
        if($(this).val() == 3){
            $('#related_warehouses').attr('disabled',false);
        }else{
            $('#related_warehouses').attr('disabled',true);
        }
    });
    $(document).ready(function() {
        $(".mydropdown2").select2();
        $("#showUserInput").hide();
        $("#generateCodeButton").click(function() {
            $("#promocode").val(makecode());


        });
        $('#noOfUsers').on('keyup',function(){
            var usersNo = $(this).val();
            if(usersNo == 1){
                $("#showUserInput").show();
                $("#showUserInput").click(function(){
                     var table = $('#users_datatable').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                order: [0, 'asc'],
                ajax: '{!! route('userslist') !!}',
                columns: [
                    {data: 'id', searchable: false, render: function (data, type, row) {
                        return "<input type='radio' name='user' class='userRadio' value='" + data +" '>"
                       }
                    },
                    {data: 'name', name: 'name'},

                    {data: 'email', name: 'email'}


                    ],
                drawCallback: function() {
                  $('.userRadio').change(function(){

                         $('#modal').modal().hide();

                                if($('input[name=user]:checked').length > 0)
                                {
                                    var selectedUser = $(this).val();
                                    $.ajax({
                                      url: "{!! route('getuser') !!}",
                                      data: { userid:selectedUser },
                                      success:function(data) {
                                      $('#showUserEmail').html(data);
                                    }});
                                    $('#showUserEmail').show();

                                    $('#modal').modal('toggle');
                                }
                           });
                    }
                   //do whatever
                });



                });
            }
            else{
                $("#showUserInput").hide();
            }

        });





       //DataTable



    });


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
