<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')
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
                        Unauthorized
                    @endslot

                    @slot('slot1')
                        Home
                    @endslot

                    @slot('current')
                        Unauthorized
                    @endslot
                    You are not allowed to access this resource!
            @endcomponent                <!--End Bread Crumb And Title Section -->

                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if( Session::has('success') )
                            <div class="alert alert-success">{{Session::get('success')}}</div>
                        @endif

                        @if( Session::has('failed') )
                            <div class="alert alert-danget">{{Session::get('failed')}}</div>
                        @endif

                        <p> Sorry You're not authorized to this page</p>

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
    <script type="text/javascript">
        $('#freeshipping').on('click', function () {
            if ($(this).is(':checked')) {
                $('#applied_amount').css('display', 'block');
            } else {
                $('#applied_amount').css('display', 'none');
            }
        });
    </script>
    <!-- JAVASCRIPT AREA -->
</body>

</html>
