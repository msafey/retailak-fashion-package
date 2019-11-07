<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')
</head>

<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">

    <div class="ex-page-content text-xs-center">
        <div class="text-error">4<span class="ion-sad"></span>1</div>
        <h3 class="text-uppercase text-white font-600"> Access denied</h3>
        <p class="text-white m-t-30">
            You have no permission.
        </p>
        <br>
        <a class="btn btn-pink waves-effect waves-light" href="{{url('/login')}}"> Return Home</a>

    </div>


</div>
<!-- end wrapper page -->

<script>
    var resizefunc = [];
</script>

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

<!-- App js -->
<script src="{{url('public/admin/js/jquery.core.js')}}"></script>
<script src="{{url('public/admin/js/jquery.app.js')}}"></script>


</html>