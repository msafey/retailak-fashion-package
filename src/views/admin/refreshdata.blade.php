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
                        Refresh Data
                @endslot

                @slot('slot1')
                        Home
                @endslot

                @slot('current')
                          Refresh Data
                @endslot
                You are not allowed to access this resource!
                @endcomponent                <!--End Bread Crumb And Title Section -->
                <div class="alert alert-success alert-dismissible fade in" style="display:none;" id="productSuccess"
                     role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Success</strong> The Products was updated successfully
                    message.
                </div>


                <div class="alert alert-success alert-dismissible fade in" style="display:none;" id="categorySuccess"
                     role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Success</strong> The Categories was updated successfully
                    message.
                </div>

                <div class="alert alert-success alert-dismissible fade in" style="display:none;" id="supplierTypeSuccess"
                     role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Success</strong> The Supplier Types was updated successfully
                    message.
                </div>

                <div class="alert alert-success alert-dismissible fade in" style="display:none;" id="suppliersSuccess"
                     role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <strong>Success</strong> The Suppliers was updated successfully
                    message.
                </div>

                <div class="row">
                    <button class="btn btn-primary btn-md" id="refreshCategories"><i class="fa fa-refresh"></i>&nbsp;
                        Refresh Categories
                    </button>
                    &nbsp;
                    <button class="btn btn-primary btn-md" id="refreshProducts"><i class="fa fa-refresh"></i>&nbsp;
                        Refresh Products
                    </button>
                    &nbsp;
                  {{--   <button class="btn btn-primary btn-md" id="refreshSupplierTypes"><i class="fa fa-refresh"></i>&nbsp;
                        Refresh Supplier Types
                    </button>
                    &nbsp;
                    <button class="btn btn-primary btn-md" id="refreshSuppliers"><i class="fa fa-refresh"></i>&nbsp;
                        Refresh Supplier
                    </button> --}}
                </div>

                <center>
                    <div id="loading-img">

                    </div>
                </center>
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
<script>
    var loadingimg = "{{url('/public/imgs/loading5.gif')}}";
    $('#refreshCategories').on('click', function () {
        $('#loading-img').html('<img style="height:100px;" src="' + loadingimg + '" />');
        $.ajax({
           
            url: ' {{URL::to("api/allcategories")}}',

            success: function () {

            },
            complete: function () {
                $('#loading-img').html('')
                $('#categorySuccess').css('display', 'block');
            }
        });

    });

    $('#refreshProducts').on('click', function () {
        $('#loading-img').html('<img style="height:100px;" src="' + loadingimg + '" />');
        $.ajax({
           
            url: ' {{URL::to("api/allproducts")}}',

            success: function () {

            },
            complete: function () {
                $('#loading-img').html('')
                $('#productSuccess').css('display', 'block');
            }
        });

    });

    $('#refreshSupplierTypes').on('click', function () {
        $('#loading-img').html('<img style="height:100px;" src="' + loadingimg + '" />');
        $.ajax({
            url: ' {{URL::to("/admin/supplier/types/refresh/data")}}',

            success: function () {

            },
            complete: function () {
                $('#loading-img').html('');
                $('#supplierTypeSuccess').css('display', 'block');
            }
        });

    });

    $('#refreshSuppliers').on('click', function () {
        $('#loading-img').html('<img style="height:100px;" src="' + loadingimg + '" />');
        $.ajax({
            url: ' {{URL::to("/admin/suppliers/refresh/data")}}',

            success: function () {

            },
            complete: function () {
                $('#loading-img').html('');
                $('#suppliersSuccess').css('display', 'block');
            }
        });

    });

</script>
</html>