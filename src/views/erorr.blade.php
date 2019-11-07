

<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')
</head>

<body class="fixed-left">
<!-- Begin page -->
<div id="wrapper">
    <!-- Top Bar Start -->
{{--@include('layouts.admin.topbar')--}}
<!-- Top Bar End -->
    <!-- ========== Left Sidebar Start ========== -->
{{--@include('layouts.admin.sidemenu')--}}
<!-- Left Sidebar End -->

    <!-- Start right Content here -->
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <!-- Bread Crumb And Title Section -->
            {{--@include('layouts.admin.breadcrumb')--}}
            <!--End Bread Crumb And Title Section -->
                <div class="row">
                    <h1>Erorr</h1>
                    <h3>Page Not Found 404</h3>
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
<!-- JAVASCRIPT AREA -->
</body>
</html>