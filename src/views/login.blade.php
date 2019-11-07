<!DOCTYPE html>
<html>
<head>
    @include('layouts.admin.head')
</head>


<body>

<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">

    <div class="account-bg">
        <div class="card-box m-b-0">
            <div class="text-xs-center m-t-20">
                <a href="#" class="logo">
                   <link rel="shortcut icon" href="{{url('public/admin/images/retailak_f_navbar.ico')}}">

                </a>
            </div>
            <div class="m-t-10 p-20">
                <div class="row">
                    <div class="col-xs-12 text-xs-center">
                        <h6 class="text-muted text-uppercase m-b-0 m-t-0">Sign In</h6>
                    </div>
                </div>
                <form class="m-t-20" method="POST" action="{{ url('/admin/login') }}">
                    {{ csrf_field() }}


                    <div class="form-group{{$errors->has('email') ? ' has-error' : '' }} row">
                        <div class="col-xs-12">
                            <input class="form-control"id="email" name="email" type="email" required="" placeholder="{{ old('email')}}">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} row">
                        <div class="col-xs-12">
                            <input class="form-control" id="password" type="password" name="password" required="" placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12">
                            <div class="checkbox checkbox-custom">
                                <input id="checkbox-signup" type="checkbox">
                                <label for="checkbox-signup">
                                    Remember me
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center row m-t-10">
                        <div class="col-xs-12">
                            <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Log In</button>
                        </div>
                    </div>

                    <div class="form-group row m-t-30 m-b-0">
                        <div class="col-sm-12">
                            <a href="{{ url('/admin/password/reset') }}" class="text-muted"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                        </div>
                    </div>



                </form>

            </div>

            <div class="clearfix"></div>
        </div>
    </div>
    <!-- end card-box-->
<!-- 
    <div class="m-t-20">
        <div class="text-xs-center">
            <p class="text-white">Don't have an account? <a href="{{ url('/admin/register') }}" class="text-white m-l-5"><b>Sign Up</b></a></p>
        </div>
    </div> -->

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

</body>
</html>