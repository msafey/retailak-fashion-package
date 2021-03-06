<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- Script Name&Des  -->
    <link href="{{url('public/admin/css/parsley.css')}}" rel="stylesheet" type="text/css"/>


    <!-- Plugins css-->
    <link href="{{url('public/admin/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet"/>
    <link href="{{url('public/admin/plugins/multiselect/css/multi-select.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{url('public/admin/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>

    <link href="{{url('components/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css"/>


    <!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- Responsive datatable examples -->
    <link href="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>

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
        a {
            color: black;
        }

        .header-title {
            font-size: 1.1rem;
            text-transform: none;
        }</style>
    <style>
        .hide {
            display: none;
        }

        .select2-hidden-accessible {
            position: static !important;

        }
        .mydropdown  li{
            height: 75px;
        }
        li a span.text {
            word-wrap: break-word;
            white-space: normal;
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
                        Push Notification
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Push Notification
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

                {!! Form::open(['url' => '/admin/notifications/users/add', 'class'=>'form-hirozontal ',
                'id'=>'demo-form','files' => true, 'data-parsley-validate'=>'']) !!}

                <div class="col-md-12 card card-block">
                    <div class="form-group col-md-12">
                        <div class="  col-sm-4">
                            <label for="series_name"> Title <span
                                        style="color:red;">*</span>:</label>
                            <input name="title" type="text"
                                   class="form-control" id="title"
                                   placeholder=""
                                   required="required"
                                   data-parsley-maxlength="50"/>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="col-sm-4">
                            <label for="seriesdesc_en">Message</label>
                            <div id="textControlEn">
                            <textarea required name="message" class="form-control"
                                      id="message" rows="3">
                            </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">

                        <div class="form-group col-sm-3">
                            <label>Product</label>

                            <select name="product_id" class="mydropdown select2 form-control "
                                    data-placeholder="Select Product ...">
                                <option value=""></option>
                                @foreach($products as $product)
                                    <option value="{{$product->id}}">
                                        {{$product->name}} -
                                        {{$product->item_code}}


                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-2">
                            <label>Promo Code</label>
                            <select required name="app_version" class="select2 form-control">
                                <option>Select PromoCode..</option>
                                @foreach($promoCodes as $promoCode)
                                    <option value={{$promoCode->code}}>{{$promoCode->code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">
                                    <h4 class="m-t-0 header-title"><b>Users</b></h4>
                                    <table id="datatable" class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>select</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                        </tr>
                                        </thead>


                                        <tbody>
                                        @foreach($users as $user)

                                            <tr>
                                                <td>
                                                    <input type="radio" name="device_token" value="{{$user->device_token}}">
                                                </td>
                                                <td>
                                                    {{$user->name}}
                                                </td>
                                                <td>
                                                    {{$user->email}}
                                                </td>

                                            </tr>


                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- end row -->
                    </div>
                </div>
                <div class=" form-group col-md-12">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary"><i
                                    class=""></i>
                            Send Push Notification
                        </button>
                        <button type="button" class="btn btn-default"><i
                                    class="zmdi "></i>
                            Back
                        </button>
                    </div>
                </div>

            </div>


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

<!-- JAVASCRIPT AREA -->


<!-- jQuery  -->
<script src="{{url('public/admin/js/jquery.min.js')}}"></script>
<script src="{{url('public/admin/js/tether.min.js')}}"></script><!-- Tether for Bootstrap -->
<script src="{{url('public/admin/js/bootstrap.min.js')}}"></script>
<script src="{{url('public/admin/js/detect.js')}}"></script>
<script src="{{url('public/admin/js/fastclick.js')}}"></script>
<script src="{{url('public/admin/js/jquery.blockUI.js')}}"></script>
<script src="{{url('public/admin/js/waves.js')}}"></script>
<script src="{{url('public/admin/js/jquery.nicescroll.js')}}"></script>
<script src="{{url('public/admin/js/jquery.scrollTo.min.js')}}"></script>
<script src="{{url('public/admin/js/jquery.slimscroll.js')}}"></script>
<script src="{{url('public/admin/plugins/switchery/switchery.min.js')}}"></script>

<!-- Required datatable js -->
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
<!-- Responsive examples -->
<script src="{{url('public/admin/plugins/datatables/dataTables.responsive.min.js')}}"></script>
<script src="{{url('public/admin/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>

<script src="{{url('components/select2/dist/js/select2.js')}}"></script>
<!-- Buttons examples -->

<script src="{{url('public/admin/js/jquery.core.js')}}"></script>
<script src="{{url('public/admin/js/jquery.app.js')}}"></script>


<script type="text/javascript">
    $(".mydropdown").select2();
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#datatable').DataTable();

        //Buttons examples
        var table = $('#datatable-buttons').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'colvis']
        });

        table.buttons().container()
            .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
    });

</script>

<script>
    $("#advanced").click(function (event) {
        event.preventDefault();
        $('#options').toggle();
    });
</script>
<!-- Laravel Javascript Validation -->


<!-- JAVASCRIPT AREA -->
</body>
</html>


