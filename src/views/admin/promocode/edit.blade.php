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

    <!-- Switchery css -->

    <!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>

    <script src="{{url('public/admin/js/modernizr.min.js')}}"></script>


</head>


<body class="fixed-left">
<?php
$assignedUser = null;
?>
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
                {!! Form::open(['url' => '/admin/promocode/'.$promocode->id.'/update',
               'method'=>'POST',
               'class'=>'form-hirozontal ',
               'id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}


                <div class="card card-block">
                    <div style="margin-left: 13px" class="card-text">

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-group" for="promocode">Code
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" value="{{$promocode->code}}" required="required" id="promocode" disabled/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-group" for="min_required_price">
                                    Min. Required Price To Apply
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="number" step="any" min="0" max="10000000000" value="{{$promocode->min_required_price}}"
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
                                <input type="number" step="any" min="0" max="10000000000" value="{{$promocode->max_required_price}}"
                                       name="max_required_price" required="required" id="max_required_price"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2 form-group">
                                Free Shipping
                                <input type="checkbox" name="freeshipping" @if($promocode->freeShipping == 1) checked
                                       @endif data-plugin="switchery" data-color="#ff5d48"/>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2 form-group">
                                Discount
                                <input type="checkbox" id="discountEnabler" @if($promocode->reward != 0) checked
                                       @endif data-plugin="switchery" data-color="#ff5d48"/>

                            </div>
                            <div class="col-sm-6" id="discountBox"
                                 @if($promocode->reward == 0) style="display: none;" @endif>
                                <div class="row">
                                    <div class="col-sm-6" style="margin:0">
                                        <label for="percentage" @if($promocode->type === 'percentage'
                                        || $promocode->type === 'persentage') checked
                                               @endif class="c-input c-radio">
                                            <input type="radio" name="type" id="percentage" value="percentage">
                                            <span class="c-indicator"></span>Percentage </label>
                                        <label for="amount" class="c-input c-radio">
                                            <input type="radio" @if($promocode->type == 'amount') checked
                                                   @endif name="type" id="amount" value="amount">
                                            <span class="c-indicator"></span>Amount </label>

                                    </div>
                                    <div class="col-sm-2">
                                        <input type="number" step="any" id="rate" value="{{$promocode->reward}}"
                                               name="rate" style="width:80px;">
                                    </div>
                                    <div class="col-sm-2" id="typeSympole">
                                        @if($promocode->type == 'amount')
                                            <h5> Pound(s)</h5>
                                        @else
                                            <h5> %</h5>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>

                        {{--Orders--}}
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="nofusers">No. of Users<span style="color:red;">*</span>:
                                </label>
                                <input id="nofusers" type="text" name="nofusers" value="{{$promocode->userscount}}">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3">
                                <label class="form-group" for="usage_per_user">Usage Per User
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <input type="number" step="1" value="{{$promocode->usage_per_user}}"
                                       id="usage_per_user" name="usage_per_user"/>
                            </div>
                        </div>
                        <div class="row">
                            @if(!$promocodeUsers->isEmpty())
                                @foreach($promocodeUsers as $promocodeUser)
                                    <div class="row">
                                        <div class="col-sm-4" id="showUSerEmail">{{$promocodeUser->id}}</div>
                                        <?php $assignedUser = $promocodeUser->id; ?>
                                    </div>
                                @endforeach
                                <div class="row" id="showUserInput2">
                                    <div class="col-sm-3">
                                        <button class="btn btn-primary waves-effect waves-light tableBtn" type="button"
                                                data-toggle="modal" data-target=".bs-example-modal-lg">Change User
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="row" id="showUserInput" style="display:none;">
                                    <button class="btn btn-primary waves-effect waves-light tableBtn" type="button"
                                            data-toggle="modal" data-target=".bs-example-modal-lg">Choose User
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{--Hidden Time Section--}}


                        <div class="row">
                            <div class="col-sm-3">
                                <label style="margin-left: 12px" class="form-group" for="from">From
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
                                <label style="margin-left: 12px" class="form-group" for="to">To
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
                                <label style="margin-left: 12px" class="form-group" for="status">Status
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <label class="c-input c-radio">
                                    <input id="Active" name="status" @if($promocode->active==1) checked="checked"
                                           @endif value="active" type="radio">
                                    <span class="c-indicator"></span> Active
                                </label>
                                <label class="c-input c-radio">
                                    <input id="inactive" required="required"
                                           @if($promocode->active==0) checked="checked" @endif value="inactive"
                                           name="status" type="radio">
                                    <span class="c-indicator"></span> In Active
                                </label>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-sm-32">
                                <button type="submit" style="margin-left: 12px" class="btn btn-primary">Update</button>
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
                                        <th>Phone</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
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
<script src="{{url('/public/admin/plugins/moment/')}}/moment.js"></script>
<script src="{{url('/public/admin/')}}/js/bootstrap-datetimepicker.js"></script>
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
<script type="text/javascript">
    <?php
    $date = new DateTime("$promocode->sfrom");
    $sfrom = $date->getTimestamp() * 1000;
    $date2 = new DateTime("$promocode->sto");
    $sto = $date2->getTimestamp() * 1000;
    ?>

    $(document).ready(function () {
        $('input[type=radio][name=type]').change(function () {
            if (this.value == 'percentage') {
                $('#typeSympole').html('<h5> %</h5>');
            } else if (this.value == 'amount') {
                $('#typeSympole').html('<h5> Pound(s)</h5>');
            }
        });
        $('#discountEnabler').change(function () {
            if (!$(this).is(":checked")) {
                // console.log(235);
                $('#rate').val('');
            }
            // if('#discountEnabler').checked('')
            $('#discountBox').toggle();
        });
    });


    $('#demo-form').parsley();
    $(function () {
        $('#datetimepicker1').datetimepicker({
            date: moment({{$sfrom}}),
            //useCurrent: false,
            icons:
                {
                    time: 'zmdi zmdi-time',
                    date: 'zmdi zmdi-calendar',
                    up: 'zmdi zmdi--up',
                    down: 'zmdi zmdi--down',
                    previous: 'zmdi zmdi-backward',
                    next: 'zmdi zmdi-right',
                    today: 'zmdi zmdi-screenshot',
                    clear: 'zmdi zmdi-trash',
                    close: 'zmdi zmdi-remove'
                },
        });
        $('#datetimepicker2').datetimepicker({
            date: moment({{$sto}}),
            // useCurrent: false,
            icons: {
                time: 'zmdi zmdi-time',
                date: 'zmdi zmdi-calendar',
                up: 'zmdi zmdi--up',
                down: 'zmdi zmdi--down',
                previous: 'zmdi zmdi-backward',
                next: 'zmdi zmdi-right',
                today: 'zmdi zmdi-screenshot',
                clear: 'zmdi zmdi-trash',
                close: 'zmdi zmdi-remove'
            }
        });
    });
    $(document).ready(function () {
        $("#generateCodeButton").click(function () {
            $("#promocode").val(makecode());
        });
        $('#nofusers').on('keyup', function () {
            var usersNo = $(this).val();
            if (usersNo == 1) {
                $("#showUserInput2").show();
                $("#showUserInput").show();
                $("#showUSerEmail").show();
            } else {
                $("#showUserInput2").hide();
                $("#showUserInput").show();
                $("#showUSerEmail").hide();
            }
        });
        $(".tableBtn").click(function () {

            @if($assignedUser!=null)
            if (!window.assignedUser)
                window.assignedUser = {{$assignedUser}};
                @endif
            var table = $('#users_datatable').DataTable(
                {
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    order: [0, 'asc'],
                    ajax: '{!! route('userslist') !!}',
                    columns:
                        [
                            {
                                data: 'id', searchable: false, render:
                                    function (data, type, row) {

                                        if (window.assignedUser == data)
                                            return "<input type='radio' name='user' checked='checked' class='userRadio' value='" + data + "'>"
                                        else
                                            return "<input type='radio' name='user'  class='userRadio' value='" + data + "'>"
                                    }
                            },
                            {data: 'name', name: 'name'},
                            {data: 'email', name: 'email'}
                        ],
                    drawCallback:
                        function () {
                            $('.userRadio').change(function () {
                                if ($('input[name=user]:checked').length > 0) {

                                    var selectedUser = $(this).val();
                                    window.assignedUser = selectedUser;

                                    $.ajax({
                                        url: "{!! route('getuser') !!}",
                                        data: {userid: window.assignedUser},
                                        success: function (data2) {
                                            $('#showUSerEmail').html(data2);
                                            alert(data2);
                                        }
                                    });
                                    $('#showUSerEmail').show();
                                }
                                //$('#modal').modal('toggle');
                            });
                        }
                    //do whatever
                });
        });
        //DataTable
    });

</script>
{{--<script src="{{url('public/admin/pages/jquery.form-pickers.init.js')}}"></script>--}}
<!-- JAVASCRIPT AREA -->
</body>
</html>
