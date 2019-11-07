<!DOCTYPE html>
<html>
<head>
@include('layouts.admin.head')
<!-- DataTables -->
    <link href="{{url('public/admin/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{url('public/admin/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <!-- DataTables -->
</head>

<body class="fixed-left">
<!-- Begin page -->
<div id="wrapper">
    <!-- Top Bar Start -->
@include('layouts.admin.topbar')
<!-- Top Bar End -->
    <!-- ========== Left Sidebar Start ========== -->
@include('layouts.admin.sidemenu')

<!-- Start right Content here -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
@include('layouts.admin.flashMessage')

                {{--End of Showing Flash Messages--}}

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="text-white">{{ $error }} <i class="fas fa-times-circle" style="float:right;"></i> </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form  action="{!! route('AddNewDrawing') !!}" method="post" style="width: 70%; margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;">
                    {{ csrf_field() }}

                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Money Drawing EGP </label>
                        <div class="col-sm-9">
                            <input name="money" type="text" class="form-control"  placeholder="Money Drawing">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">For Employee Person </label>
                        <div class="col-sm-9">
                            <input name="person" type="text" class="form-control"  placeholder="Employee Name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Reason</label>
                        <div class="col-sm-9">
                            <textarea rows="6" name="reason" type="text" class="form-control"  placeholder="Reason"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Type </label>
                        <div class="col-sm-9">

                            <div class="form-check form-check-inline">
                                <div style="float: left;">
                                    <input name="type" class="form-check-input" type="radio" id="inlineCheckbox1" value="0">
                                    <label class="form-check-label" for="inlineCheckbox1">Salary</label>
                                </div>

                                <div style="float: right;">
                                    <input name="type" class="form-check-input" type="radio" id="inlineCheckbox2" value="1">
                                    <label class="form-check-label" for="inlineCheckbox2">Other Drawings</label>
                                </div>
                            </div>

                        </div>
                    </div>

                    <button class="btn btn-block btn-primary col-6">
                        Save
                    </button>

                </form>
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
@include('layouts.admin.javascript')
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
<script>
    setTimeout(function() {
        $('#SessionMessage').fadeOut('1000');
    }, 2000); // <-- time in milliseconds
</script>
<!-- JAVASCRIPT AREA -->
</body>
</html>
