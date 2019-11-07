<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css"/>
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
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            /* display: none; <- Crashes Chrome on hover */
            -webkit-appearance: none;
            margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
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
                        PromoCodes
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        PromoCodes
                    @endslot
                    You are not allowed to access this resource!
                @endcomponent                    <!--End Bread Crumb And Title Section -->
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
                {!! Form::open(['url' => '/admin/promocode/add', 'class'=>'form-hirozontal ','id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}
                <div class="card card-block">
                    <div class="card-title">
                        <h2>Add a new promocode</h2>
                        <br/>
                    </div>
                    <div style="margin-left: 5px" class="card-text">
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-group" for="promocode">Code
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="code" required="required" id="promocode"/>
                                <button id="generateCodeButton" type="button" class="btn btn-sm btn-primary"> Generate
                                    Code
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-group" for="min_required_price">
                                    Min. Required Price To Apply
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="number" step="any" min="0" max="10000000000"
                                       name="min_required_price" required="required" id="min_required_price"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-group" for="max_required_price">
                                    Max. Required Price To Apply
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="number" step="any" min="0" max="10000000000"
                                       name="max_required_price" required="required" id="max_required_price"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 form-group">
                                Free Shipping
                                <input type="checkbox" name="freeshipping" data-plugin="switchery"
                                       data-color="#ff5d48"/>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2 form-group">
                                Discount
                                <input type="checkbox" id="discountEnabler" data-plugin="switchery"
                                       data-color="#ff5d48"/>

                            </div>
                            <div class="col-sm-6" id="discountBox" style="display: none;">
                                <div class="row">
                                    <div class="col-sm-6" style="margin:0">
                                        <label for="percentage" class="c-input c-radio">
                                            <input type="radio" name="type" id="percentage" value="percentage">
                                            <span class="c-indicator"></span>Percentage </label>
                                        <label for="amount" class="c-input c-radio">
                                            <input type="radio" name="type" id="amount" value="amount">
                                            <span class="c-indicator"></span>Amount </label>

                                    </div>
                                    <div class="col-sm-2">
                                        <input type="number" step="any" id="rate" name="rate" style="width:80px;">
                                    </div>
                                    <div class="col-sm-2" id="typeSympole">

                                    </div>

                                </div>
                            </div>
                        </div>


                        {{--<div class="row">


                        </div>--}}

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-group" for="usercount">No. Of Users
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" data-parsley-type="number" id="noOfUsers" name="nofusers"/>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-group" for="usage_per_user">Usage Per User
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="number" step="1"
                                       id="usage_per_user" name="usage_per_user"/>
                            </div>
                        </div>

                        <div class="row" id="showUserEmail" style="display: none;">
                            <div class="col-sm-3">
                            </div>
                        </div>

                        <div class="row" id="showUserInput" style="display: none;">
                            <div class="col-sm-3">
                                <button class="btn btn-primary waves-effect waves-light" type="button"
                                        data-toggle="modal" data-target=".bs-example-modal-lg">Choose A User
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-group" for="from">From
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' name="sfrom" class="form-control"/>
                                    <span class="input-group-addon">
                                        <span class="zmdi zmdi-calendar"></span>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-group" for="to">To
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <div class='input-group date' id='datetimepicker2'>
                                    <input type='text' name="sto" class="form-control"/>
                                    <span class="input-group-addon">
                                        <span class="zmdi zmdi-calendar"></span>
                                        </span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-group" for="status">Status
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <label class="c-input c-radio">
                                    <input id="Active" name="status" checked value="active" type="radio">
                                    <span class="c-indicator"></span> Active
                                </label>
                                <label class="c-input c-radio">
                                    <input id="inactive" required="required" value="inactive" name="status"
                                           type="radio">
                                    <span class="c-indicator"></span> In Active
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="submit" style="margin-left: 12px" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade bs-example-modal-lg" id="modal" tabindex="-1" role="dialog"
                     aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myLargeModalLabel">Choose User</h4>
                            </div>
                            <div class="modal-body">
                                <h4 class="m-t-0 header-title"><b>Users</b></h4>
                                <table id="users_datatable" class="table table-striped table-bordered"
                                       style="width:100%;">
                                    <thead>
                                    <tr>
                                        <th>select</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                    </tr>
                                    </thead>


                                    <tbody>
                                    {{--   @foreach($users as $user)

                                          <tr>
                                              <td>
                                                  <input type="radio" name="user" value="{{$user->id}}">
                                              </td>
                                              <td>
                                                  {{$user->name}}
                                              </td>
                                              <td>
                                                  {{$user->email}}
                                              </td>

                                          </tr>


                                      @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
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
    $(document).ready(function () {
        $('input[name=type]').change(function () {
            if (this.value == 'percentage') {
                $('#rate').attr({
                    "max": 100        // values (or variables) here
                });
                $('#typeSympole').html('<h5> %</h5>');
            } else if (this.value == 'amount') {
                $('#rate').removeAttr("max");
                $('#typeSympole').html('<h5> Pound(s)</h5>');
            }
        });
        $(document).on('keydown', '#rate', function () {
            // Save old value.
            console.log('value: ' + $(this).val());
            if ($('input[name=type]:checked').val() == 'percentage' && $(this).val() >= 0 && $(this).val() <= 100) {
                $(this).data("old", $(this).val());
            }

        });


        $(document).on('keyup', '#rate', function () {

            if ($('input[name=type]:checked').val() == 'percentage') {
                if ((parseInt($(this).val()) <= 100 && parseInt($(this).val()) >= 0) || $(this).val() == '')
                    ;
                else {

                    $(this).val($(this).data("old"));
                }

            }


        });

        $('#discountEnabler').change(function () {
            $('#discountBox').toggle();
        });
    });
    $('#demo-form').parsley();
    $(function () {
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
    $(document).ready(function () {
        $("#showUserInput").hide();
        $("#generateCodeButton").click(function () {
            $("#promocode").val(makecode());


        });
        $('#noOfUsers').on('change', function () {
            var usersNo = $(this).val();
            if (usersNo == 1) {


                $("#showUserInput").show();
                $("#showUserInput").click(function () {
                    var table = $('#users_datatable').DataTable({
                        processing: true,
                        serverSide: true,
                        destroy: true,
                        order: [0, 'asc'],
                        ajax: '{!! route('userslist') !!}',
                        columns: [
                            {
                                data: 'id', searchable: false, render: function (data, type, row) {
                                    return "<input type='radio' name='user' class='userRadio' value='" + data + " '>"
                                }
                            },
                            {data: 'name', name: 'name'},

                            {data: 'email', name: 'email'}


                        ],
                        drawCallback: function () {
                            $('.userRadio').change(function () {

                                $('#modal').modal().hide();

                                if ($('input[name=user]:checked').length > 0) {
                                    var selectedUser = $(this).val();
                                    $.ajax({
                                        url: "{!! route('getuser') !!}",
                                        data: {userid: selectedUser},
                                        success: function (data) {
                                            $('#showUserEmail').html(data);
                                        }
                                    });
                                    $('#showUserEmail').show();

                                    $('#modal').modal('toggle');
                                }
                            });
                        }
                        //do whatever
                    });


                });
            } else {
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
